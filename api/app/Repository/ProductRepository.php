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
        return Product::with('sizes')->paginate(200);
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

            // Save sizes if provided
            $product->save();

            // Save sizes if provided
            if (isset($data->sizes) && is_array($data->sizes)) {
                foreach ($data->sizes as $size) {
                    $product->sizes()->create([
                        'size' => $size['size'], // Access size from the array
                        'quantity' => $size['quantity'] // Access quantity from the array
                    ]);
                }
            }
        return $product->fresh();
    }

    public function update(object $data, int $id)
    {
        $product = Product::findOrFail($id);
        $product->name = $data->name;
        $product->description = $data->description;
        $product->price = $data->price;
        $product->category = $data->category;

        // Save sizes if provided
        if (isset($data->sizes) && is_array($data->sizes)) {
            // Remove sizes associated with the product that are not present in the updated data
            $currentSizes = $product->sizes()->pluck('size')->toArray();
            $updatedSizes = array_column($data->sizes, 'size');
            $sizesToRemove = array_diff($currentSizes, $updatedSizes);
            $product->sizes()->whereIn('size', $sizesToRemove)->delete();

            // Update existing sizes and add new sizes
            foreach ($data->sizes as $size) {
                $product->sizes()->updateOrCreate(
                    ['size' => $size['size']],
                    ['quantity' => $size['quantity']]
                );
            }
        }

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
