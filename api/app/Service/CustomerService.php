<?php
namespace App\Service;

use App\Http\Resources\CustomerResource;
use App\Interface\Repository\CustomerRepositoryInterface;
use App\Interface\Service\CustomerServiceInterface;

class CustomerService implements CustomerServiceInterface
{
    private $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAllCustomers()
    {
        return $this->customerRepository->getAll();
    }

    public function getCustomerById(int $id)
    {
        $customer = $this->customerRepository->getById($id);

        return new CustomerResource($customer);
    }

    public function createCustomer(object $data)
    {
        $customer = $this->customerRepository->create($data);

        return new CustomerResource($customer);
    }

    public function updateCustomer(object $data, int $id)
    {
        $customer = $this->customerRepository->update($data, $id);

        return new CustomerResource($customer);
    }
}
