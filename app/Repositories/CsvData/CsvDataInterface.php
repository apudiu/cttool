<?php
/**
 * Created by PhpStorm.
 * User: Apu
 * Date: 7/10/2019
 * Time: 1:07 PM
 */

namespace App\Repositories\CsvData;


interface CsvDataInterface
{
    // get all clients
    public function getAll(int $limit = 0, array $with = []);

    // get a client by id
    public function getById(int $id, array $with = []);

    // create a client
    public function create(array $attributes);

    // update a client
    public function update(int $id, array $attributes);

    // delete a client
    public function delete(int $id);
}
