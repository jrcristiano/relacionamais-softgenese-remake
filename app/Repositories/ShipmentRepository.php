<?php

namespace App\Repositories;

use App\Note;
use App\Shipment;
use App\Spreadsheet;

class ShipmentRepository extends Repository
{
    protected $repository;
    private $shipmentRepo;

    public function __construct(Spreadsheet $spreadsheet, Shipment $shipment, Note $noteRepo)
    {
        $this->repository = $spreadsheet;
        $this->shipmentRepo = $shipment;
        $this->noteRepo = $noteRepo;
    }

    public function addGeneratedStatus($data)
    {
        return $this->shipmentRepo->save($data);
    }
}
