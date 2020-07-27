<?php

namespace Tests\Feature;

use App\HackShipment\Cnab240\Shipments\Bank\Layout\HeaderFileTypeZeroItau as Header;
use App\HackShipment\Cnab240\Shipments\Bank\Layout\LoteFile;
use App\HackShipment\Cnab240\Shipments\Bank\Layout\RegisterDetailFile;
use App\HackShipment\Cnab240\Shipments\Bank\Layout\TrailerFile;
use App\HackShipment\Cnab240\Shipments\Bank\Layout\TrailerLote;
use Tests\TestCase;

class HackShipmentGenerationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLineHeaderFile()
    {
        $header = new Header;

        $date = new \DateTime();
        $hour = $date->format('his');
        $dmY = $date->format('dmY');

        $headerFile = $header->addBankCode('341')->addBankLote('0000')
            ->addTypeRegister('0')
            ->addWhiteSpace('6')
            ->addLayoutFile('081')
            ->addDocumentType('2')
            ->addDocumentNumber('30740059000187') //'30740059000187'
            ->addWhiteSpace('20')
            ->addAgency('00078') //'00078'
            ->addWhiteSpace('1')
            ->addAccount('000000094200') //'000000094200'
            ->addWhiteSpace('1')
            ->addDAC('0')
            ->addCompanyName('Relacionamais Marketing LTDA') //'Relacionamais Marketing LTDA'
            ->addBankName('Banco Itaú SA') // 'Banco Itaú SA'
            ->addWhiteSpace('10')
            ->addFileCode('1')
            ->addDateGeneration('00-00-0000')
            ->addHourGeneration()
            ->addZeros('9')
            ->addDensityUnity('01600')
            ->addWhiteSpace('69')
            ->get();

        $headerFileText = implode('', $headerFile);
        $expectedText = "34100000      081230740059000187                    00078 000000094200 0RELACIONAMAIS MARKETING LTDA  BANCO ITAU SA                           1{$dmY}{$hour}00000000001600                                                                     ";

        $this->assertEquals($expectedText, $headerFileText);
    }

    public function testLineLoteFile()
    {
        $key = 1;
        $bank = '341';
        $paymentType = $bank != '341' ? '041' : '001';

        $lote = new LoteFile;
        $loteFile = $lote->addBankCode('341')->addCodeLote($key)
            ->addRegisterHeaderLote('1')
            ->addOperationType('C')
            ->addPaymentMethod('2')
            ->addPaymentType($paymentType)
            ->addLayoutLote('040')
            ->addWhiteSpace('1')
            ->addDocumentType('2') // '2'
            ->addDocumentNumber('30740059000187') // '30740059000187'
            ->addWhiteSpace('4')
            ->addWhiteSpace('16')
            ->addAgency('00078') // '00078'
            ->addWhiteSpace('1')
            ->addAccount('000000094200') // '000000094200'
            ->addWhiteSpace('1')
            ->addDAC('0')
            ->addCompanyName('Relacionamais Marketing LTDA') // 'Relacionamais Marketing LTDA'
            ->addWhiteSpace(30)
            ->addWhiteSpace(10)
            ->addCompanyAddress('av guilherme') // 'av guilherme'
            ->addNumberAddress('01515') //'01515'
            ->addComplementAddress('')
            ->addCity('São Paulo') // 'São Paulo'
            ->addCEP('02053003') // '02053003'
            ->addState('SP') // 'SP'
            ->addWhiteSpace('8')
            ->addOccurrence('')
            ->get();

            $lineLoteFile = implode('', $loteFile);
            $expectedText = '34100011C2001040 230740059000187                    00078 000000094200 0RELACIONAMAIS MARKETING LTDA                                          AV GUILHERME                  01515               SAO PAULO           02053003SP                  ';

            $this->assertEquals($expectedText, $lineLoteFile);
    }

    public function testLineRegisterDetail()
    {
        $register = new RegisterDetailFile;
        $value = (int) 10000;
        $key = 1;

        $date = new \DateTime();
        $dmY = $date->format('dmY');

        $registerDetail = $register->addBankCode('341')->addCodeLote($key)
            ->addRegisterType('3')
            ->addRegisterNumber(1)
            ->addSegment('A')
            ->addMovementType()
            ->addChamber()
            ->addBankCode('341')
            ->addDebitAgency('07146') // '07146'
            ->addWhiteSpace('1')
            ->addDebitAccount('281394') // '281394'
            ->addWhiteSpace('1')
            ->addDAC('281394') // pega o último número de conta
            ->addPayeeName('PITTER DOS SANTOS') // 'PITTER DOS SANTOS'
            ->addNumberCreatedByCompany('REMESSA0000000000002') // 'REMESSA0000000000001'
            ->addDateGeneration()
            ->addCurrency('rea')
            ->addSBPCode('00000000')
            ->addZeros('7')
            ->addPaymentValue($value)
            ->addWhiteSpace('18')
            ->addWhiteSpace('2')
            ->addZeros('8') // date 00-00-0000
            ->addPaymentEffectiveValue('00000')
            ->addNoteFiscal('519')
            ->addZeros('6')
            ->addDebitDocumentNumber('00031051521807') // '00031051521807'
            ->addWhiteSpace('2')
            ->addWhiteSpace('5')
            ->addWhiteSpace('5')
            ->addZeros('1')
            ->addWhiteSpace('10')
            ->get();

        $lineDetailLine = implode('', $registerDetail);
        $expectedText = "3410001300001A00000034107146 000000028139 4PITTER DOS SANTOS             REMESSA0000000000002{$dmY}REA000000000000000000000000010000                    00000000000000000000000NF 519              00000000031051521807            0          ";

        $this->assertEquals($expectedText, $lineDetailLine);
    }

    public function testLineRegisterDetailOthersBanks()
    {
        $register = new RegisterDetailFile;
        $value = (int) 39000;
        $key = 1;

        $date = new \DateTime();
        $dmY = $date->format('dmY');

        $registerDetailOthersBanks = $register->addBankCode('341')->addCodeLote($key)
            ->addRegisterType('3')
            ->addRegisterNumber(1)
            ->addSegment('A')
            ->addMovementType()
            ->addChamber()
            ->addBankCode('756')
            ->addDebitAgency('1480') // '09279'
            ->addWhiteSpace('1')
            ->addDebitAccount('286341') // '000000010894'
            ->addWhiteSpace('1')
            ->addDAC('164841')
            ->addPayeeName('EDUARDO DE SOUSA RIOS') // 'EDUARDO DE SOUSA RIOS'
            ->addNumberCreatedByCompany('REMESSA0000000000001') // 'REMESSA0000000000001'
            ->addDateGeneration()
            ->addCurrency('rea')
            ->addSBPCode('00000000')
            ->addZeros('7')
            ->addPaymentValue($value)
            ->addWhiteSpace('15')
            ->addWhiteSpace('5')
            ->addZeros('8') // date 00-00-0000
            ->addPaymentEffectiveValue('00000')
            ->addNoteFiscal('519')
            ->addZeros('6')
            ->addDebitDocumentNumber('35774027848') // '35774027848'
            ->addWhiteSpace('2')
            ->addConstantNumber('00005')
            ->addWhiteSpace('0')
            ->addWhiteSpace('5')
            ->addZeros('1')
            ->addWhiteSpace('10')
            ->get();

        $lineRegisterDetail = implode('', $registerDetailOthersBanks);
        $expectedText = "3410001300001A00000075601480 000000028634 1EDUARDO DE SOUSA RIOS         REMESSA000000000000105022020REA000000000000000000000000039000                    00000000000000000000000NF 519              00000000035774027848  00005     0          ";

        $this->assertEquals($expectedText, $lineRegisterDetail);
    }

    public function testLineTrailerLote()
    {
        $value = 1939000;
        $registers = 5;
        $trailer = new TrailerLote;

        $trailerLote = $trailer->addBankCode('341')->addCodeLote(1)
            ->addTypeRegister('5')
            ->addWhiteSpace('9')
            ->addQuantityRegister($registers + 2)
            ->addPaymentValue($value, 18)
            ->addZeros('18')
            ->addWhiteSpace('171')
            ->addWhiteSpace('10')
            ->get();

        $lineTrailerLote = implode('', $trailerLote);
        $expectedText = "34100015         000007000000000001939000000000000000000000                                                                                                                                                                                     ";

        $this->assertEquals($expectedText, $lineTrailerLote);
    }

    public function testLineTraileFile()
    {
        $trailerFile = new TrailerFile;
        $quantityLotes = 1;

        $trailerFile = $trailerFile->addBankCode('341')->addCodeLote('9999')
            ->addTypeRegister('9')
            ->addWhiteSpace('9')
            ->addQuantityLote($quantityLotes) //
            ->addQuantityRegister(9)
            ->addWhiteSpace('211')
            ->get();

        $lineTrailerLote = implode('', $trailerFile);
        $expectedText = '34199999         000001000009                                                                                                                                                                                                                   ';

        $this->assertEquals($expectedText, $lineTrailerLote);
    }
}
