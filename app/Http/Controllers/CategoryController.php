<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
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
        $categories = Category::all();
        return response([
            'status' => true,
            'message' => 'Successfully get all categories!',
            'data'  => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'      => 'required',
            'status'    => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = new Category;
        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();

        return response()->json([
            'status'    => true,
            'data'      => $category,
            'message'   => 'Successfully create category!'  
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return response([
                'status'        => true,
                'data'          => $category,
                'message'       => 'Category not found'
            ]); 
        }

        return response([
            'status'        => true,
            'data'          => $category,
            'message'       => 'OK'
        ]);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name'      => 'required',
            'status'    => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::find($id);
        if ($category == null) {
            return response([
                'status'        => false,
                'data'          => $category,
                'message'       => 'Category not found!'
            ]);
        }

        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();

        return response()->json([
            'status'        => true,
            'data'          => $category,
            'message'       => 'Successfully update category!'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response([
                'status'        => false,
                'data'          => $category,
                'message'       => 'Category not found!'
            ]); 
        }

        $category->delete();
        return response([
            'status'        => true,
            'data'          => $category,
            'message'       => 'Successfully delete category'
        ]); 
    }
}
