<?php

namespace App\Repositories;

abstract class Repository
{
    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function save(array $data, $id = null)
    {
        if (!$id) {
            return $this->repository->create($data);
        }

        return $this->repository->find($id)
            ->fill($data)
            ->save();
    }

    public function update(array $data, string $field, $value)
    {
        return $this->repository->where($field, $value)
            ->update($data);
    }

    public function delete(int $id)
    {
        return $this->repository->find($id)
            ->delete();
    }
}
