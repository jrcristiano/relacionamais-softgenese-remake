<?php

namespace App\Events\Awards;

use App\Repositories\AwardRepository;
use App\Repositories\SpreadsheetRepository;

class UpdateAwardedValueAfterRemoveAwarded
{
    private $awardRepo;
    private $spreadsheetRepo;
    private $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SpreadsheetRepository $spreadsheetRepo, AwardRepository $awardRepo, $id)
    {
        $this->awardRepo = $awardRepo;
        $this->spreadsheetRepo = $spreadsheetRepo;
        $this->id = $id;
    }

    public function getAwardedRepo()
    {
        return $this->awardRepo;
    }

    public function getSpreadsheetRepo()
    {
        return $this->spreadsheetRepo;
    }

    public function getId()
    {
        return $this->id;
    }
}
