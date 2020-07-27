<?php

namespace App\Repositories;

use App\Manager;

class ManagerRepository extends Repository
{
    protected $repository;

    public function __construct(Manager $manager)
    {
        $this->repository = $manager;
    }

    public function getManagersByPaginate($pages)
    {
        return $this->repository->orderBy('managers.id', 'desc')
            ->paginate(30);
    }

    public function getManagerNameAndId()
    {
        return $this->repository->select(['id', 'manager_name'])->get();
    }
}
