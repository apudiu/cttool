<?php
namespace App\Repositories\ImageData;

use App\File;

interface ImageDataInterface
{
    // get all models
    public function getAll(int $limit = 0, array $where = [], array $with = [], array $sort=['asc', 'created_at']);

    // get a model by id
    public function getById(int $id, array $with = []);

    // create a model
    public function create(array $attributes);

    // create many models
    public function createMany(array $attributes);

    // update a model
    public function update(int $id, array $attributes);

    // delete a model
    public function delete(int $id);

    // return model
    public function model();


    // model attributes
    public function getAttributes() :array;
}
