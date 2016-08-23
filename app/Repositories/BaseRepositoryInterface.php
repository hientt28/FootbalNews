<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function count();

    public function all();

    public function find($id);

    public function findBy($column, $option);

    public function paginate($limit);

    public function create($input);

    public function updateById($inputs, $id);

    public function deleteById($ids);

    public function search($column, $value);
}
