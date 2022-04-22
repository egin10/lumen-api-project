<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;

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
     *      path="/api/firebase-books",
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

        return $this->responseApi("success", "List of Books", $data, 200);
    }

    /**
     * @OA\Get(
     *      path="/api/firebase-books/{slug}",
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

        if(!$data) return $this->responseApi("error", "Book not found", null, 404);
        
        return $this->responseApi("success", "Data of Book", $data, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/firebase-books",
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
     *      path="/api/firebase-books/{slug}",
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

            if(!$findData) return $this->responseApi("error", "Book not found", null, 404);

            $data = $this->database
                            ->getReference('books/' . $slug)
                            ->update([
                                'title/' => $request['title'],
                                'author/' => $request['author'],
                                'qty/' => intval($request['qty']),
                            ]);
            $data = $data->getValue();

            return $this->responseApi("success", "Book updated successful", $data, 200);

        } 
        catch (\Exception $e) 
        {
            return $this->responseApi("error", "Failed to update data", null, 409);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/firebase-books/{slug}",
     *      operationId="deleteBookBySlug",
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
        try 
        {   
            $findData = $this->database->getReference('books/' . $slug)->getValue();

            if(!$findData) return $this->responseApi("error", "Book not found", null, 404);

            $this->database->getReference('books/' . $slug)->remove();

            return $this->responseApi("success", "Book deleted", null, 200);
        } 
        catch (\Exception $e) 
        {
            return $this->responseApi("error", "Failed to updated data", null, 409);
        }
    }
}
