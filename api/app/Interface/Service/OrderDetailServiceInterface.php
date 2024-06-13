<?php

namespace App\Interface\Service;

interface OrderDetailServiceInterface
{
    public function getAllOrderDetails();

    public function getOrderDetailByOrderId(int $orderId);

    public function createOrderDetail(object $data);

    public function updateOrderDetail(object $data, int $id);

    public function deleteOrderDetail(int $id);
}
