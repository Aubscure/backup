<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Interface\Service\CustomerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;


class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        return $this->customerService->getAllCustomers();
    }

    public function store(CustomerStoreRequest $request)
    {
        return $this->customerService->createCustomer($request);
    }

    public function show(int $id)
    {
        return $this->customerService->getCustomerById($id);
    }

    public function update(CustomerUpdateRequest $request, int $id)
    {

        return $this->customerService->updateCustomer($request, $id);
    }
}
