<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class FirebaseController extends Controller
{
    private $firebase;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        $data = $this->database->getReference('books')->getValue();

        return response()->json([
            'status'    => 'success',
            'message'   => 'List Data from Firebase',
            'data'      => $data
        ], 200);
    }

    public function show($slug) {
        $data = $this->database->getReference('books/' . $slug)->getValue();

        if(!$data) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Not Found',
                'data'      => null
            ], 404);
        }
        
        return response()->json([
            'status'    => 'success',
            'message'   => 'Show Data from Firebase',
            'data'      => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'author' => 'required|string',
            'qty' => 'required|numeric',
        ]);

        try 
        {
            $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $request['title']));
            $data = $this->database
                            ->getReference('books/' . $slug)
                            ->set([
                                'title' => $request['title'],
                                'author' => $request['author'],
                                'qty' => intval($request['qty']),
                            ]);
            $data = $data->getValue();

            return $this->responseApi("success", "Book stored successful", $data, 201);

        } 
        catch (\Exception $e) 
        {
            return $this->responseApi("error", "Failed to stored data", null, 409);
        }
    }

    public function update(Request $request, $slug)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'author' => 'required|string',
            'qty' => 'required|numeric',
        ]);

        try 
        {
            $findData = $this->database->getReference('books/' . $slug)->getValue();

            if(!$findData) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Not Found',
                    'data'      => null
                ], 404);
            }

            $data = $this->database
                            ->getReference('books/' . $slug)
                            ->update([
                                'title/' => $request['title'],
                                'author/' => $request['author'],
                                'qty/' => intval($request['qty']),
                            ]);
            $data = $data->getValue();

            return $this->responseApi("success", "Book updated successful", $data, 201);

        } 
        catch (\Exception $e) 
        {
            return $this->responseApi("error", "Failed to update data", null, 409);
        }
    }

    public function delete($slug)
    {
        $findData = $this->database->getReference('books/' . $slug)->getValue();

        if(!$findData) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Not Found',
                'data'      => null
            ], 404);
        }

        $this->database->getReference('books/' . $slug)->remove();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Delete Data from Firebase',
            'data'      => null
        ], 200);
    }
}
