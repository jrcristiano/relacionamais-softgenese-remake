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
            'base_acesso_cards_completo.base_acesso_card_name',
            'base_acesso_cards_completo.base_acesso_card_cpf',
            'base_acesso_cards_completo.base_acesso_card_status',
            'call_centers.call_center_base_acesso_card_completo_id',
        ])
        ->leftJoin('base_acesso_cards_completo', 'call_centers.call_center_base_acesso_card_completo_id', '=', 'base_acesso_cards_completo.id')
        ->paginate($perPage);
    }

    public function firstCallCenter($id)
    {
        return $this->repository->select([
            'call_centers.id',
            'call_centers.created_at',
            'call_centers.call_center_reason',
            'call_centers.call_center_subproduct',
            'call_centers.call_center_base_acesso_card_completo_id',
            'call_centers.call_center_award_type',
            'call_centers.call_center_phone',
            'call_centers.call_center_email',
            'call_centers.call_center_status',
            'base_acesso_cards_completo.base_acesso_card_name',
            'base_acesso_cards_completo.base_acesso_card_cpf',
        ])
        ->leftJoin('base_acesso_cards_completo', 'call_centers.call_center_base_acesso_card_completo_id', '=', 'base_acesso_cards_completo.id')
        ->where('call_centers.id', $id)
        ->first();
    }
}
