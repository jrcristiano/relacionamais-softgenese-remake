<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau;

use App\Libraries\SoftGeneseCnab\Cnab240\Banks\Itau\Layout\HeaderFileTypeZeroRow;
use App\Libraries\SoftGeneseCnab\Cnab240\Banks\Itau\Layout\LoteFileRow;
use App\Libraries\SoftGeneseCnab\Cnab240\Banks\Itau\Layout\RegisterDetailRow;
use App\Libraries\SoftGeneseCnab\Cnab240\Banks\Itau\Layout\TrailerFileRow;
use App\Libraries\SoftGeneseCnab\Cnab240\Banks\Itau\Layout\TrailerLoteRow;
use App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Shipment;

class Itau extends Shipment
{
    protected $data = [];

    protected $lines;

    protected $linesItauBank = 0;
    protected $linesOthersBanks = 0;
    protected $linesItauBankSavings = 0;

    protected $linesLoteFile = 0;
    protected $registerDetail = 0;
    protected $registerDetailSavings = 0;
    protected $registerDetailOthers = 0;

    protected $trailerLote = 0;

    protected $keyItauCurrent = 0;
    protected $keyItauSavings = 0;

    public function getHeaderFile()
    {
        $this->lines++;

        $header = new HeaderFileTypeZeroRow;
        $headerFile = $header->addFixedBankCode()->addZeros(4)
            ->addTypeRegister('0')
            ->addWhiteSpace('6')
            ->addLayoutFile('081')
            ->addDocumentType('2')
            ->addFixedDocumentNumber() //'30740059000187'
            ->addWhiteSpace('20')
            ->addFixedAgency() //'00078'
            ->addWhiteSpace('1')
            ->addFixedAccount() //'000000094200'
            ->addWhiteSpace('1')
            ->addDAC('0')
            ->addFixedCompanyName() //'Relacionamais Marketing LTDA'
            ->addFixedBankName() // 'Banco Itaú SA'
            ->addWhiteSpace('10')
            ->addFileCode('1')
            ->addDateGeneration('00-00-0000')
            ->addHourGeneration()
            ->addZeros('9')
            ->addDensityUnity('01600')
            ->addWhiteSpace('69')
            ->get();

        return implode('', $headerFile);
    }

    public function getLoteFile($bank, $key, array $typeAccount = [])
    {
        $this->lines++;
        $this->linesLoteFile++;

        $paymentType = '041';

        if (in_array('C', $typeAccount)) {
            $paymentType = '001';
        }

        if (in_array('P', $typeAccount)) {
            $paymentType = '005';
        }

        $lote = new LoteFileRow;
        $loteFile = $lote->addFixedBankCode()->addCodeLote($key)
            ->addRegisterHeaderLote('1')
            ->addOperationType('C')
            ->addPaymentMethod('2')
            ->addPaymentType($paymentType)
            ->addLayoutLote('040')
            ->addWhiteSpace('1')
            ->addDocumentType($this->data['document_type']) // '2'
            ->addFixedDocumentNumber() // '30740059000187'
            ->addWhiteSpace('4')
            ->addWhiteSpace('16')
            ->addFixedAgency() // '00078'
            ->addWhiteSpace('1')
            ->addFixedAccount() // '000000094200'
            ->addWhiteSpace('1')
            ->addDAC('0')
            ->addFixedCompanyName() // 'Relacionamais Marketing LTDA'
            ->addWhiteSpace(30)
            ->addWhiteSpace(10)
            ->addFixedCompanyAddress() // 'av guilherme'
            ->addFixedNumberAddress() //'01515'
            ->addComplementAddress('')
            ->addFixedCity() // 'São Paulo'
            ->addFixedCEP() // '02053003'
            ->addFixedState() // 'SP'
            ->addWhiteSpace('8')
            ->addOccurrence('')
            ->get();

            return implode('', $loteFile);
    }

    public function getRegisterDetail($value, $key)
    {
        $register = new RegisterDetailRow;
        $value = (float) $value;

        $this->registerDetail++;
        $this->lines++;
        $this->linesItauBank++;

        $debitAccount = toIntLiteral($this->data['debit_account']);
        $debitAccount = (string) $debitAccount;
        $registerDetail = $register->addFixedBankCode()->addCodeLote($key)
            ->addRegisterType('3')
            ->addRegisterNumber($this->registerDetail)
            ->addSegment('A')
            ->addMovementType()
            ->addChamber()
            ->addFixedBankCode($this->data['bank_code'])
            ->addZeros(1)
            ->addDebitAgency($this->data['debit_agency']) // '09279'
            ->addWhiteSpace(1)
            ->addZeros(6)
            ->addDebitAccount($debitAccount) // '10894'
            ->addWhiteSpace('1')
            ->addDAC($this->data['debit_account'])
            ->addPayeeName($this->data['payee_name']) // 'CARLOS WEDER ARAUJO'
            ->addNumberCreatedByCompany($this->data['number_created_by_company']) // 'REMESSA0000000000001'
            ->addDateGeneration()
            ->addCurrency('rea')
            ->addZeros('8')
            ->addZeros('7')
            ->addPaymentValue($this->data['value'])
            ->addWhiteSpace('20')
            ->addZeros('8') // date 00-00-0000
            ->addZeros(15)
            ->addNoteFiscal($this->data['note_fiscal'])
            ->addWhiteSpace(2)
            ->addZeros('6')
            ->addDebitDocumentNumber($this->data['debit_document_number']) // '00023020014867'
            ->addWhiteSpace('2')
            ->addWhiteSpace('5')
            ->addWhiteSpace('5')
            ->addZeros('1')
            ->addWhiteSpace('10')
            ->get();

        return implode('', $registerDetail);
    }

