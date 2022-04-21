<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // Get All Data


        return response()->json([
            'status'    => 'success',
            'message'   => 'List Data from MongoDB',
            'data'      => []
        ], 200);
    }

    public function show($id) {
        // Find data by slug or id

        return response()->json([
            'status'    => 'success',
            'message'   => 'Show Data from MongoDB',
            'data'      => []
        ], 200);
    }

    public function store(Request $request) {
        // Insert new data

        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|min:6',
            'password' => 'required|string',
            'email' => 'required|email',
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Store Data to MongoDB',
            'data'      => []
        ], 200);
    }

    public function update(Request $request, $id) {
        // Update data by Id

        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|min:6',
            'password' => 'required|string',
            'email' => 'required|email',
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Update Data to MongoDB',
            'data'      => []
        ], 200);
    }

    public function delete($id) {
        // Delete data by Id
        
        return response()->json([
            'status'    => 'success',
            'message'   => 'Delete Data from MongoDB',
            'data'      => []
        ], 200);
    }
}
