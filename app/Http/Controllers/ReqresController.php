<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReqresController extends Controller
{
    private $uri;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->uri = "https://reqres.in/api";
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try 
        {
            $response = Http::post($this->uri . "/register", [
                'email' => $request['email'],
                'password' => $request['password'],
            ]);
    
            $statusCode = $response->status();
            return $this->setResponse($statusCode, $response);

        } 
        catch (\Exception $e) 
        {
            return $this->responseApi("error", "Failed to register", null, 409);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try 
        {
            $response = Http::post($this->uri . "/login", [
                'email' => $request['email'],
                'password' => $request['password'],
            ]);
    
            $statusCode = $response->status();
            return $this->setResponse($statusCode, $response);
        } 
        catch (\Exception $e) 
        {
            return $this->responseApi("error", "Failed to login", null, 400);
        }
    }

    private function setResponse($statusCode, $response){
        switch ($statusCode) {
            case 200:
                return $this->responseApi("success", "Data User", json_decode($response->body()), $statusCode);
                break;
            case 400:
                return $this->responseApi("error", "Malformed syntax or a bad query is strange but possible", null, $statusCode);
                break;
            case 401:
                return $this->responseApi("error", "Unauthorized Authentication failure", null, $statusCode);
                break;
            case 403:
                return $this->responseApi("error", "Authorization failure or invalid Application ID", null, $statusCode);
                break;
            case 404:
                return $this->responseApi("error", "Data not found", null, $statusCode);
                break;
            case 405:
                return $this->responseApi("error", "Method Not Allowed", null, $statusCode);
                break;
        }
    }
}
