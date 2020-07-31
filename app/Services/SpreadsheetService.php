<?php

namespace App\Services;

use App\Helpers\Text;
use App\Repositories\SpreadsheetRepository;
use Bissolli\ValidadorCpfCnpj\Documento;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yidas\phpSpreadsheet\Helper;

class SpreadsheetService
{
    private $service;
    private $messageErrors = [];
    private $result = [];
    private $awardedValue = 0;

    public function __construct(SpreadsheetRepository $service)
    {
        $this->service = $service;
    }

    public function updateSpreadsheet($data, $id)
    {
        $spreadsheet = $this->service->getData($id);
        $fileName = $spreadsheet->awarded_upload_table;
        $fileName = storage_path('app/public/') . $fileName;

        $spreadsheetIO = IOFactory::load($fileName);
        $sheet = $spreadsheetIO->getActiveSheet();

        $sheet->setCellValueExplicit("A{$spreadsheet->spreadsheet_keyline}", $data['spreadsheet_name'], DataType::TYPE_STRING);
        $sheet->setCellValueExplicit("B{$spreadsheet->spreadsheet_keyline}", $data['spreadsheet_document'], DataType::TYPE_STRING);
        $sheet->setCellValueExplicit("C{$spreadsheet->spreadsheet_keyline}", $data['spreadsheet_value'], DataType::TYPE_STRING);
        $sheet->setCellValueExplicit("D{$spreadsheet->spreadsheet_keyline}", $data['spreadsheet_bank'], DataType::TYPE_STRING);
        $sheet->setCellValueExplicit("E{$spreadsheet->spreadsheet_keyline}", $data['spreadsheet_agency'], DataType::TYPE_STRING);
        $sheet->setCellValueExplicit("F{$spreadsheet->spreadsheet_keyline}", $data['spreadsheet_account'], DataType::TYPE_STRING);
        $sheet->setCellValueExplicit("G{$spreadsheet->spreadsheet_keyline}", $data['spreadsheet_account_type'], DataType::TYPE_STRING);

        try {
            $writer = new Xlsx($spreadsheetIO);
            $writer->save($fileName);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao salvar planilha!');
        }
    }

    public function isDocumentValid($fileName)
    {
        $excel = Helper::newSpreadsheet($fileName)->getRows();

        foreach ($excel as $key => $row) {
            if ($excel[$key][0] == null) {
                unset($excel[$key]);
            }

            $key = $key + 1;
            $row[1] = Text::cleanDocument($row[1]);
            $row[1] = str_pad($row[1], 11, '0', STR_PAD_LEFT);
            $awardedValue = (float) $row[2];
            $document = (string) Text::clean($row[1]);
            $documentValidator = new Documento($document);

            try {
                $this->isAllDocumentValid($documentValidator, $document, $key);
            } catch (\Throwable $e) {
                return redirect()->back()->withErrors('Erro ao enviar planilha, verifique-a e tente novamente.');
            }

            $this->awardedValue += $awardedValue;
        }

        // dd($this->awardedValue);

        if (in_array(false, $this->result)) {
            return false;
        }

        return true;
    }

    public function isAllDocumentValid(Documento $documentObj, $document, $key)
    {
        if (!$documentObj->isValid()) {
            $this->result[] = $documentObj->isValid();
            $this->messageErrors[] = [ 'line' => $key, 'document' => $document ];
            return false;
        }
        return true;
    }

    public function getMessageErrors()
    {
        return $this->messageErrors;
    }

    public function getAwardedValue()
    {
        return $this->awardedValue;
    }
}

