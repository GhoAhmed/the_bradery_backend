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
            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est produite lors de la récupération des produits.'], 500);
        }
    }
}
