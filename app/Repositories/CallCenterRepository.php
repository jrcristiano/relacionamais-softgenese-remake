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
        $query = $this->repository->select([
            'call_centers.id',
            'call_centers.created_at',
            'call_centers.call_center_reason',
            'call_centers.call_center_subproduct',
            'call_centers.call_center_award_type',
            'call_centers.call_center_phone',
            'call_centers.call_center_email',
            'call_centers.call_center_status',
            'acesso_cards.id as acesso_card_id',
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_document',
            'acesso_card_shoppings.id as acesso_card_shopping_id',
            'acesso_card_shoppings.acesso_card_shopping_name',
            'acesso_card_shoppings.acesso_card_shopping_document',
            'call_centers.call_center_acesso_card_id',
        ])
        ->leftJoin('acesso_cards', 'call_centers.call_center_acesso_card_id', '=', 'acesso_cards.id')
        ->leftJoin('acesso_card_shoppings', 'call_centers.call_center_acesso_card_shopping_id', '=', 'acesso_card_shoppings.id');

        $search = request()->get('search');
        $status = request()->get('status');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('acesso_cards.acesso_card_name', 'like', "%{$search}%")
                    ->orWhere('acesso_cards.acesso_card_document', 'like', "%{$search}%")
                    ->orWhere('acesso_card_shoppings.acesso_card_shopping_name', 'like', "%{$search}%")
                    ->orWhere('acesso_card_shoppings.acesso_card_shopping_document', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('call_centers.call_center_status', $status);
        }

        return $query->paginate($perPage);
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
            'acesso_cards.id as acesso_card_id',
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_document',
            'acesso_cards.acesso_card_proxy',
            'acesso_card_shoppings.id as acesso_card_shopping_id',
            'acesso_card_shoppings.acesso_card_shopping_name',
            'acesso_card_shoppings.acesso_card_shopping_document',
            'base_acesso_cards_completo.base_acesso_card_status',
            'base_acesso_cards_completo.id as base_acesso_card_id',
        ])
        ->leftJoin('acesso_cards', 'call_centers.call_center_acesso_card_id', '=', 'acesso_cards.id')
        ->leftJoin('acesso_card_shoppings', 'call_centers.call_center_acesso_card_shopping_id', '=', 'acesso_card_shoppings.id')
        ->leftJoin('base_acesso_cards_completo', 'acesso_cards.acesso_card_proxy', '=', 'base_acesso_card_proxy')
        ->where('call_centers.id', $id)
        ->first();
    }
}