    public function getRegisterDetailLoteSavings($value, $key)
    {
        $register = new RegisterDetailRow;
        $value = (float) $value;

        $this->lines++;
        $this->linesItauBankSavings++;
        $this->registerDetailSavings++;

        $debitAccount = toIntLiteral($this->data['debit_account']);
        $debitAccount = (string) $debitAccount;
        $registerDetailSavings = $register->addFixedBankCode()->addCodeLote($key)
            ->addRegisterType('3')
            ->addRegisterNumber($this->registerDetailSavings)
            ->addSegment('A')
            ->addMovementType()
            ->addChamber()
            ->addFixedBankCode($this->data['bank_code'])
            ->addZeros(1)
            ->addDebitAgency($this->data['debit_agency']) // '09279'
            ->addWhiteSpace(1)
            ->addZeros(6)
            ->addDebitAccount($debitAccount) // '10894'
            ->addWhiteSpace('1')
            ->addDAC($this->data['debit_account'])
            ->addPayeeName($this->data['payee_name']) // 'CARLOS WEDER ARAUJO'
            ->addNumberCreatedByCompany($this->data['number_created_by_company']) // 'REMESSA0000000000001'
            ->addDateGeneration()
            ->addCurrency('rea')
            ->addZeros('8')
            ->addZeros('7')
            ->addPaymentValue($this->data['value'])
            ->addWhiteSpace('20')
            ->addZeros('8') // date 00-00-0000
            ->addZeros(15)
            ->addNoteFiscal($this->data['note_fiscal'])
            ->addWhiteSpace(2)
            ->addZeros('6')
            ->addDebitDocumentNumber($this->data['debit_document_number']) // '00023020014867'
            ->addWhiteSpace('2')
            ->addWhiteSpace('5')
            ->addWhiteSpace('5')
            ->addZeros('1')
            ->addWhiteSpace('10')
            ->get();

        return implode('', $registerDetailSavings);
    }

    public function getRegisterDetailOthersBanks($value, $key)
    {
        $value = (float) $value;
        $register = new RegisterDetailRow;

        $this->lines++;
        $this->registerDetailOthers++;

        $debitAccountOthers = toIntLiteral($this->data['debit_account_others']);
        $debitAccountOthers = (string) $debitAccountOthers;
        $registerDetailOthersBanks = $register->addFixedBankCode()->addCodeLote($key)
            ->addRegisterType('3')
            ->addRegisterNumber($this->registerDetailOthers)
            ->addSegment('A')
            ->addMovementType()
            ->addChamber()
            ->addFixedBankCode($this->data['bank_code'])
            ->addZeros(1)
            ->addDebitAgency($this->data['debit_agency']) // '09279'
            ->addWhiteSpace(1)
            ->addDebitAccountOthersBanks($debitAccountOthers)
            ->addWhiteSpace('1')
            ->addDAC($debitAccountOthers)
            ->addPayeeName($this->data['payee_name']) // 'CARLOS WEDER ARAUJO'
            ->addNumberCreatedByCompany($this->data['number_created_by_company']) // 'REMESSA0000000000001'
            ->addDateGeneration()
            ->addCurrency('rea')
            ->addZeros('8')
            ->addZeros('7')
            ->addPaymentValue($this->data['value'])
            ->addWhiteSpace('20')
            ->addZeros('8') // date 00-00-0000
            ->addZeros(15)
            ->addNoteFiscal($this->data['note_fiscal'])
            ->addWhiteSpace(2)
            ->addZeros('6')
            ->addDebitDocumentNumber($this->data['debit_document_number']) // '00023020014867'
            ->addWhiteSpace('2')
            ->addWhiteSpace('5')
            ->addWhiteSpace('5')
            ->addZeros('1')
            ->addWhiteSpace('10')
            ->get();

            return implode('', $registerDetailOthersBanks);
    }

    public function getTrailerLote(array $value, $key, $registers)
    {
        $value = array_sum($value);
        $trailer = new TrailerLoteRow;
        $this->lines++;
        $this->trailerLote++;

        $trailerLote = $trailer->addFixedBankCode()->addCodeLote($key)
            ->addTypeRegister('5')
            ->addWhiteSpace('9')
            ->addQuantityRegister($registers + 2)
            ->addPaymentValue($value, 18)
            ->addZeros('18')
            ->addWhiteSpace('171')
            ->addWhiteSpace('10')
            ->get();

        return implode('', $trailerLote);
    }

    public function getTrailerFile($quantityLotes, $numberLines)
    {
        $this->lines++;
        $numberLines = $numberLines + $quantityLotes;
        $trailerFile = new TrailerFileRow;

        $trailerFile = $trailerFile->addFixedBankCode()->addCodeLote('9999')
            ->addTypeRegister('9')
            ->addWhiteSpace('9')
            ->addQuantityLote($quantityLotes) //
            ->addQuantityRegister($numberLines + 2)
            ->addWhiteSpace('211')
            ->get();

        return implode('', $trailerFile);
    }

    public function getLines()
    {
        return $this->lines;
    }
}
