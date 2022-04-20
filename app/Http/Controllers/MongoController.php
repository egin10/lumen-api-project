<?php

namespace App\Http\Controllers;

class MongoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index() {
        return response()->json([
            'status'    => 'success',
            'message'   => 'List Data from MongoDB',
            'data'      => []
        ], 200);
    }

    public function show($slug) {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Show Data from MongoDB',
            'data'      => []
        ], 200);
    }

    public function store($slug) {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Store Data to MongoDB',
            'data'      => []
        ], 200);
    }

    public function update($slug) {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Update Data to MongoDB',
            'data'      => []
        ], 200);
    }

    public function delete($slug) {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Delete Data from MongoDB',
            'data'      => []
        ], 200);
    }
}
