<?php

namespace App\Repositories;

use App\HistoryAcessoCard;
use Illuminate\Http\Request;

class HistoryAcessoCardRepository extends Repository
{
    protected $repository;

    public function __construct(HistoryAcessoCard $historyAcessoCard)
    {
        $this->repository = $historyAcessoCard;
    }

    public function getInfoBaseAcessoCardsAndAcessoCardsByAwardId($id)
    {
        return $this->repository->select([
            'acesso_cards.*',
            'base_acesso_cards_completo.*',
        ])
        ->leftJoin('acesso_cards', 'history_acesso_cards.history_acesso_card_id', '=', 'acesso_cards.id')
        ->leftJoin('base_acesso_cards_completo', 'history_acesso_cards.history_base_id', '=', 'base_acesso_cards_completo.id')
        ->where('acesso_cards.acesso_card_award_id', $id)
        ->get();
    }

    public function queryAwardedsOfAllAwards(Request $request, $perPage = 500)
    {
        $query = $this->repository->select([
            'awards.awarded_status',
            'acesso_cards.acesso_card_value',
            'acesso_cards.acesso_card_generated',
            'acesso_cards.acesso_card_chargeback',
            'acesso_cards.created_at',
            'base_acesso_cards_completo.base_acesso_card_name',
            'base_acesso_cards_completo.base_acesso_card_proxy',
            'base_acesso_cards_completo.base_acesso_card_status',
            'base_acesso_cards_completo.base_acesso_card_cpf',
            'base_acesso_cards_completo.base_acesso_card_number'
        ])
        ->leftJoin('acesso_cards', 'history_acesso_cards.history_acesso_card_id', '=', 'acesso_cards.id')
        ->leftJoin('base_acesso_cards_completo', 'history_acesso_cards.history_base_id', '=', 'base_acesso_cards_completo.id')
        ->leftJoin('awards', 'acesso_cards.acesso_card_award_id', '=', 'awards.id');

        return $query->paginate($perPage);
    }

    public function queryDataByPaginate($perPage = 500)
    {
        return $this->repository->paginate($perPage);
    }

    public function getDataForFilters()
    {
        return $this->repository->select([
            'base_acesso_cards_completo.base_acesso_card_name',
            'base_acesso_cards_completo.base_acesso_card_cpf',
            'base_acesso_cards_completo.base_acesso_card_proxy',
            'base_acesso_cards_completo.base_acesso_card_status',
        ])
        ->leftJoin('base_acesso_cards_completo', 'history_acesso_cards.history_base_id', '=', 'base_acesso_cards_completo.id')
        ->whereNotNull('base_acesso_card_name')
        ->whereNotNull('base_acesso_card_cpf')
        ->get();
    }

    public function getInfoBaseAcessoCardsNotGeneratedAndAcessoCardsByAwardId($id)
    {
        return $this->repository->select([
            'acesso_cards.*',
            'base_acesso_cards_completo.*',
        ])
        ->leftJoin('acesso_cards', 'history_acesso_cards.history_acesso_card_id', '=', 'acesso_cards.id')
        ->leftJoin('base_acesso_cards_completo', 'history_acesso_cards.history_base_id', '=', 'base_acesso_cards_completo.id')
        ->where('acesso_cards.acesso_card_award_id', $id)
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
