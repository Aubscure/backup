<?php
namespace App\Repository;

use App\Interface\Repository\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll()
    {
        return Product::paginate(200);
    }

    public function getById(int $id)
    {
        return Product::findOrFail($id);
    }

    public function create(object $data)
    {
        $product = new Product();
        $product->name = $data->name;
        $product->description = $data->description;
        $product->price = $data->price;
        $product->quantity = $data->quantity;
        $product->category = $data->category;

        if (isset($data->photo) && $data->photo instanceof UploadedFile) {
            try {
                $fileName = uniqid() . '.' . $data->photo->getClientOriginalExtension();
                $data->photo->move(public_path('products'), $fileName);
                $product->photo = 'products/' . $fileName;
            } catch (\Exception $e) {
                Log::error('File upload failed', ['error' => $e->getMessage()]);
                throw $e;
            }
        }

        $product->save();

        return $product->fresh();
    }

    public function update(object $data, int $id)
    {
        $product = Product::findOrFail($id);
        $product->name = $data->name;
        $product->description = $data->description;
        $product->price = $data->price;
        $product->quantity = $data->quantity;
        $product->category = $data->category;

        $product->save();

        return $product->fresh();
    }

    public function updatePhoto($photo, int $id)
    {
        $product = Product::findOrFail($id);

        try {
            $fileName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('products'), $fileName);
            $product->photo = 'products/' . $fileName;
            $product->save();
        } catch (\Exception $e) {
            Log::error('File upload failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $product;
    }

}
