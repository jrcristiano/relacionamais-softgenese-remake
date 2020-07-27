<?php

namespace App\Repositories;

abstract class Repository
{
    public function all()
    {
        return $this->repository->all();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function save($data, $id = null)
    {
        if (!$id) {
            return $this->repository->create($data);
        }

        return $this->repository->find($id)
            ->fill($data)
            ->save();
    }

    public function delete($id)
    {
        return $this->repository->find($id)
            ->delete();
    }
}
