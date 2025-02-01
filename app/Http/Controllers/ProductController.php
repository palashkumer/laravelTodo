<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // This method will show products page
    public function  index()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        return view('products.list', ['products' => $products]);
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
            // 'image' => 'nullable|image',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('products.create')->withInput()->withErrors($validator);
        }

        //here we will intert product in db
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
            $image->move(public_path('uploads/products_image/' . $imageName));

            //save the image to the database
            $product->image = $imageName;
            $product->save();
        }





        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('public/uploads/products');
        //     $imageName = basename($imagePath);
        //     $product->image = $imageName;
        //     $product->save();
        // }

        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    // This method will show products page
    public function edit() {}

    // This method will update products
    public function update() {}

    // This method will delete products 
    public function destroy() {}
}
