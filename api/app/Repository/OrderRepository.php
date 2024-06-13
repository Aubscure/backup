<?php

namespace App\Repository;

use App\Interface\Repository\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderDetail;
use Illuminate\Http\Response;

class OrderRepository implements OrderRepositoryInterface
{
    public function getAll()
    {
        return Order::paginate(200);
    }

    public function getById(int $id)
    {
        return Order::findOrFail($id);
    }

    public function create(object $data)
    {
        // Create the customer
        $customer = new Customer();
        $customer->first_name = $data->customer_first_name;
        $customer->last_name = $data->customer_last_name;
        $customer->id_number = $data->customer_id_number;
        $customer->save();

        // Create the order
        $order = new Order();
        $order->customer_id = $customer->id;
        $order->total_price = $data->total_price;
        $order->payment_method = isset($data->payment_method) ? $data->payment_method : 'cashier';
        $order->order_status = isset($data->order_status) ? $data->order_status : 'pending';
        $order->save();

        // Create order details
        foreach ($data->order_details as $detail) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $detail['product_id'];
            $orderDetail->quantity = $detail['quantity'];
            $orderDetail->unit_price = $detail['unit_price'];
            $orderDetail->total = $detail['total'];
            $orderDetail->save();
        }

        return $order->fresh();
    }

    public function update(object $data, int $id)
    {
        $order = Order::findOrFail($id);
        if (isset($data->order_status) && in_array($data->order_status, ['completed'])) {
            $order->order_status = $data->order_status;

            $order->save();

            return $order->fresh();
        }

        return null; // Handle invalid status or other error scenarios
    }


    public function delete(int $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'Success'
        ], Response::HTTP_OK);
    }
}
