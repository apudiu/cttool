<?php
/**
 * Created by PhpStorm.
 * User: Apu
 * Date: 7/10/2019
 * Time: 1:07 PM
 */

namespace App\Repositories\CsvData;

use App\CsvData;

class CsvDataRepository implements CsvDataInterface
{
    // Model (resolved by IoC/service container)
    private $model;

    public function __construct(CsvData $csvData)
    {
        // Getting model using Laravel service container (IoC container)
        $this->model = $csvData;
    }

    /**
     * Get all clients
     * @param array $with
     * @param bool $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(int $limit=0, array $with=[])
    {
        if ($limit) {
            $r = $this->model->with($with)->limit($limit)->get();
        } else {
            $r = $this->model->with($with)->get();
        }

        return $r;
    }
    /**
     * Get client by id
     * @param int $id
     * @param array $with
     * @return mixed
     */
    public function getById(int $id, array $with = [])
    {
        return $this->model->with($with)->findOrFail($id);
    }
    /**
     * Create client
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }
    /**
     * Update client
     * @param int $id
     * @param array $attributes
     * @return mixed
     */
    public function update(int $id, array $attributes)
    {
        return $this->getById($id)->update($attributes);
    }
    /**
     * Delete client
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->getById($id)->delete();
    }
}
