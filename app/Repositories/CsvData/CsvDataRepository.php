<?php
/**
 * Created by PhpStorm.
 * User: Apu
 * Date: 7/10/2019
 * Time: 1:07 PM
 */

namespace App\Repositories\CsvData;

use App\CsvData;
use Illuminate\Support\Collection;

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
     * Get all models
     * @param int $limit
     * @param array $with
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
     * Get model by id
     * @param int $id
     * @param array $with
     * @return mixed
     */
    public function getById(int $id, array $with = [])
    {
        return $this->model->with($with)->findOrFail($id);
    }

    /**
     * Create model
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Create many models at once
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createMany(array $attributes)
    {
        return getAuthUser()->csv_data()->createMany($attributes);
    }

    /**
     * Update model
     * @param int $id
     * @param array $attributes
     * @return mixed
     */
    public function update(int $id, array $attributes)
    {
        return $this->getById($id)->update($attributes);
    }

    /**
     * Delete model
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->getById($id)->delete();
    }


    /**
     * Model attributes
     * @return array
     */
    public function getAttributes() :array {
        return $this->model->getFillable();
    }
}
