<?php

namespace App\Repository;

use App\Interface\Repository\OrderDetailRepositoryInterface;
use App\Models\OrderDetail;
use Illuminate\Http\Response;

class OrderDetailRepository implements OrderDetailRepositoryInterface
{
    public function getAll()
    {
        return OrderDetail::with(['product' => function ($query) {
            $query->select('id', 'name'); // Select only 'id' and 'name' columns
        }])->paginate(20); // Eager load product
    }

    public function getByOrderId(int $orderId)
    {
        return OrderDetail::with(['product:id,name']) // Eager load product with only id and name columns
            ->where('order_id', $orderId)
            ->get()
            ->map(function ($orderDetail) {
                return [
                    'id' => $orderDetail->id,
                    'product_id' => $orderDetail->product_id,
                    'quantity' => $orderDetail->quantity,
                    'unit_price' => $orderDetail->unit_price,
                    'total_price' => $orderDetail->total_price,
                    'product_name' => $orderDetail->product->name, // Add product name
                ];
            });
    }

    public function create(object $data)
    {
        $order_detail = new OrderDetail();
        $order_detail->order_id = $data->order_id;
        $order_detail->product_id = $data->product_id;
        $order_detail->quantity = $data->quantity;
        $order_detail->unit_price = $data->unit_price;
        $order_detail->total_price = $data->total;
        $order_detail->save();

        return $order_detail->fresh();
    }

    public function update(object $data, int $id)
    {
        $order_detail = OrderDetail::findOrFail($id);
        $order_detail->order_id = $data->order_id;
        $order_detail->product_id = $data->product_id;
        $order_detail->quantity = $data->quantity;
        $order_detail->unit_price = $data->unit_price;
        $order_detail->total_price = $data->total;
        $order_detail->save();

        return $order_detail->fresh();
    }

    public function delete(int $id)
    {
        $product = OrderDetail::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Success'
        ], Response::HTTP_OK);
    }
}
