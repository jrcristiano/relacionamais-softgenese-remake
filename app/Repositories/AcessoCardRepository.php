<?php

namespace App\Repositories;

use App\AcessoCard;
use yidas\phpSpreadsheet\Helper;

class AcessoCardRepository extends Repository
{
    protected $repository;

    public function __construct(AcessoCard $acessoCard)
    {
        $this->repository = $acessoCard;
    }

    public function storeCard($fileName, $demandId, $awardDemandId)
    {
        $excel = Helper::newSpreadsheet($fileName)->getRows();

        $data = [];
        foreach ($excel as $key => $row) {
            if ($row[0] != null) {
                $key += 1;

                $data['acesso_card_document'] = $row[0];
                $data['acesso_card_name'] = $row[1];
                $data['acesso_card_value'] = $row[2];
                $data['acesso_card_spreadsheet_line'] = $key;
                $data['acesso_card_demand_id'] = $demandId;
                $data['acesso_card_award_id'] = $awardDemandId;

                $this->repository->create($data);
            }
        }

        return false;
    }

    public function getAcessoCardsWhereAwarded($id, $perPage = 200)
    {
        return $this->repository->select([
            'acesso_cards.*',
            'shipments_api.shipment_generated',
            ])
            ->leftJoin('shipments_api', 'acesso_cards.acesso_card_award_id', '=', 'shipments_api.shipment_award_id')
            ->where('acesso_cards.acesso_card_award_id', $id)
            ->paginate($perPage);
    }
}
