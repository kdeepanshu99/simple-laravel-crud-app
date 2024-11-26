<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        return view('products.list', ['products' => $products]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required | min:5',
            'sku' => 'required | min:3',
            'price' => 'required | numeric',
        ];

        if ($request->image !== '' && !is_null($request->image)) {
            $rules['image'] = 'image';
        }

        $valid = Validator::make($request->all(), $rules);

        if ($valid->fails()) {
            return redirect()->route('products.create')->withInput()->withErrors($valid);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        if ($request->image !== '' && !is_null($request->image)) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;

            $product->image = $imageName;
            $product->save();
            if ($product) {
                $image->move(public_path('uploads/products'), $imageName);
            }
        }

        if ($product) {
            return redirect()->route('products.index')->with('success', 'Product added successfully.');
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', ['product' => $product]);
    }

    public function update($id, Request $request)
    {
        $product = Product::findOrFail($id);

        $rules = [
            'name' => 'required | min:5',
            'sku' => 'required | min:3',
            'price' => 'required | numeric',
        ];

        if ($request->image !== '' && !is_null($request->image)) {
            $rules['image'] = 'image';
        }

        $valid = Validator::make($request->all(), $rules);

        if ($valid->fails()) {
            return redirect()->route('products.edit', $product->id)->withInput()->withErrors($valid);
        }

        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        if ($request->image !== '' && !is_null($request->image)) {
            File::delete(public_path('uploads/products/' . $product->image));
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;

            $product->image = $imageName;
            $product->save();
            if ($product) {
                $image->move(public_path('uploads/products'), $imageName);
            }
        }

        if ($product) {
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        File::delete(public_path('uploads/products/' . $product->image));

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
