<?php
/**
 * Base Repository
 */

namespace App\Repositories;

use Exception;
use DB;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function count()
    {
        return $this->model->count();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        $data = $this->model->find($id);
        if (!$data) {
            throw new Exception(trans('message.find_error'));
        }

        return $data;
    }

    public function findBy($column, $option)
    {
        $data = $this->model->where($column, $option)->get();
        if (!$data) {
            throw new Exception(trans('message.find_error'));
        }

        return $data;
    }

    public function paginate($limit)
    {
        return $this->model->paginate($limit);
    }

    public function create($input)
    {
        $data = $this->model->create($input);
        if (!$data) {
            throw new Exception(trans('message.create_error'));
        }

        return $data;
    }

    public function updateById($inputs, $id)
    {
        $data = $this->model->where('id', $id)->update($inputs);
        if (!$data) {
            throw new Exception(trans('message.update_error'));
        }

        return $data;
    }

    public function deleteById($ids)
    {
        try {
            DB::beginTransaction();
            $data = $this->model->destroy($ids);
            if (!$data) {
                throw new Exception(trans('message.delete_error'));
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $data;
    }

    public function search($column, $value)
    {
        return $this->model->where('$column LIKE "%$value%"');
    }
}
