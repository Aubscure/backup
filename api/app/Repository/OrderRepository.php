<?php

namespace App\Repository;

use App\Interface\Repository\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\Customer; // Add this line
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

        // Check if payment_method is provided, otherwise use default
        $order->payment_method = isset($data->payment_method) ? $data->payment_method : 'cashier';

        // Check if order_status is provided, otherwise use default
        $order->order_status = isset($data->order_status) ? $data->order_status : 'pending';

        $order->save();

        return $order->fresh();
    }



    public function update(object $data, int $id)
    {
        $order = Order::findOrFail($id);
        $order->customer_id = $data->customer_id;
        $order->total_price = $data->total_price;
        $order->payment_method = $data->payment_method;
        $order->order_status = $data->order_status;
        $order->save();

        return $order->fresh();
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
