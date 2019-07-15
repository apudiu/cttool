<?php
/**
 * Created by PhpStorm.
 * User: Apu
 * Date: 7/10/2019
 * Time: 1:07 PM
 */

namespace App\Repositories\CsvData;


use Illuminate\Support\Collection;

interface CsvDataInterface
{
    // get all models
    public function getAll(int $limit = 0, array $with = []);

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


    // model attributes
    public function getAttributes() :array;
}
