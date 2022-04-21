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

    /**
     * @OA\Get(
     *      path="/api/firebase",
     *      operationId="showAllBooks",
     *      tags={"Books"},
     *      summary="Show all data book",
     *      description="Returns all data Book",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *      )
     *     )
     */
    public function index()
    {
        $data = $this->database->getReference('books')->getValue();

        return response()->json([
            'status'    => 'success',
            'message'   => 'List Data from Firebase',
            'data'      => $data
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/firebase/{slug}",
     *      operationId="showBookBySlug",
     *      tags={"Books"},
     *      summary="Show a book",
     *      description="Returns a Book",
     *      @OA\Parameter(
     *          parameter="slug",
     *          name="slug",
     *          @OA\Schema(
     *             type="string"
     *          ),
     *          in="path",
     *          required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *      )
     *     )
     */
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

    /**
     * @OA\Post(
     *      path="/api/firebase",
     *      operationId="createNewBook",
     *      tags={"Books"},
     *      summary="Create a book",
     *      description="Returns message created",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title","author","qty"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="author",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="qty",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *      )
     *     )
     */
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

    /**
     * @OA\Patch(
     *      path="/api/firebase/update/{slug}",
     *      operationId="updateBookById",
     *      tags={"Books"},
     *      summary="Update a book",
     *      description="Returns message updated",
     *      @OA\Parameter(
     *          parameter="slug",
     *          name="slug",
     *          @OA\Schema(
     *             type="string"
     *          ),
     *          in="path",
     *          required=true
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title","author","qty"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="author",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="qty",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *      )
     *     )
     */
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

    /**
     * @OA\Delete(
     *      path="/api/firebase/{slug}",
     *      operationId="deleteBySlug",
     *      tags={"Books"},
     *      summary="Delete a book",
     *      description="Returns message delete",
     *      @OA\Parameter(
     *          parameter="slug",
     *          name="slug",
     *          @OA\Schema(
     *             type="string"
     *          ),
     *          in="path",
     *          required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *      )
     *     )
     */
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
