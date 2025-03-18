<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CrudRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [],array $relations = [],array $sortBy = []): Collection
    {
        $query = $this->model->query();
        if(!empty($relations)){
            $query->with($relations);
        }
        if(!empty($sortBy)){
            foreach($sortBy as $column=>$direction){
                $query->orderBy($column,$direction);
            }
        }
        return $query->get();
    }

    public function find(int $id, array $relations = null): ?Model
    {
        $query = $this->model;
        if ($relations != null) $query = $query->with($relations);
        return $query->find($id);
    }

    public function getPaginated(int $page = 1,int $ppg = 10,array $filters = []) : object
    {
        $query = $this->model->query();
        return $query->paginate($ppg,['*'],'page',$page);
    }

    public function add(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->find($id);
        if ($record) return $record->update($data);
        return false;
    }

    public function firstOrCreate(array $keys,array $data)
    {
       return $this->model->firstOrCreate($keys,$data);
    }
   
    public function updateOrCreate(array $keys,array $data) : Model
    {
       return $this->model->updateOrCreate($keys,$data);
    }

    public function delete(int $id): bool
    {
        $record = $this->find($id);
        if ($record) return $record->delete();
        return false;
    }

    public function isIdExist(int $id): bool
    {
        $record = $this->find($id);
        if ($record) return true;
        return false;
    }

}
