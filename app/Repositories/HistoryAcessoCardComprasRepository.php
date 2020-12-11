<?php

namespace App\Repositories;

use App\HistoryAcessoCardCompra;
use Illuminate\Http\Request;

class HistoryAcessoCardComprasRepository extends Repository
{
    protected $repository;

    public function __construct(HistoryAcessoCardCompra $historyAcessoCard)
    {
        $this->repository = $historyAcessoCard;
    }

    public function getInfoBaseAcessoCardsAndAcessoCardsByAwardId($id)
    {
        return $this->repository->select([
            'acesso_card_shoppings.*',
            'base_acesso_cards_compras.*',
        ])
        ->leftJoin('acesso_card_shoppings', 'history_acesso_card_compras.history_acesso_card_id', '=', 'acesso_card_shoppings.id')
        ->leftJoin('base_acesso_cards_compras', 'history_acesso_card_compras.history_base_id', '=', 'base_acesso_cards_compras.id')
        ->where('acesso_card_shoppings.acesso_card_shopping_award_id', $id)
        ->whereNull('acesso_card_shopping_chargeback')
        ->get();
    }

    public function queryAwardedsOfAllAwards(Request $request, $perPage = 500)
    {
        $query = $this->repository->select([
            'awards.awarded_status',
            'acesso_card_shoppings.acesso_card_shopping_value',
            'acesso_card_shoppings.acesso_card_shopping_generated',
            'acesso_card_shoppings.acesso_card_shopping_chargeback',
            'acesso_card_shoppings.created_at',
            'base_acesso_cards_compras.base_acesso_card_name',
            'base_acesso_cards_compras.base_acesso_card_proxy',
            'base_acesso_cards_compras.base_acesso_card_status',
            'base_acesso_cards_compras.base_acesso_card_cpf',
            'base_acesso_cards_compras.base_acesso_card_number'
        ])
        ->leftJoin('acesso_card_shoppings', 'history_acesso_card_compras.history_acesso_card_id', '=', 'acesso_card_shoppings.id')
        ->leftJoin('base_acesso_cards_compras', 'history_acesso_card_compras.history_base_id', '=', 'base_acesso_cards_compras.id')
        ->leftJoin('awards', 'acesso_card_shoppings.acesso_card_shopping_award_id', '=', 'awards.id');

        return $query->paginate($perPage);
    }

    public function queryDataByPaginate($perPage = 500)
    {
        return $this->repository->paginate($perPage);
    }

    public function getDataForFilters()
    {
        return $this->repository->select([
            'base_acesso_cards_compras.base_acesso_card_name',
            'base_acesso_cards_compras.base_acesso_card_cpf',
            'base_acesso_cards_compras.base_acesso_card_proxy',
            'base_acesso_cards_compras.base_acesso_card_status',
        ])
        ->leftJoin('base_acesso_cards_compras', 'history_acesso_card_compras.history_base_id', '=', 'base_acesso_cards_compras.id')
        ->whereNotNull('base_acesso_card_name')
        ->whereNotNull('base_acesso_card_cpf')
        ->get();
    }

    public function getInfoBaseAcessoCardsNotGeneratedAndAcessoCardsByAwardId($id)
    {
        return $this->repository->select([
            'acesso_card_shoppings.*',
            'base_acesso_cards_compras.*',
        ])
        ->leftJoin('acesso_card_shoppings', 'history_acesso_card_compras.history_acesso_card_id', '=', 'acesso_cards.id')
        ->leftJoin('base_acesso_cards_compras', 'history_acesso_card_compras.history_base_id', '=', 'base_acesso_cards_compras.id')
        ->where('acesso_card_shoppings.acesso_card_shopping_award_id', $id)
        ->whereNull('base_acesso_card_generated')
        ->get();
    }

    public function findAcessoCardId($id)
    {
        return $this->repository->where('history_acesso_card_id', $id)
            ->first();
    }

    public function saveByHistoryAcessoCardId($data, $id)
    {
        return $this->repository->where('history_acesso_card_id', $id)
            ->update($data);
    }
}
