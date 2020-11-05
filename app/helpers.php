<?php

use App\Award;
use App\Bank;
use App\BaseAcessoCardsCompleto;
use App\Helpers\Number;
use App\Repositories\BankRepository;
use App\Repositories\BaseAcessoCardsCompletoRepository;
use App\Services\BaseAcessoCardsCompletoService;
use Illuminate\Support\Facades\DB;

    // SUBSTITUIR HELPERS POR FACADES

    function toInt($value) {
        $number = new Number;
        $value = $number->setInt($value);
        $value = ($value / 100);
        return (int) $value;
    }

    function toIntLiteral($value) {
        $number = new Number;
        $value = $number->setInt($value);
        return (int) $value;
    }

    function toReal($value) {
        $number = new Number;
        $value = $number->setReal($value);
        $value = ($value / 100);
        return (float) $value;
    }

    function toRealLiteral($value) {
        $number = new Number;
        $value = $number->setReal($value);
        return (float) $value;
    }

    function toMoney($value) {
        $number = new Number;
        $value = $number->setReal($value);
        $value = ($value / 100);
        return $value;
    }

    function toMoneyLiteral($value) {
        $number = new Number;
        $value = $number->setReal($value);
        return $value;
    }

    function takeMoneyFormat($value) {
        $number = new Number;
        $value = $number->takeMoneyFormat($value);
        return $value;
    }

    function toPercent($value) {
        $number = new Number;
        $value = $number->percent($value);
        return (float) $value;
    }

    function getShipmentValueById($id)
    {
        $shipmentValue = (float) \App\Spreadsheet::select(DB::raw('sum(spreadsheets.spreadsheet_value) as shipment_value'))
            ->leftJoin('demands', 'spreadsheets.spreadsheet_demand_id', '=', 'demands.id')
            ->leftJoin('awards', 'spreadsheets.spreadsheet_award_id', '=', 'awards.id')
            ->leftJoin('shipments_api', 'spreadsheets.spreadsheet_award_id', '=', 'shipments_api.shipment_award_id')
            ->where('spreadsheets.spreadsheet_demand_id', $id)
            ->where('shipments_api.shipment_generated', 1)
            ->whereNull('awards.awarded_shipment_cancelled')
            ->whereNull('spreadsheets.spreadsheet_chargeback')
            ->first()
            ->shipment_value ?? 0;

        $acessoCardValue = \App\AcessoCard::select(DB::raw('sum(acesso_cards.acesso_card_value) as card_value'))
            ->leftJoin('demands', 'acesso_cards.acesso_card_demand_id', '=', 'demands.id')
            ->leftJoin('awards', 'acesso_cards.acesso_card_award_id', '=', 'awards.id')
            ->leftJoin('shipments_api', 'acesso_cards.acesso_card_award_id', '=', 'shipments_api.shipment_award_id')
            ->where('demands.id', $id)
            ->where('shipments_api.shipment_generated', 1)
            ->whereNull('awards.awarded_shipment_cancelled')
            ->whereNull('acesso_cards.acesso_card_chargeback')
            ->first()
            ->card_value;

        $awards = \App\NoteReceipt::select(DB::raw('sum(note_receipt_award_real_value) as award_real_value'))
            ->where('note_receipt_demand_id', $id)
            ->first()
            ->award_real_value ?? 0;

        $awardManual = \App\Award::select(DB::raw('sum(awards.awarded_value) as award_manual'))
            ->where('awarded_type', 3)
            ->where('awarded_status', 1)
            ->where('awarded_demand_id', $id)
            ->whereNull('awarded_shipment_cancelled')
            ->first()
            ->award_manual ?? 0;

        return $awards - $shipmentValue - $awardManual - $acessoCardValue;
    }

    function getSpreadsheetValue($id) {
        $shipmentValue = (float) \App\Spreadsheet::select(DB::raw('sum(spreadsheets.spreadsheet_value) as shipment_value'))
            ->leftJoin('demands', 'spreadsheets.spreadsheet_demand_id', '=', 'demands.id')
            ->leftJoin('awards', 'spreadsheets.spreadsheet_award_id', '=', 'awards.id')
            ->where('spreadsheets.spreadsheet_demand_id', $id)
            ->where('awards.awarded_shipment_generated', 1)
            ->whereNull('awards.awarded_shipment_cancelled')
            ->whereNull('spreadsheets.spreadsheet_chargeback')
            ->first()
            ->shipment_value ?? 0;

        return $shipmentValue;
    }

    function getBankById($bankId) {
        $bank = new Bank();
        $repository = (new BankRepository($bank))->getBankById($bankId);

        $transferBank = "{$repository->bank_name} | AG {$repository->bank_agency} | Conta {$repository->bank_account}";
        return $transferBank;
    }

    function getShipmentName() {
        $createdAt = Award::select('created_at')->first()
            ->count();
        $shipmentNumber = $createdAt;
        $shipmentNumber = str_pad($shipmentNumber, 2, 0, STR_PAD_LEFT);

        $date = new \DateTime;
        $shipmentName = "R{$date->format('dm')}{$shipmentNumber}";
        return $shipmentName;
    }

    function getPathFileNameShipment() {
        $path = storage_path('app/public/shipments');
        $filename = getShipmentName();
        $pathFileName = "{$path}/{$filename}.txt";

        return $pathFileName;
    }

    function getAcessoCardCompletoNotGeneratedView($id) {
        $baseAcessoCardsCompletoService = new BaseAcessoCardsCompletoService(new BaseAcessoCardsCompletoRepository(new BaseAcessoCardsCompleto));
        return $baseAcessoCardsCompletoService->getAcessoCardCompletoNotGenerated($id);
    }
