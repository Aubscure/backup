<?php

namespace App\Providers;

use App\Interface\Repository\CustomerRepositoryInterface;
use App\Interface\Repository\OrderDetailRepositoryInterface;
use App\Interface\Repository\OrderRepositoryInterface;
use App\Interface\Repository\ProductRepositoryInterface;
use App\Interface\Repository\UserRepositoryInterface;
use App\Interface\Service\AuthenticationServiceInterface;
use App\Interface\Service\CustomerServiceInterface;
use App\Interface\Service\OrderDetailServiceInterface;
use App\Interface\Service\OrderServiceInterface;
use App\Interface\Service\ProductServiceInterface;
use App\Repository\CustomerRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\AuthenticationService;
use App\Service\CustomerService;
use App\Service\OrderDetailService;
use App\Service\OrderService;
use App\Service\ProductService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {

        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderDetailRepositoryInterface::class, OrderDetailRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(CustomerServiceInterface::class, CustomerService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(OrderDetailServiceInterface::class, OrderDetailService::class);
        $this->app->bind(AuthenticationServiceInterface::class, AuthenticationService::class);
    }


    public function boot(): void
    {
        //
    }
}
