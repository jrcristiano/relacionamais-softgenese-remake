<?php

namespace App\Http\Controllers\Api;

use App\HistoryAcessoCard;
use App\Http\Controllers\Controller;
use App\Repositories\HistoryAcessoCardRepository;
use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AcessoCardControllerApi extends Controller
{
    private $baseAcessoCardCompleto;
    private $acessoCardService;
    private $historyAcessoCardRepo;

    public function __construct(BaseAcessoCardsCompletoService $baseAcessoCardCompleto,
        AcessoCardService $acessoCardService,
        HistoryAcessoCardRepository $historyAcessoCardRepo)
    {
        $this->baseAcessoCardCompleto = $baseAcessoCardCompleto;
        $this->acessoCardService = $acessoCardService;
        $this->historyAcessoCardRepo = $historyAcessoCardRepo;
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

        foreach ($data as $id) {
            $cards[] = $this->historyAcessoCardRepo->getInfoBaseAcessoCardsAndAcessoCardsByAwardId($id);
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

                \App\CashFlow::create([
                    'flow_movement_date' => date('Y-m-d'),
                    'flow_bank_id' => 1,
                    'flow_award_id' => $id,
                    'flow_award_generated_shipment' => date('Y-m-d'),
                    'flow_demand_id' => $card->acesso_card_demand_id,
                ]);
            }
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
}
