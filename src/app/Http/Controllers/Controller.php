<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *   title="Lumen API Project",
     *   version="1.0.0",
     *   @OA\Contact(
     *     email="egin066@gmail.com",
     *     name="Ginanjar S.B"
     *   )
     * )
     */

    public function respondWithToken($token)
    {
        return $this->responseApi("success", "Logout success", 
        [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null
        ], 200);
    }

    public function responseApi($status, $message, $data, $httpCode)
    {
        return response()->json([
            'status'    => $status,
            'message'   => $message,
            'data'      => $data
        ], $httpCode);
    }
}
