<?php

namespace App\Http\Controllers;

use App\Subcategory;
use Illuminate\Http\Request;
use Validator;

class SubcategoryController extends Controller
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
        $subcategories = Subcategory::all();
        // $subcategories = Subcategory::with('category')->get();
        return response([
            'status' => true,
            'message' => 'Successfully get all subcategories!',
            'data'  => $subcategories
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
            'name'          => 'required',
            'category_id'   => 'required',
            'status'        => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $subcategory = new Subcategory();
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        $subcategory->status = $request->status;
        $subcategory->save();

        return response()->json([
            'status'    => true,
            'data'      => $subcategory,
            'message'   => 'Successfully create subcategory!'  
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subcategory = Subcategory::find($id);
        if ($subcategory == null) {
            return response([
                'status'        => true,
                'data'          => $subcategory,
                'message'       => 'Subcategory not found!'
            ]); 
        }

        return response([
            'status'        => true,
            'data'          => $subcategory,
            'message'       => 'OK'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Subcategory $subcategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name'          => 'required',
            'category_id'   => 'required',
            'status'        => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $subcategory = Subcategory::find($id);
        if ($subcategory == null) {
            return response([
                'status'        => false,
                'data'          => $subcategory,
                'message'       => 'Subcategory not found!'
            ]);
        }

        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        $subcategory->status = $request->status;
        $subcategory->save();

        return response()->json([
            'status'        => true,
            'data'          => $subcategory,
            'message'       => 'Successfully update subcategory!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcategory = Subcategory::find($id);
        if (!$subcategory) {
            return response([
                'status'        => false,
                'data'          => $subcategory,
                'message'       => 'Subcategory not found!'
            ]); 
        }

        $subcategory->delete();
        return response([
            'status'        => true,
            'data'          => $subcategory,
            'message'       => 'Successfully delete subcategory!'
        ]); 
    }
}
