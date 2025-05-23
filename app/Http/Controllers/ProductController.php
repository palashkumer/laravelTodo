<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // This method will show products page
    public function  index()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        return view('products.index', ['products' => $products]);
    }

    // This method will create products
    public function create()
    {
        return view('products.create');
    }

    // This method will store products in db
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('products.create')->withInput()->withErrors($validator);
        }

        //here we will insert product in db
        $product = new Product();
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        if ($request->image != "") {

            // here we will store the image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;  //unique image name

            //save image to products directory
            $image->move('uploads/products/', $imageName);

            //save the image to the database
            $product->image = $imageName;
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    // This method will show products page
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', ['product' => $product]);
    }
    // This method will update products
    public function update($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('products.edit', $product->id)->withInput()->withErrors($validator);
        }
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        if ($request->image != "") {

            //delete old image
            File::delete(public_path('uploads/products/', $product->image));

            // here we will store the image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;  //unique image name

            //save image to products directory
            $image->move('uploads/products/', $imageName);

            //save the image to the database
            $product->image = $imageName;
            $product->save();
        }
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }
    // This method will delete products
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (!empty($product->image) && Storage::exists('public/uploads/products/' . $product->image)) {
            Storage::delete('public/uploads/products/' . $product->image);
        }

        // delete product from db
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
