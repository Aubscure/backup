<?php

namespace App\Repository;

use App\Interface\Repository\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Http\Response;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getAll()
    {
        return Customer::paginate(20);
    }

    public function getById(int $id)
    {
        return Customer::findOrFail($id);
    }

    public function create(object $data)
    {
        $customer = new Customer();
        $customer->first_name = $data->first_name;
        $customer->last_name = $data->last_name;
        $customer->id_number = $data->id_number;
        $customer->save();

        return $customer->fresh();
    }

    public function update(object $data, int $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->first_name = $data->first_name;
        $customer->last_name = $data->last_name;
        $customer->id_number = $data->id_number;
        $customer->save();

        return $customer->fresh();
    }

}
