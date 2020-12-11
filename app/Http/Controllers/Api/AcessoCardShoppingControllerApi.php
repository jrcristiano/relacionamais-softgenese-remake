<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AwardRepository;
use App\Repositories\HistoryAcessoCardComprasRepository;
use App\Services\AcessoCardShoppingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AcessoCardShoppingControllerApi extends Controller
{
    private $historyAcessoCardShoppingRepo;
    private $acessoCardShoppingService;
    private $awardRepo;

    public function __construct(HistoryAcessoCardComprasRepository $historyAcessoCardShoppingRepo,
        AwardRepository $awardRepo,
        AcessoCardShoppingService $acessoCardShoppingService)
    {
        $this->historyAcessoCardShoppingRepo = $historyAcessoCardShoppingRepo;
        $this->awardRepo = $awardRepo;
        $this->acessoCardShoppingService = $acessoCardShoppingService;
    }

    public function store(Request $request)
    {
        $data = $request->get('data');

        $cards = [];
        $lastField = \App\ShipmentApi::select('shipment_last_field')
                ->orderBy('shipment_last_field', 'desc')
                ->whereDate('created_at', '=', date('Y-m-d'))
                ->first();

        $setLastField = !$lastField ? 1 : $lastField->shipment_last_field + 1;
        $shipmentFieldNumber = $setLastField;

        $params = [];
        foreach ($data as $id) {
            $cards[] = $this->historyAcessoCardShoppingRepo->getInfoBaseAcessoCardsAndAcessoCardsByAwardId($id);
            $params[] = $this->awardRepo->find($id);

            $this->acessoCardShoppingService->saveByParam([
                'acesso_card_shopping_generated' => 1,
            ], 'acesso_card_shopping_award_id', $id);
        }

        $collectCards = [];
        foreach ($cards as $key => $objCards) {
            foreach ($objCards as $objCard) {
                $objCard->shipment_number = str_pad($shipmentFieldNumber, 4, '0', STR_PAD_LEFT);
                $collectCards[] = $objCard;
            }
        }

        $cards = [];
        foreach ($collectCards as $card) {
            $date = Carbon::parse(Carbon::now())->format('dm');
            $field = str_pad($shipmentFieldNumber, 2, '0', STR_PAD_LEFT);

            $path = storage_path('app/public/shipments');
            $filename = "R{$date}{$field}.xlsx";
            $storageFileName = "{$path}/{$filename}";

            $awardId = $card->acesso_card_shopping_award_id;
            $hasShipmentApiAward = \App\ShipmentApi::where('shipment_award_id', $awardId)
                ->first();

            if (!$hasShipmentApiAward) {
                \App\ShipmentApi::create([
                    'shipment_award_id' => $awardId,
                    'shipment_generated' => 1,
                    'shipment_last_field' => $shipmentFieldNumber,
                    'shipment_file' => $filename,
                    'shipment_file_vinc' => "VINC{$date}{$field}.xlsx",
                ]);
            }

            $demandId[] = $card->acesso_card_shopping_demand_id;
        }

        foreach ($params as $param) {
            \App\CashFlow::create([
                'flow_movement_date' => date('Y-m-d'),
                'flow_bank_id' => 3,
                'flow_award_id' => $param->id,
                'flow_award_generated_shipment' => date('Y-m-d'),
                'flow_demand_id' => $param->awarded_demand_id,
            ]);
        }

        $spreadsheet = new Spreadsheet;

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'CODPRGCRG');
        $sheet->setCellValue('B1', 'PROXY');
        $sheet->setCellValue('C1', 'VALOR');

        foreach ($collectCards as $key => $card) {
            $key = $key + 2;
            $proxy = $card->acesso_card_shopping_proxy;
            $sheet->setCellValue("A{$key}", "R{$date}{$field}");
            $sheet->setCellValueExplicit(
                "B{$key}",
                $proxy,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValue("C{$key}", $card->acesso_card_shopping_value);
        }

        $writer = new Xlsx($spreadsheet);

        $writer->save($storageFileName);

        return $filename;
    }

    public function update(Request $request, $id)
    {
        $lessAcessoCardValue = (float) \App\AcessoCardShopping::select('acesso_card_shopping_value')
                ->where('id', $id)
                ->whereNull('acesso_card_shopping_chargeback')
                ->first()
                ->acesso_card_shopping_value ?? 0;

        $acessoCardTotal = (float) \App\AcessoCardShopping::select(DB::raw('sum(acesso_card_shopping_value) as total'))
                ->where('acesso_card_shopping_award_id', $request->get('premiado_id'))
                ->whereNull('acesso_card_shopping_chargeback')
                ->first()
                ->total ?? 0;

        $total = (float) $acessoCardTotal - $lessAcessoCardValue;

        \App\Award::where('id', $request->get('premiado_id'))
            ->update([
                'awarded_value' => $total
            ]);

        $awardId = $request->get('premiado_id');

        \App\AcessoCardShopping::where('id', $id)
            ->update([
                'acesso_card_shopping_chargeback' => 1
            ]);

        $findAward = $this->awardRepo->find($awardId);
        $this->awardRepo->save([
            'awarded_value' => $total,
        ], $findAward->id);

        return redirect()->back();
    }
}
