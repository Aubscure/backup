<?php

namespace App\Interface\Repository;

interface OrderDetailRepositoryInterface
{
    public function getAll();

    public function getByOrderId(int $id);

    public function create(object $data);

    public function update(object $data, int $id);

    public function delete(int $id);
}
