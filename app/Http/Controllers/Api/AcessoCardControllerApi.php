<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AwardRepository;
use App\Repositories\HistoryAcessoCardRepository;
use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AcessoCardControllerApi extends Controller
{
    private $historyAcessoCardRepo;
    private $acessoCardService;
    private $awardRepo;
    private $baseAcessoCardsCompletoService;

    public function __construct(HistoryAcessoCardRepository $historyAcessoCardRepo,
        AwardRepository $awardRepo,
        AcessoCardService $acessoCardService,
        BaseAcessoCardsCompletoService $baseAcessoCardsCompletoService)
    {
        $this->historyAcessoCardRepo = $historyAcessoCardRepo;
        $this->awardRepo = $awardRepo;
        $this->acessoCardService = $acessoCardService;
        $this->baseAcessoCardsCompletoService = $baseAcessoCardsCompletoService;
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
            $cards[] = $this->historyAcessoCardRepo->getInfoBaseAcessoCardsAndAcessoCardsByAwardId($id);
            $params[] = $this->awardRepo->find($id);

            $this->acessoCardService->saveByParam([
                'acesso_card_generated' => 1,
            ], 'acesso_card_award_id', $id);
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

            $awardId = $card->acesso_card_award_id;
            $hasShipmentApiAward = \App\ShipmentApi::where('shipment_award_id', $awardId)
                ->first();

            if (!$hasShipmentApiAward) {
                \App\ShipmentApi::create([
                    'shipment_award_id' => $awardId,
                    'shipment_generated' => 1,
                    'shipment_last_field' => $shipmentFieldNumber,
                    'shipment_file' => $filename,
                ]);
            }

            $demandId[] = $card->acesso_card_demand_id;
        }

        foreach ($params as $param) {
            \App\CashFlow::create([
                'flow_movement_date' => date('Y-m-d'),
                'flow_bank_id' => 1,
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
            $sheet->setCellValue("A{$key}", $card->shipment_number);
            $sheet->setCellValue("B{$key}", $card->base_acesso_card_proxy, DataType::TYPE_STRING);
            $sheet->setCellValue("C{$key}", $card->acesso_card_value);
        }

        $writer = new Xlsx($spreadsheet);

        $writer->save($storageFileName);

        return $filename;
    }

    public function update(Request $request, $id)
    {
        $data = $this->validate($request, [
            'acesso_card_chargeback' => 'required|boolean',
        ]);

        $lessAcessoCardValue = (float) \App\AcessoCard::select('acesso_card_value')
                ->where('id', $id)
                ->whereNull('acesso_card_chargeback')
                ->first()
                ->acesso_card_value;

        $acessoCardTotal = (float) \App\AcessoCard::select(DB::raw('sum(acesso_card_value) as total'))
                ->where('acesso_card_award_id', $request->award_id)
                ->whereNull('acesso_card_chargeback')
                ->first()
                ->total;

        $total = (float) $acessoCardTotal - $lessAcessoCardValue;

        \App\Award::where('id', $request->award_id)
            ->update([
                'awarded_value' => $total
            ]);

        $this->acessoCardService->save($data, $request->award_id);
        return redirect()->back();
    }
}
