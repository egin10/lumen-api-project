<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MongoController extends Controller
{
    private $collection;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $client = new \MongoDB\Client(
            'mongodb+srv://test:test@lumen.fcevy.mongodb.net/lumen?retryWrites=true&w=majority');
        $collection = $client->lumen->toys;

        $this->collection = $collection;
    }

    /**
     * @OA\Get(
     *      path="/api/mongo-toys",
     *      operationId="showAllToys",
     *      tags={"Toys"},
     *      summary="Show all data books",
     *      description="Returns all data Toys",
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
    public function index() {
        $data = $this->collection->find()->toArray();

        return $this->responseApi("success", "List of Toys", $data, 200);
    }

    /**
     * @OA\Get(
     *      path="/api/mongo-toys/{slug}",
     *      operationId="showToyBySlug",
     *      tags={"Toys"},
     *      summary="Show a toy",
     *      description="Returns a toy",
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
        $data = $this->collection->findOne(['slug' => $slug]);

        if(!$data) return $this->responseApi("error", "Data not found", null, 404);

        return $this->responseApi("success", "Data of Toys", $data, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/mongo-toys",
     *      operationId="createNewToy",
     *      tags={"Toys"},
     *      summary="Create a toy",
     *      description="Returns message created",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"toy_name","price","qty"},
     *                 @OA\Property(
     *                     property="toy_name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="integer",
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
    public function store(Request $request) {
        $this->validate($request, [
            'toy_name' => 'required|string',
            'price' => 'required|numeric',
            'qty' => 'required|numeric',
        ]);

        try 
        {
            $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $request['toy_name']));
            $newData = [
                'slug' => $slug,
                'toy_name' => $request['toy_name'],
                'price' => $request['price'],
                'qty' => intval($request['qty']),
            ];
            $data = $this->collection->insertOne($newData);
            
            if($data->getInsertedCount() > 0) return $this->responseApi("success", "Toy stored successful", $newData, 201);

        } 
        catch (\Exception $e) 
        {
            return $this->responseApi("error", "Failed to stored data", null, 409);
        }
    }

    /**
     * @OA\Patch(
     *      path="/api/mongo-toys/{slug}",
     *      operationId="updateToyById",
     *      tags={"Toys"},
     *      summary="Update a toy",
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
     *                 required={"toy_name","price","qty"},
     *                 @OA\Property(
     *                     property="toy_name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="integer",
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
    public function update(Request $request, $slug) {
        $this->validate($request, [
            'toy_name' => 'required|string',
            'price' => 'required|numeric',
            'qty' => 'required|numeric',
        ]);

        try 
        {
            $newData = [
                'slug' => $slug,
                'toy_name' => $request['toy_name'],
                'price' => $request['price'],
                'qty' => intval($request['qty']),
            ];
            
            $findOne = $this->collection->findOne(['slug' => $slug]);
            
            if(!$findOne) return $this->responseApi("error", "Data not found", null, 404);

            $data = $this->collection->updateOne(['slug' => $slug], ['$set' => $newData]);
            
            if($data->getModifiedCount() > 0) return $this->responseApi("success", "Toy updated successful", $newData, 200);

        } 
        catch (\Exception $e) 
        {
            return $this->responseApi("error", "Failed to updated data", null, 409);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/mongo-toys/{slug}",
     *      operationId="deleteToyBySlug",
     *      tags={"Toys"},
     *      summary="Delete a toy",
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
    public function delete($slug) {
        try 
        {   
            $findOne = $this->collection->findOne(['slug' => $slug]);
            
            if(!$findOne) return $this->responseApi("error", "Data not found", null, 404);

            $data = $this->collection->deleteOne(['slug' => $slug]);
            
            if($data->getDeletedCount() > 0) return $this->responseApi("success", "Toy deleted", null, 200);

        } 
        catch (\Exception $e) 
        {
            return $this->responseApi("error", "Failed to updated data", null, 409);
        }
    }
}
