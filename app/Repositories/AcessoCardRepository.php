<?php

namespace App\Repositories;

use App\AcessoCard;
use Illuminate\Http\Request;
use yidas\phpSpreadsheet\Helper;

class AcessoCardRepository extends Repository
{
    protected $repository;

    public function __construct(AcessoCard $acessoCard)
    {
        $this->repository = $acessoCard;
    }

    public function findByDocument($document)
    {
        return $this->repository->where('acesso_card_document', $document)
            ->first();
    }

    public function getHistoriesByDocument($document)
    {
        return $this->repository->where('acesso_card_document', $document)
            ->get();
    }

    public function getAwardedsAwaitingPayment($id)
    {
        return $this->repository->select([
            'acesso_cards.acesso_card_document',
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_award_id',
            'base_acesso_cards_completo.base_acesso_card_proxy'
        ])
        ->join('base_acesso_cards_completo', 'acesso_cards.acesso_card_document', '=', 'base_acesso_cards_completo.base_acesso_card_cpf')
        ->where('acesso_card_award_id', $id)
        ->get();
    }

    public function getAwardedsAwaitingPaymentNotGenerated($id)
    {
        return $this->repository->select([
            'acesso_cards.acesso_card_document',
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_award_id',
            'base_acesso_cards_completo.base_acesso_card_proxy'
        ])
        ->leftJoin('base_acesso_cards_completo', 'acesso_cards.acesso_card_document', '=', 'base_acesso_cards_completo.base_acesso_card_cpf')
        ->where('acesso_card_award_id', $id)
        ->whereNull('base_acesso_card_generated')
        ->get();
    }

    public function getAcessoCardByDocument($document)
    {
        return $this->repository->where('acesso_card_document', $document)
            ->get();
    }

    public function findByAwardId($id)
    {
        return $this->repository->where('acesso_card_award_id', $id)
            ->get();
    }

    public function findByCard($card)
    {
        return $this->repository->where('acesso_card_number', $card)
            ->first();
    }

    public function updateByDocument(array $data, $document)
    {
        return $this->repository->where('acesso_card_document', $document)
            ->update($data);
    }

    public function saveByParam(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->update($data);
    }

    public function storeCard($fileName, $demandId, $awardDemandId, array $params = [])
    {
        $excel = Helper::newSpreadsheet($fileName)->getRows();

        $data = [];
        foreach ($excel as $key => $row) {
            if ($row[0] != null) {
                $key += 1;

                $data['acesso_card_document'] = $row[0];
                $data['acesso_card_name'] = $row[1];
                $data['acesso_card_value'] = $row[2];
                $data['acesso_card_number'] = $params['acesso_card_number'];
                $data['acesso_card_spreadsheet_line'] = $key;
                $data['acesso_card_demand_id'] = $demandId;
                $data['acesso_card_award_id'] = $awardDemandId;

                return $this->repository->create($data);
            }
        }

        return false;
    }

    public function getAcessoCardsWhereAwarded($id, $perPage = 200)
    {
        return $this->repository->select([
            'acesso_cards.*',
            'base_acesso_cards_completo.*',
            'shipments_api.shipment_generated',
        ])
        ->leftJoin('shipments_api', 'acesso_cards.acesso_card_award_id', '=', 'shipments_api.shipment_award_id')
        ->leftJoin('base_acesso_cards_completo', 'acesso_cards.acesso_card_document', '=', 'base_acesso_cards_completo.base_acesso_card_cpf')
        ->where('acesso_cards.acesso_card_award_id', $id)
        ->paginate($perPage);
    }

    public function getAwardedsByAllAwards(Request $request, $perPage = 500)
    {
        $query = $this->repository->select([
            'awards.awarded_status',
            'base_acesso_cards_completo.*',
            'acesso_cards.*',
        ])
        ->leftJoin('base_acesso_cards_completo', 'acesso_cards.acesso_card_document', '=', 'base_acesso_cards_completo.base_acesso_card_cpf')
        ->leftJoin('awards', 'acesso_cards.acesso_card_award_id', '=', 'awards.id')
        ->groupBy('base_acesso_cards_completo.base_acesso_card_proxy');

        if ($request->name) {
            $query->where('base_acesso_card_name', $request->name);
        }

        if ($request->cpf) {
            $query->where('base_acesso_card_cpf', $request->cpf);
        }

        if ($request->proxy) {
            $query->where('base_acesso_card_proxy', $request->proxy);
        }

        return $query->paginate($perPage);
    }
}
