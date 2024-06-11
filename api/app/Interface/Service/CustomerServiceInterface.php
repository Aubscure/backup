<?php

namespace App\Interface\Service;

interface CustomerServiceInterface
{
    public function getAllCustomers();

    public function getCustomerById(int $id);

    public function createCustomer(object $data);

    public function updateCustomer(object $data, int $id);
}
