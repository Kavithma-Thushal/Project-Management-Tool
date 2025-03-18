<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

interface CrudRepositoryInterface
{
    public function find(int $id, array $relations = null): ?object;

    public function getAll(array $filters,array $relations = [],array $sortBy = []): Collection;

    public function getPaginated(int $page = 1,int $ppg = 10,array $filters = []): object;

    public function add(array $data): ?object;

    public function update(int $id, array $data) : bool;

    public function updateOrCreate(array $key, array $data);

    public function firstOrCreate(array $key, array $data);

    public function delete(int $id) : bool;

    public function isIdExist(int $id) : bool;
}
