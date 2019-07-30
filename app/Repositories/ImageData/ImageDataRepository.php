<?php
namespace App\Repositories\ImageData;

use App\File;

class ImageDataRepository implements ImageDataInterface
{
    // Model (resolved by IoC/service container)
    private $model;

    public function __construct(File $file)
    {
        // Getting model using Laravel service container (IoC container)
        $this->model = $file;
    }

    /**
     * Get all models
     * @param int $limit
     * @param array $where
     * @param array $with
     * @param array $sort   sort by, first element is for sort direction & second for column ['asc', 'created_at'].
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(int $limit=0, array $where=[], array $with=[], array $sort=['asc', 'created_at'])
    {
        if ($limit) {
            $r = $this->model->with($with)->where($where)->orderBy($sort[1], $sort[0])->limit($limit)->get();
        } else {
            $r = $this->model->with($with)->where($where)->orderBy($sort[1], $sort[0])->get();
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
        return getAuthUser()->files()->createMany($attributes);
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
     * Gives the model
     * This is NOT GOOD PRACTICE but doing it anyway
     * @return File
     */
    public function model() {
        return $this->model;
    }


    /**
     * Model attributes
     * @return array
     */
    public function getAttributes() :array {
        return $this->model->getFillable();
    }
}
