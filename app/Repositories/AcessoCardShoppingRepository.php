<?php

namespace App\Repositories;

use App\AcessoCardShopping;
use Illuminate\Http\Request;
use yidas\phpSpreadsheet\Helper;

class AcessoCardShoppingRepository extends Repository
{
    protected $repository;

    public function __construct(AcessoCardShopping $acessoCardShopping)
    {
        $this->repository = $acessoCardShopping;
    }

    public function findByDocument($document)
    {
        return $this->repository->where('acesso_card_shopping_document', $document)
            ->first();
    }

    public function findByProxy($proxy)
    {
        return $this->repository->where('acesso_card_shopping_proxy', $proxy)
            ->first();
    }

    public function getHistoriesByDocument($document)
    {
        return $this->repository->where('acesso_card_shopping_document', $document)
            ->get();
    }

    public function getAwardedsAwaitingPayment($id)
    {
        return $this->repository->select([
            'acesso_card_shoppings.acesso_card_shopping_document',
            'acesso_card_shoppings.acesso_card_shopping_name',
            'acesso_card_shoppings.acesso_card_shopping_award_id',
            'base_acesso_cards_compras.base_acesso_card_proxy'
        ])
        ->join('base_acesso_cards_compras', 'acesso_card_shoppings.acesso_card_shopping_document', '=', 'base_acesso_cards_compras.base_acesso_card_cpf')
        ->where('acesso_card_shopping_award_id', $id)
        ->get();
    }

    public function getAwardedsAwaitingPaymentNotGenerated($id)
    {
        return $this->repository->select([
            'acesso_card_shoppings.acesso_card_shopping_document',
            'acesso_card_shoppings.acesso_card_shopping_name',
            'acesso_card_shoppings.acesso_card_shopping_award_id',
            'base_acesso_cards_compras.base_acesso_card_proxy'
        ])
        ->leftJoin('base_acesso_cards_compras', 'acesso_card_shoppings.acesso_card_shopping_document', '=', 'base_acesso_cards_compras.base_acesso_card_cpf')
        ->where('acesso_card_award_id', $id)
        ->whereNull('base_acesso_card_generated')
        ->get();
    }

    public function getAcessoCardByDocument($document)
    {
        return $this->repository->where('acesso_card_shopping_document', $document)
            ->get();
    }

    public function findByAwardId($id)
    {
        return $this->repository->where('acesso_card_shopping_award_id', $id)
            ->get();
    }

    public function findCardCancelledByAwardId($id)
    {
        return $this->repository->where('acesso_card_shopping_award_id', $id)
            ->whereNull('acesso_card_shopping_number')
            ->whereNull('acesso_card_shopping_proxy')
            ->get();
    }

    public function findByCard($card)
    {
        return $this->repository->where('acesso_card_shopping_number', $card)
            ->first();
    }

    public function updateByDocument(array $data, $document)
    {
        return $this->repository->where('acesso_card_shopping_document', $document)
            ->update($data);
    }

    public function saveByParam(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->update($data);
    }

    public function saveByParamWhereProxyNull(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->whereNull('acesso_card_shopping_proxy')
            ->update($data);
    }

    public function storeCard($fileName, $demandId, $awardDemandId, array $params = [])
    {
        $excel = Helper::newSpreadsheet($fileName)->getRows();

        $data = [];
        foreach ($excel as $key => $row) {
            if ($row[0] != null) {
                $key += 1;

                $data['acesso_card_shopping_document'] = $row[0];
                $data['acesso_card_shopping_name'] = $row[1];
                $data['acesso_card_shopping_value'] = $row[2];
                $data['acesso_card_shopping_number'] = $params['acesso_card_number'];
                $data['acesso_card_shopping_spreadsheet_line'] = $key;
                $data['acesso_card_shopping_demand_id'] = $demandId;
                $data['acesso_card_shopping_award_id'] = $awardDemandId;

                return $this->repository->create($data);
            }
        }

        return false;
    }

    public function getAcessoCardsWhereAwarded($id, $perPage = 200)
    {
        return $this->repository->select([
            'awards.awarded_status',
            'awards.award_already_parted',
            'acesso_card_shoppings.*',
            'acesso_card_shoppings.id as acesso_card_id',
            'base_acesso_cards_compras.*',
            'shipments_api.shipment_generated',
        ])
        ->leftJoin('awards', 'acesso_card_shoppings.acesso_card_award_id', '=', 'awards.id')
        ->leftJoin('shipments_api', 'acesso_card_shoppings.acesso_card_award_id', '=', 'shipments_api.shipment_award_id')
        ->leftJoin('base_acesso_cards_compras', 'acesso_card_shoppings.acesso_card_shopping_proxy', '=', 'base_acesso_cards_compras.base_acesso_card_proxy')
        ->where('acesso_card_shoppings.acesso_card_shopping_award_id', $id)
        ->whereNull('acesso_card_chargeback')
        ->paginate($perPage);
    }

