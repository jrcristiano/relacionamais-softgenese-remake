<?php

namespace App\Repositories;

use App\Demand;
use App\Note;
use App\NoteReceipt;
use App\Receive;
use Illuminate\Support\Facades\DB;

class ReceiveRepository extends Repository
{
    protected $repository;
    private $demandRepo;
    private $noteRepo;
    private $noteReceipt;

    public function __construct(Receive $receive, Demand $demand, Note $note, NoteReceipt $noteReceipt)
    {
        $this->repository = $receive;
        $this->demandRepo = $demand;
        $this->noteRepo = $note;
        $this->noteReceipt = $noteReceipt;
    }

    public function getDataDemandsNotesBanksByPaginate($perPage = 500, array $filter = [], $id = null)
    {
        $query = $this->demandRepo->select([
            'demands.demand_client_name',
            'demands.demand_prize_amount as receive_prize_amount',
            'demands.demand_taxable_amount as receive_taxable_amount',
            'demands.demand_taxable_manual',
            'demands.demand_other_value',

            'notes.id as note_id',
            'notes.note_number',
            'notes.note_status',
            'notes.note_due_date',
            'notes.note_receipt_date',
            'notes.note_demand_id',
            'notes.note_created_at',

            'banks.bank_account',
        ])
        ->join('notes', 'demands.id', '=', 'notes.note_demand_id')
        ->leftJoin('banks', 'notes.note_account_receipt_id', '=', 'banks.id')
        ->leftJoin('awards', 'demands.id', '=', 'awards.awarded_demand_id')
        ->orderBy('notes.id', 'desc')
        ->groupBy('demands.id');

        $between = array(
            $filter[0],
            $filter[1]
        );

        $status = $filter[2];
        $client = $filter[3];

        if ($between[0] && $between[1] && !$status && !$client) {
            return $query->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_due_date', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('notes.note_created_at', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_receipt_date', $between);
            })
            ->paginate($perPage);
        }

        if ($between[0] && $between[1] && $status != 2 && !$client) {
            return $query->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('notes.note_created_at', $between)
                    ->where('notes.note_status', $status);
            })
            ->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->paginate($perPage);
        }

        if ($between[0] && $between[1] && $status == 2 && !$client) {
            return $query->whereBetween('note_receipt_date', $between)
                ->where('notes.note_status', $status)
                ->paginate($perPage);
        }

        if ($between[0] && $between[1] && $status != 2 && $client) {
            return $query->orWhere(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
            })
            ->orWhere(function ($query) use ($between, $status, $client) {
                $query->whereBetween('notes.note_created_at', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
            })
            ->orWhere(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
            })
            ->paginate($perPage);
        }

        if ($between[0] && $between[1] && $status == 2 && $client) {
            return $query->whereBetween('note_receipt_date', $between)
                ->where('notes.note_status', $status)
                ->where('demands.demand_client_name', $client)
                ->paginate($perPage);
        }

        if ($between[0] && $between[1] && !$status && $client) {
            return $query->orWhere(function ($query) use ($between, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('demands.demand_client_name', $client);
            })
            ->orWhere(function ($query) use ($between, $client) {
                $query->whereBetween('notes.note_created_at', $between)
                    ->where('demands.demand_client_name', $client);
            })
            ->orWhere(function ($query) use ($between, $client) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('demands.demand_client_name', $client);
            })
            ->paginate($perPage);
        }

        if ($status && $client) {
            return $query->where('notes.note_status', $status)
                ->where('demands.demand_client_name', $client)
                ->paginate($perPage);
        }

        if ($client) {
            return $query->where('demands.demand_client_name', $client)
                ->paginate($perPage);
        }

        if ($status) {
            return $query->where('notes.note_status', $status)
                ->paginate($perPage);
        }

        return $query->paginate($perPage);
    }

    public function getAwardTotal(array $filter = [])
    {
        $between = array(
            $filter[0],
            $filter[1]
        );

        $status = $filter[2];
        $client = $filter[3];

        $minCreatedAt = $this->hasMinNoteCreatedAt($between);
        $minDueDate = $this->hasMinNoteDueDate($between);
        $minNoteReceiptDate = $this->hasMinNoteReceiptDate($between);

        if (!$minCreatedAt && !$minDueDate && !$minNoteReceiptDate) {
            $between[0] = '2019-01-01';
            $between[1] = $between[1];
        }

        $query = $this->demandRepo->leftJoin('notes', 'demands.id', '=', 'notes.note_demand_id')
            ->select(DB::raw('SUM(demand_prize_amount) as award_total'));

        if ($between[0] && $between[1] && !$status && !$client) {
            return $query->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_due_date', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('notes.note_created_at', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_receipt_date', $between);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status != 2 && !$client) {
            return $query->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('notes.note_created_at', $between)
                    ->where('notes.note_status', $status);
            })
            ->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status == 2 && !$client) {
            return $query->where(function ($query) use ($between, $status) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status != 2 && $client) {
            return $query->orWhere(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $status, $client) {
                    $query->whereBetween('notes.note_created_at', $between)
                        ->where('notes.note_status', $status)
                        ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $status, $client) {
                    $query->whereBetween('note_receipt_date', $between)
                        ->where('notes.note_status', $status)
                        ->where('demands.demand_client_name', $client);
                })
                ->first();
        }

        if ($between[0] && $between[1] && $status == 2 && $client) {
            return $query->where(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
            })
            ->first();
        }

        if ($between[0] && $between[1] && !$status && $client) {
            return $query->orWhere(function ($query) use ($between, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $client) {
                    $query->whereBetween('notes.note_created_at', $between)
                        ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $client) {
                    $query->whereBetween('note_receipt_date', $between)
                        ->where('demands.demand_client_name', $client);
                })
                ->first();
        }

        if ($status && $client) {
            return $query->where('notes.note_status', $status)
                ->where('demands.demand_client_name', $client)
                ->first();
        }

        if ($client) {
            return $query->where('demands.demand_client_name', $client)
                ->first();
        }

        if ($status) {
            return $query->where('notes.note_status', $status)
                ->first();
        }

        return $query->first();
    }

    public function getPatrimonyTotal(array $filter = [])
    {
        $between = array(
            $filter[0],
            $filter[1]
        );

        $status = $filter[2];
        $client = $filter[3];

        $minCreatedAt = $this->hasMinNoteCreatedAt($between);
        $minDueDate = $this->hasMinNoteDueDate($between);
        $minNoteReceiptDate = $this->hasMinNoteReceiptDate($between);

        if (!$minCreatedAt && !$minDueDate && !$minNoteReceiptDate) {
            $between[0] = '2019-01-01';
            $between[1] = $between[1];
        }

        $query = $this->demandRepo->leftJoin('notes', 'demands.id', '=', 'notes.note_demand_id')
            ->select(DB::raw('SUM(demand_taxable_amount) as patrimony_total'));

        if ($between[0] && $between[1] && !$status && !$client) {
            return $query->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_due_date', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('notes.note_created_at', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_receipt_date', $between);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status != 2 && !$client) {
            return $query->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('notes.note_created_at', $between)
                    ->where('notes.note_status', $status);
            })
            ->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status == 2 && !$client) {
            return $query->where(function ($query) use ($between) {
                $query->whereBetween('note_receipt_date', $between);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status != 2 && $client) {
            return $query->orWhere(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $status, $client) {
                    $query->whereBetween('notes.note_created_at', $between)
                        ->where('notes.note_status', $status)
                        ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $status, $client) {
                    $query->whereBetween('note_receipt_date', $between)
                        ->where('notes.note_status', $status)
                        ->where('demands.demand_client_name', $client);
                })
                ->first();
        }

        if ($between[0] && $between[1] && $status == 2 && $client) {
            return $query->where(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
            })
            ->first();
        }

        if ($between[0] && $between[1] && !$status && $client) {
            return $query->orWhere(function ($query) use ($between, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $client) {
                    $query->whereBetween('notes.note_created_at', $between)
                        ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $client) {
                    $query->whereBetween('note_receipt_date', $between)
                        ->where('demands.demand_client_name', $client);
                })
                ->first();
        }

        if ($status && $client) {
            return $query->where('notes.note_status', $status)
                ->where('demands.demand_client_name', $client)
                ->first();
        }

        if ($client) {
            return $query->where('demands.demand_client_name', $client)
                ->first();
        }

        if ($status) {
            return $query->where('notes.note_status', $status)
                ->first();
        }

        return $query->first();
    }

    public function getTaxableManual(array $filter = [])
    {
        $between = array(
            $filter[0],
            $filter[1]
        );

        $status = $filter[2];
        $client = $filter[3];

        $minCreatedAt = $this->hasMinNoteCreatedAt($between);
        $minDueDate = $this->hasMinNoteDueDate($between);
        $minNoteReceiptDate = $this->hasMinNoteReceiptDate($between);

        if (!$minCreatedAt && !$minDueDate && !$minNoteReceiptDate) {
            $between[0] = '2019-01-01';
            $between[1] = $between[1];
        }

        $query = $this->demandRepo->leftJoin('notes', 'demands.id', '=', 'notes.note_demand_id')
            ->select(DB::raw('SUM(demand_taxable_manual) as taxable_manual'));

        if ($between[0] && $between[1] && !$status && !$client) {
            return $query->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_due_date', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('notes.note_created_at', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_receipt_date', $between);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status != 2 && !$client) {
            return $query->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('notes.note_created_at', $between)
                    ->where('notes.note_status', $status);
            })
            ->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status == 2 && !$client) {
            return $query->where(function ($query) use ($between, $status) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status != 2 && $client) {
            return $query->orWhere(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $status, $client) {
                    $query->whereBetween('notes.note_created_at', $between)
                        ->where('notes.note_status', $status)
                        ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $status, $client) {
                    $query->whereBetween('note_receipt_date', $between)
                        ->where('notes.note_status', $status)
                        ->where('demands.demand_client_name', $client);
                })
                ->first();
        }

        if ($between[0] && $between[1] && $status == 2 && $client) {
            return $query->where(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
            })
            ->first();
        }

        if ($between[0] && $between[1] && !$status && $client) {
            return $query->orWhere(function ($query) use ($between, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $client) {
                    $query->whereBetween('notes.note_created_at', $between)
                        ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $client) {
                    $query->whereBetween('note_receipt_date', $between)
                        ->where('demands.demand_client_name', $client);
                })
                ->first();
        }

        if ($status && $client) {
            return $query->where('notes.note_status', $status)
                ->where('demands.demand_client_name', $client)
                ->first();
        }

        if ($client) {
            return $query->where('demands.demand_client_name', $client)
                ->first();
        }

        if ($status) {
            return $query->where('notes.note_status', $status)
                ->first();
        }

        return $query->first();
    }

    public function getOtherValues(array $filter)
    {
        $between = array(
            $filter[0],
            $filter[1]
        );

        $status = $filter[2];
        $client = $filter[3];

        $minCreatedAt = $this->hasMinNoteCreatedAt($between);
        $minDueDate = $this->hasMinNoteDueDate($between);
        $minNoteReceiptDate = $this->hasMinNoteReceiptDate($between);

        if (!$minCreatedAt && !$minDueDate && !$minNoteReceiptDate) {
            $between[0] = '2019-01-01';
            $between[1] = $between[1];
        }

        $query = $this->demandRepo->leftJoin('notes', 'demands.id', '=', 'notes.note_demand_id')
            ->select(DB::raw('SUM(demand_other_value) as other_value_total'));

        if ($between[0] && $between[1] && !$status && !$client) {
            return $query->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_due_date', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('notes.note_created_at', $between);
            })
            ->orWhere(function ($query) use ($between) {
                $query->whereBetween('note_receipt_date', $between);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status != 2 && !$client) {
            return $query->orWhere(function ($query) use ($between, $status) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status);
                })
                ->orWhere(function ($query) use ($between, $status) {
                    $query->whereBetween('notes.note_created_at', $between)
                        ->where('notes.note_status', $status);
                })
                ->orWhere(function ($query) use ($between, $status) {
                    $query->whereBetween('note_receipt_date', $between)
                        ->where('notes.note_status', $status);
                })
                ->first();
        }

        if ($between[0] && $between[1] && $status == 2 && !$client) {
            return $query->where(function ($query) use ($between, $status) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status);
            })
            ->first();
        }

        if ($between[0] && $between[1] && $status != 2 && $client) {
            return $query->orWhere(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $status, $client) {
                    $query->whereBetween('notes.note_created_at', $between)
                        ->where('notes.note_status', $status)
                        ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $status, $client) {
                    $query->whereBetween('note_receipt_date', $between)
                        ->where('notes.note_status', $status)
                        ->where('demands.demand_client_name', $client);
                })
                ->first();
        }

        if ($between[0] && $between[1] && $status == 2 && $client) {
            return $query->where(function ($query) use ($between, $status, $client) {
                $query->whereBetween('note_receipt_date', $between)
                    ->where('notes.note_status', $status)
                    ->where('demands.demand_client_name', $client);
            })
            ->first();
        }

        if ($between[0] && $between[1] && !$status && $client) {
            return $query->orWhere(function ($query) use ($between, $client) {
                $query->whereBetween('note_due_date', $between)
                    ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $client) {
                    $query->whereBetween('notes.note_created_at', $between)
                        ->where('demands.demand_client_name', $client);
                })
                ->orWhere(function ($query) use ($between, $client) {
                    $query->whereBetween('note_receipt_date', $between)
                        ->where('demands.demand_client_name', $client);
                })
                ->first();
        }

        if ($status && $client) {
            return $query->where('notes.note_status', $status)
                ->where('demands.demand_client_name', $client)
                ->first();
        }

        if ($client) {
            return $query->where('demands.demand_client_name', $client)
                ->first();
        }

        if ($status) {
            return $query->where('notes.note_status', $status)
                ->first();
        }

        return $query->first();
    }

    public function hasMinNoteDueDate(array $between)
    {
        $movementDate = $this->noteRepo->select(DB::raw('min(note_due_date) as minor_date'))
            ->whereBetween('note_due_date', $between)
            ->first();

        return $movementDate->minor_date;
    }

    public function hasMinNoteCreatedAt(array $between)
    {
        $movementDate = $this->noteRepo->select(DB::raw('min(note_created_at) as minor_date'))
            ->whereBetween('note_created_at', $between)
            ->first();

        return $movementDate->minor_date;
    }

    public function hasMinNoteReceiptDate(array $between)
    {
        $movementDate = $this->noteRepo->select(DB::raw('min(note_receipt_date) as minor_date'))
            ->whereBetween('note_receipt_date', $between)
            ->first();

        return $movementDate->minor_date;
    }

    public function getDemandToReceiveSelect()
    {
        return $this->repository->select('demands.*')
            ->join('demands', 'receives.id', '=', 'demands.id')
            ->orderBy('receives.id', 'desc')
            ->get();
    }

    public function deleteNotesAndNoteReceipts($id, $demandId)
    {
        $this->noteRepo->where('note_demand_id', $demandId)->delete();
        $this->noteReceipt->where('note_receipt_demand_id', $demandId)->delete();

        return $this->repository->find($id)
            ->delete();
    }
}
