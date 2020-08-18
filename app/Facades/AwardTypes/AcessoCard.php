<?php

namespace App\Facades\AwardTypes;

use App\Repositories\AwardRepository as AwardRepo;
use App\Services\AcessoCardService;

class AcessoCard extends Award
{
    protected $awardRepo;
    protected $service;

    public function __construct(AwardRepo $awardRepo, AcessoCardService $service)
    {
        $this->awardRepo = $awardRepo;
        $this->service = $service;
    }

    public function storeAward($file, array $data)
    {
        $validDocument = $this->service->isDocumentValid($file->getFullFileName(), 0, 2);

        if ($file->validation() && $validDocument) {
            $demandId = $data['awarded_demand_id'];

            $awardedValue = $this->service->getAwardedValue();

            $data['awarded_value'] = $awardedValue;
            $data['awarded_upload_table'] = $file->getFullFileName();

            $save = $this->awardRepo->save($data);

            $this->service->storeCard($file->getFullFileName(), $demandId, $save->id);
        }
    }
}
