<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsController extends Controller
{
    public function list()
    {
        return view('pages.products.list', ['products' => Product::orderBy('name')->get()]);
    }

    public function create($slug = null)
    {
        return view('pages.products.product', ['product' => $slug ? Product::where('slug', $slug)->first() : null]);
    }

    public function store()
    {
        $request = request();

        $product = Product::where('id', $request->id)->first();

        $attributes = $request->validate([
            'name' => ['required']
        ], [
            'name.required' => 'Ürün adı boş bırakılamaz.'
        ]);

        $attributes['slug'] = null;

        if ($product) {
            $product->update($attributes);
        } else {
            Product::create($attributes);
        }

        session()->flash('success', 'Kaydedildi.');

        return response()->json([
            "status" => true
        ]);
    }
}
