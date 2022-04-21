<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function index() {
        return response()->json([
            'status'    => 'success',
            'message'   => 'List Data from Firebase',
            'data'      => []
        ], 200);
    }

    public function show($id) {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Show Data from Firebase',
            'data'      => []
        ], 200);
    }

    public function store(Request $request, $id) {

        $this->validate($request, [
            'title' => 'required|string',
            'author' => 'required|string',
            'qty' => 'required|numeric',
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Store Data to Firebase',
            'data'      => []
        ], 200);
    }

    public function update(Request $request, $id) {

        $this->validate($request, [
            'title' => 'required|string',
            'author' => 'required|string',
            'qty' => 'required|numeric',
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Update Data to Firebase',
            'data'      => []
        ], 200);
    }

    public function delete($id) {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Delete Data from Firebase',
            'data'      => []
        ], 200);
    }
}
