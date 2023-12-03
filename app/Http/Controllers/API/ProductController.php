<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProducts()
    {
        try {
            $products = Product::all();

            // Transform the products to include the full image URL
            $products = $products->map(function ($product) {
                // Remove 'public/' from the image path
                $product->image = asset('storage/' . substr($product->image, 7));
                return $product;
            });

            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching products.'], 500);
        }
    }

    public function getProductDetails($id)
    {
        try {
            // Find the product by ID
            $product = Product::findOrFail($id);

            // Return the product details
            return response()->json($product);
        } catch (ModelNotFoundException $e) {
            // Product not found
            return response()->json(['error' => 'Product not found'], 404);
        } catch (\Exception $e) {
            // Other unexpected errors
            \Log::error('Error fetching product details: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching product details'], 500);
        }
    }

}
