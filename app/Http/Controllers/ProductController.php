<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Validator;
use File;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $products = Product::all();
        $products = Product::with('category', 'sub_category')->get();

        // If use sharehosting or vps you can use this code 
        // I didnt use because deploy on heroku, which is in heroku we cannot upload image so i will generate image from asset angular and then
        // synchronized all of image same value in field image at table product
        // foreach ($products as $product) {
        //     $product['image'] = asset('products/'.$product->image);
        // }
        return response()->json([
            'status'        => true,
            'data'          => $products,
            'message'       => 'OK'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'original_price' => 'required',
            // 'discount_price' => 'required',
            'image' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = new Product;

        if ($request->has('discount')) {
            if ($request->discount >= 100) {
                return response()->json([
                    'status'        => 'DISCOUNT_VALIDATION',
                    'data'          => null,
                    'message'       => 'Can more than 100%!'
                ]);
            }

            $calculation_discount = ($request->original_price * $request->discount) / 100;
            $result_discount = $request->original_price - $calculation_discount;
            $product->discount_price = $result_discount;
            $product->discount = $request->discount;
        }

        $product->name = $request->name;
        $product->original_price = $request->original_price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;

        // if($request->has('discount_price')) {
        //     $product->discount_price = $request->discount_price;
        // }

        if ($request->hasFile('image')) {
            // $imageName = time().'_'.$request->name.'.'.$request->image->extension();
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }
        $product->save();

        return response()->json([
            'status'        => true,
            'data'          => $product,
            'message'       => 'Successfully create product!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if ($product == null) {
            return response([
                'status'        => true,
                'data'          => $product,
                'message'       => 'Product not found'
            ]);
        }

        return response([
            'status'        => true,
            'data'          => $product,
            'message'       => 'OK'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'original_price' => 'required',
            // 'discount_price' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::find($id);
        // Untuk Frontend
        // $filePath = asset('products/'.$product->image);

        if ($product == null) {
            return response([
                'status'        => false,
                'data'          => $product,
                'message'       => 'Product not found!'
            ]);
        }

        if ($request->has('discount')) {

            if ($request->discount >= 100) {
                return response()->json([
                    'status'        => 'DISCOUNT_VALIDATION',
                    'data'          => null,
                    'message'       => 'Can more than 100%!'
                ]);
            }

            $calculation_discount = ($request->original_price * $request->discount) / 100;
            $result_discount = $request->original_price - $calculation_discount;
            $product->discount_price = $result_discount;
            $product->discount = $request->discount;
        }

        $product->name = $request->name;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->original_price = $request->original_price;

        // if($request->has('discount_price')) {
        //     $product->discount_price = $request->discount_price;
        // }

        if ($request->hasFile('image')) {
            $filePath = public_path('products/') . $product->image;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }
        $product->save();

        return response()->json([
            'status'        => true,
            'data'          => $product,
            'message'       => 'Successfully update product!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response([
                'status'        => false,
                'data'          => $product,
                'message'       => 'Product not found!'
            ]);
        }

        if (!empty($product->image)) {
            $filePath = public_path('products/') . $product->image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $product->delete();
        return response([
            'status'        => true,
            'data'          => $product,
            'message'       => 'Successfully delete product'
        ]);
    }

    public function searchBySubcategory($id)
    {
        $products = Product::with('category', 'sub_category')->where('sub_category_id', $id)->get();

        // foreach ($products as $product) {
        //     $product['image'] = asset('products/' . $product->image);
        // }

        return response()->json([
            'status'        => true,
            'data'          => $products,
            'message'       => 'OK'
        ]);
    }

    public function searchByCategory($id)
    {
        $products = Product::with('category', 'sub_category')->where('category_id', $id)->get();

        // foreach ($products as $product) {
        //     $product['image'] = asset('products/' . $product->image);
        // }

        return response()->json([
            'status'        => true,
            'data'          => $products,
            'message'       => 'OK'
        ]);
    }

    public function searchByName($name)
    {
        $products = Product::with('category', 'sub_category')->where('name', 'like', '%' . $name . '%')->get();

        // foreach ($products as $product) {
        //     $product['image'] = asset('products/' . $product->image);
        // }

        return response()->json([
            'status'        => true,
            'data'          => $products,
            'message'       => 'OK'
        ]);
    }
}
