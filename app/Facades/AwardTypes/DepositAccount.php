<?php

namespace App\Facades\AwardTypes;

use App\Facades\AwardTypes\Award;
use App\Repositories\AwardRepository;
use App\Services\SpreadsheetService;

class DepositAccount extends Award
{
    protected $awardRepo;
    protected $service;

    public function __construct(AwardRepository $awardRepo, SpreadsheetService $service)
    {
        $this->awardRepo = $awardRepo;
        $this->service = $service;
    }

    public function storeAward($file, array $data)
    {
        $validDocument = $this->service->isDocumentValid($file->getFullFileName(), 1, 2);

        if ($file->validation() && $validDocument) {
            $demandId = $data['awarded_demand_id'];

            $awardedValue = $this->service->getAwardedValue();

            $data['awarded_value'] = $awardedValue;
            $data['awarded_upload_table'] = $file->getFileName();

            $save = $this->awardRepo->save($data);
            $this->service->storeShipment($file->getFullFileName(), $demandId, $save->id);
        }
    }
}
