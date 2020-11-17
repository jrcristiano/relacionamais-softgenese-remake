<?php

namespace App\Repositories;

use App\CallCenter;

class CallCenterRepository extends Repository
{
    protected $repository;

    public function __construct(CallCenter $callCenter)
    {
        $this->repository = $callCenter;
    }

    public function getCallCentersByPaginate($perPage = 500)
    {
        return $this->repository->select([
            'call_centers.id',
            'call_centers.created_at',
            'call_centers.call_center_reason',
            'call_centers.call_center_subproduct',
            'call_centers.call_center_award_type',
            'call_centers.call_center_phone',
            'call_centers.call_center_email',
            'call_centers.call_center_status',
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_document',
            'call_centers.call_center_acesso_card_id',
        ])
        ->leftJoin('acesso_cards', 'call_centers.call_center_acesso_card_id', '=', 'acesso_cards.id')
        ->paginate($perPage);
    }

    public function firstCallCenter($id)
    {
        return $this->repository->select([
            'call_centers.id',
            'call_centers.created_at',
            'call_centers.call_center_reason',
            'call_centers.call_center_subproduct',
            'call_centers.call_center_acesso_card_id',
            'call_centers.call_center_award_type',
            'call_centers.call_center_phone',
            'call_centers.call_center_email',
            'call_centers.call_center_status',
            'call_centers.call_center_comments',
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_document',
            'acesso_cards.acesso_card_proxy',
            'base_acesso_cards_completo.base_acesso_card_status',
        ])
        ->leftJoin('acesso_cards', 'call_centers.call_center_acesso_card_id', '=', 'acesso_cards.id')
        ->leftJoin('base_acesso_cards_completo', 'acesso_cards.acesso_card_proxy', '=', 'base_acesso_card_proxy')
        ->where('call_centers.id', $id)
        ->first();
    }
}
