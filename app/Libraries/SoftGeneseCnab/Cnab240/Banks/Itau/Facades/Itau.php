<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Facades;

use App\Helpers\Collection;
use App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Itau as ItauBank;

class Itau
{
    private $itau;
    protected $data = [];
    protected $models;
    protected $lotes;
    protected $lines;
    protected $fields;

    protected $rows = 0;
    protected $lote = 0;

    public function __construct()
    {
        $this->itau = new ItauBank;
    }

    public function getLines()
    {
        return $this->itau->getLines();
    }

    public function setModels($models)
    {
        $this->models = $models;
        return $this;
    }

    public function getModels()
    {
        return $this->models;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getLote()
    {
        return $this->lote;
    }

    public function run()
    {
        $fields = $this->getData();
        $fields = $this->getCollectionBanks($fields);

        foreach ($fields as $key => $values) {
            $this->lote++;
            $lote = $this->lote;
            foreach ($values as $k => $value) {
                $this->rows++;
                $rows = $this->itau->setBankCode($value['bank_code'])
                    ->setValue($value['value'])
                    ->setNoteFiscal($value['note_fiscal'])
                    ->setDocumentType($value['document_type'])
                    ->setDebitAgency($value['debit_agency'])
                    ->setDebitAccount($value['debit_account'])
                    ->setPayeeName($value['payee_name'])
                    ->setNumberCreatedByCompany($value['number_created_by_company'])
                    ->setDebitDocumentNumber($value['debit_document_number']);

                $bankCode = $value['bank_code'];

                if ($bankCode == 341 && ($value['spreadsheet_account_type'] == 'C' || $value['spreadsheet_account_type'] == null || $value['spreadsheet_account_type'] == '')) {
                    $data['itau_bank_lotes'][] = $lote;
                    $data['itau_key'] = $lote;
                    $data['itau_bank_value']['value_total'][] = toIntLiteral($value['value']);
                    $data['type_account'][] = $value['spreadsheet_account_type'];
                    $rowBank['itau'][] = $rows->setDebitAccount($value['debit_account'])
                        ->getRegisterDetail($value['value'], $lote);
                }

                if ($bankCode == 341 && $value['spreadsheet_account_type'] == 'P') {
                    $data['itau_bank_lotes_savings'][] = $lote;
                    $data['itau_key_savings'] = $lote;
                    $data['itau_bank_value_savings']['value_total'][] = toIntLiteral($value['value']);
                    $data['type_account_savings'][] = $value['spreadsheet_account_type'];
                    $rowBank['itau_savings'][] = $rows->setDebitAccount($value['debit_account'])
                        ->getRegisterDetailLoteSavings($value['value'], $lote);
                }

                if ($bankCode != 341) {
                    $data['others_bank_lotes'][] = $lote;
                    $data['others_key'] = $lote;
                    $data['others_bank_value']['value_total'][] = toIntLiteral($value['value']);
                    $rowBank['others'][] = $rows->setDebitAccountOthers($value['debit_account'])
                        ->getRegisterDetailOthersBanks($value['value'], $lote);
                }
            }
        }

        $data['lotes'] = count($fields);
        $this->lotes = $data['lotes'];

        $rowBank[] = $data;

        $text = $this->makeRowsithoutBorderLines($rowBank);

        return $text;
    }

    public function getRows()
    {
        return $this->rows;
    }

    private function getCollectionBanks($data)
    {
        $collection = new Collection;

        $itauBankCurrent = $collection->forEach($data, 'itau_bank_current');
        $itauBankSavings = $collection->forEach($data, 'itau_bank_savings');
        $othersBank = $collection->forEach($data, 'others_bank');

        $bankData = array($itauBankCurrent, $itauBankSavings, $othersBank);

        $banks = [];
        foreach ($bankData as $bank) {
            if ($bank !== null) {
                $banks[] = $bank;
            }
        }

        return $banks;
    }

    public function getData()
    {
        $data = [];
        foreach ($this->fields as $shipment => $value) {
            $bank = $value['spreadsheet_bank'];
            $accountType = $value['spreadsheet_account_type'];

            if ($bank == '341' && ($accountType == 'C' || $accountType == null)) {
                $data = $this->each($value, 'itau_bank_current', $shipment);
            }

            if ($bank == '341' && $accountType == 'P') {
                $data = $this->each($value, 'itau_bank_savings', $shipment);
            }

            if ($bank != '341') {
                $data = $this->each($value, 'others_bank', $shipment);
            }
        }

        return $data;
    }

    private function each(array $value, $transferType, $shipment)
    {
        $demandId = str_pad($this->fields[$shipment]['spreadsheet_demand_id'], 4, '0', STR_PAD_LEFT);

        $this->data[$transferType][$shipment]['document_number'] = env('CNAB240_DOCUMENT_NUMBER');
        $this->data[$transferType][$shipment]['agency'] = env('CNAB240_AGENCY');
        $this->data[$transferType][$shipment]['account'] = env('CNAB240_ACCOUNT');
        $this->data[$transferType][$shipment]['company_name'] = env('CNAB240_COMPANY_NAME');
        $this->data[$transferType][$shipment]['company_address'] = env('CNAB240_COMPANY_NAME');
        $this->data[$transferType][$shipment]['number_address'] = env('CNAB240_NUMBER_ADDRESS');
        $this->data[$transferType][$shipment]['city'] = env('CNAB240_CITY');
        $this->data[$transferType][$shipment]['cep'] = env('CNAB240_CEP');
        $this->data[$transferType][$shipment]['state'] = env('CNAB240_STATE');
        $this->data[$transferType][$shipment]['document_type'] = strlen(env('CNAB240_DOCUMENT_NUMBER')) === 11 ? '1' : '2';
        $this->data[$transferType][$shipment]['payee_name'] = $value['spreadsheet_name'];
        $this->data[$transferType][$shipment]['value'] = $value['spreadsheet_value'];
        $this->data[$transferType][$shipment]['note_fiscal'] = $demandId;
        $this->data[$transferType][$shipment]['bank_code'] = $value['spreadsheet_bank'];
        $this->data[$transferType][$shipment]['debit_agency'] = $value['spreadsheet_agency'];
        $this->data[$transferType][$shipment]['debit_account'] = $value['spreadsheet_account'];
        $this->data[$transferType][$shipment]['number_created_by_company'] = "REMESSA00000000{$demandId}";
        $this->data[$transferType][$shipment]['debit_document_number'] = $value['spreadsheet_document'];
        $this->data[$transferType][$shipment]['spreadsheet_account_type'] = $value['spreadsheet_account_type'];

        if ($value['spreadsheet_bank'] != '341') {
            unset($this->data[$transferType][$shipment]['spreadsheet_account_type']);
        }

        return $this->data;
    }

    public function makeFileText($text, $pathFilename)
    {
        $file = new FileText;
        $file->make($text, $pathFilename);
    }

    public function makeRowsithoutBorderLines(array $rows)
    {
        $key = $rows[0];
        $this->lotes = $key['lotes'];

        $text = '';

        if (array_key_exists('itau', $rows)) {
            $text .= $this->rowItau($rows);
        }

        if (array_key_exists('itau_savings', $rows)) {
            $text .= $this->rowItauSavings($rows);
        }

        if (array_key_exists('others', $rows)) {
            $text .= $this->rowItauOthers($rows);
        }

        return $text;
    }

    public function makeFinallyRows($rows)
    {
        $numberLines = $this->rows + $this->lote;
        $text = "{$this->itau->getHeaderFile()}";

        $text .= $rows;

        $text .= "\r\n{$this->itau->getTrailerFile($this->lotes, $numberLines)}\r\n";
        return $text;
    }

    private function rowItau(array $rows)
    {
        $key = $rows[0];
        $itauBanksKeyCurrent = $key['itau_key'];
        $lotesItau = count($key['itau_bank_lotes']);

        $text = '';
        $text .= "\r\n{$this->itau->getLoteFile('341', $itauBanksKeyCurrent, $key['type_account'])}\r\n";
        $text .= $this->eachRow($rows, 'itau');
        $text .= "{$this->itau->getTrailerLote($key['itau_bank_value']['value_total'], $itauBanksKeyCurrent, $lotesItau)}";
        return $text;
    }

    private function rowItauSavings(array $rows)
    {
        $key = $rows[0];
        $itauBanksKeySavings = $key['itau_key_savings'];
        $lotesItau = count($key['itau_bank_lotes_savings']);

        $text = '';
        $text .= "\r\n{$this->itau->getLoteFile('341', $itauBanksKeySavings, $key['type_account_savings'])}\r\n";
        $text .= $this->eachRow($rows, 'itau_savings');
        $text .= "{$this->itau->getTrailerLote($key['itau_bank_value_savings']['value_total'], $itauBanksKeySavings, $lotesItau)}";
        return $text;
    }

    private function rowItauOthers(array $rows)
    {
        $key = $rows[0];
        $othersBanksKey = $key['others_key'];
        $lotesOthers = count($key['others_bank_lotes']);

        $text = '';
        $text .= "\r\n{$this->itau->getLoteFile('000', $othersBanksKey)}\r\n";
        $text .= $this->eachRow($rows, 'others');
        $text .= "{$this->itau->getTrailerLote($key['others_bank_value']['value_total'], $othersBanksKey, $lotesOthers)}";
        return $text;
    }

    private function eachRow(array $rows, $name)
    {
        $text = '';
        foreach ($rows[$name] as $row) {
            $text .= $row . "\r\n";
        }

        return $text;
    }
}