    public function getAcessoCardsWhereAwardedWithChargeback($id, $perPage = 200)
    {
        return $this->repository->select([
            'awards.awarded_status',
            'awards.awarded_type_card',
            'awards.award_already_parted',
            'awards.awarded_shipment_cancelled',
            'acesso_card_shoppings.*',
            'acesso_card_shoppings.id as acesso_card_id',
            'base_acesso_cards_compras.*',
            'shipments_api.shipment_generated',
        ])
        ->leftJoin('awards', 'acesso_card_shoppings.acesso_card_shopping_award_id', '=', 'awards.id')
        ->leftJoin('shipments_api', 'acesso_card_shoppings.acesso_card_shopping_award_id', '=', 'shipments_api.shipment_award_id')
        ->leftJoin('base_acesso_cards_compras', 'acesso_card_shoppings.acesso_card_shopping_proxy', '=', 'base_acesso_cards_compras.base_acesso_card_proxy')
        ->where('acesso_card_shoppings.acesso_card_shopping_award_id', $id)
        ->paginate($perPage);
    }

    public function getAwardedsByAllAwards(Request $request, $perPage = 500)
    {
        $query = $this->repository->select([
            'awards.awarded_status',
            'base_acesso_cards_compras.*',
            'acesso_card_shoppings.*',
            'acesso_card_shoppings.id as acesso_card_id',
        ])
        ->leftJoin('base_acesso_cards_compras', 'acesso_card_shoppings.acesso_card_shopping_proxy', '=', 'base_acesso_cards_compras.base_acesso_card_proxy')
        ->leftJoin('awards', 'acesso_card_shoppings.acesso_card_shopping_award_id', '=', 'awards.id')
        ->orderBy('acesso_card_shoppings.id', 'desc')
        ->groupBy('base_acesso_cards_compras.base_acesso_card_proxy');

        if ($request->search) {
            $query->where(function ($query) use ($request) {
                $query->orWhere('acesso_card_shoppings.acesso_card_shopping_name', 'like', "%{$request->search}%")
                ->orWhere('acesso_card_shoppings.acesso_card_shopping_number', 'like', "%{$request->search}%")
                ->orWhere('acesso_card_shoppings.acesso_card_shopping_document', 'like', "%{$request->search}%")
                ->orWhere('acesso_card_shoppings.acesso_card_shopping_proxy', 'like', "%{$request->search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findInfoAcessoCard($document, $perPage = 250)
    {
        return $this->repository->select([
            'acesso_card_shoppings.acesso_card_shopping_name',
            'acesso_card_shoppings.acesso_card_shopping_proxy',
            'acesso_card_shoppings.acesso_card_shopping_value',
            'acesso_card_shoppings.acesso_card_shopping_chargeback',
            'acesso_card_shoppings.acesso_card_shopping_document',
            'base_acesso_cards_compras.base_acesso_card_number',
            'base_acesso_cards_compras.base_acesso_card_proxy',
            'acesso_card_shoppings.created_at',
            'demands.demand_client_name',
            'demands.id as demand_id',
            'awards.id as award_id',
            'awards.awarded_status',
            'shipments_api.shipment_generated',
        ])
        ->leftJoin('awards', 'acesso_card_shoppings.acesso_card_shopping_award_id', '=', 'awards.id')
        ->leftJoin('demands', 'awards.awarded_demand_id', '=', 'demands.id')
        ->leftJoin('shipments_api', 'awards.id', '=', 'shipments_api.shipment_award_id')
        ->leftJoin('base_acesso_cards_compras', 'acesso_card_shoppings.acesso_card_shopping_proxy', '=', 'base_acesso_cards_compras.base_acesso_card_proxy')
        ->where('acesso_card_shoppings.acesso_card_shopping_document', $document)
        ->orderBy('acesso_card_shoppings.created_at', 'desc')
        ->paginate($perPage);
    }

    public function chargebackAllNewsAcessoCard($id)
    {
        return $this->repository->where('acesso_card_shopping_award_id', $id)
            ->whereNull('acesso_card_shopping_already_exists')
            ->update([
                'acesso_card_shopping_chargeback' => 1
            ]);
    }

    public function getAllPartedAcessoCards($id)
    {
        return $this->repository->where('acesso_card_shopping_award_id', $id)
            ->whereNull('acesso_card_shopping_already_exists')
            ->get();
    }

    public function updateAcessoCardsAlreadyExists(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->whereNull('acesso_card_shopping_proxy')
            ->update($data);
    }

    public function updateAcessoCardsNotExists(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->whereNull('acesso_card_shopping_already_exists')
            ->update($data);
    }

    public function getAllNewsAcessoCardsWhereAcessoCardAwardedId($id)
    {
        return $this->repository->where('acesso_card_shopping_award_id', $id)
            ->whereNull('acesso_card_shopping_already_exists')
            ->get();
    }

    public function findAcessoCardsWithoutCards($id)
    {
        return $this->repository->whereNull('acesso_card_shopping_proxy')
            ->get();
    }

    public function getAcessoCardsAndBaseAcessoCardStatus()
    {
        return $this->repository->select([
            'acesso_card_shoppings.acesso_card_shopping_name',
            'acesso_card_shoppings.acesso_card_shopping_document',
            'base_acesso_cards_compras.base_acesso_card_status'
        ])
        ->leftJoin('base_acesso_cards_compras', 'acesso_card_shoppings.acesso_card_shopping_document', '=', 'base_acesso_cards_compras.base_acesso_card_cpf')
        ->get();
    }
}
