<?php

namespace App\Repositories;

use App\HistoryAcessoCard;

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
