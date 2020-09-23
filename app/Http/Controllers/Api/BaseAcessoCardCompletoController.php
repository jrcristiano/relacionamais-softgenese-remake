<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BaseAcessoCardCompletoController extends Controller
{
    private $baseAcessoCardsCompletoService;
    private $acessoCardService;

    public function __construct(BaseAcessoCardsCompletoService $baseAcessoCardsCompletoService, AcessoCardService $acessoCardService)
    {
        $this->baseAcessoCardsCompletoService = $baseAcessoCardsCompletoService;
        $this->acessoCardService = $acessoCardService;
    }

    public function update(Request $request, $id)
    {
        $findAwardIdByFile = \App\ShipmentApi::select(['shipment_award_id'])
            ->where('shipment_file_vinc', $request->shipment_file_vinc)
            ->get();

        $data = [];
        foreach ($findAwardIdByFile as $item) {
            $findBaseAcessoCards = $this->baseAcessoCardsCompletoService->getAcessoCardCompletoNotGenerated($item->shipment_award_id);
            $data[] = $this->acessoCardService->getAcessoCardCompletoNotGenerated($item->shipment_award_id);

            foreach ($findBaseAcessoCards as $findBaseAcessoCard) {
                $this->baseAcessoCardsCompletoService->saveByDocument([
                    'base_acesso_card_generated' => 1,
                ], $findBaseAcessoCard->acesso_card_document);
            }
        }

        $findShipmentsApiNotVincGenerateds = \App\ShipmentApi::where('shipment_file_vinc', $request->shipment_file_vinc)
            ->get();

        foreach ($findShipmentsApiNotVincGenerateds as $findShipmentsApiNotVincGenerated) {
            \App\ShipmentApi::where('shipment_file_vinc', $request->shipment_file_vinc)->update([
                'shipment_file_vinc_generated' => 1
            ]);
        }

        $collections = [];
        foreach ($data as $awards) {
            foreach ($awards as $award) {
                $collections[] = $award;
            }
        }

        $spreadsheet = new Spreadsheet;

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'PROXY');
        $sheet->setCellValue('B1', 'CPF');
        $sheet->setCellValue('C1', 'NOME');

        foreach ($collections as $key => $value) {
            $key = $key + 2;
            $proxy = $value->base_acesso_card_proxy;

            $sheet->setCellValueExplicit(
                "A{$key}",
                $proxy,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValueExplicit(
                "B{$key}",
                $value->acesso_card_document,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValue("C{$key}", $value->acesso_card_name);
        }

        $writer = new Xlsx($spreadsheet);

        $path = storage_path();
        $awardId = str_pad($id, 2, '0', STR_PAD_LEFT);

        $field = \App\ShipmentApi::select('shipment_last_field')
            ->where('shipment_award_id', $awardId)
            ->first();

        $field = str_pad($field->shipment_last_field, 2, '0', STR_PAD_LEFT);

        $storageFileName = "{$path}/app/public/shipments/TODOSVINC{$field}.xlsx";

        $writer->save($storageFileName);

        return "TODOSVINC{$field}.xlsx";
    }
}
