<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApiController extends Controller
{
    private ApiService $service;    
    public function __construct()
    {
        $this->service = new ApiService();
    }
    
    /**
     * method: GET
     * description: get list of movies
     * api: /api/movies
     * params: Request $request(string query, integer offset, integer limit, string lang)
     * response: JsonResponse
     */
    public function getMovies(Request $request)
    {   
        $this->service->validateData($request);
        return response()->json($this->service->getListOfMovies($request));
    }

    /**
     * method: GET
     * description: get a specific movie
     * api: /api/movie/{id}
     * params:  integer id,
     *          Request $request(string lang)
     * response: JsonResponse
     */
    public function getMovie($id, Request $request)
    {
        $this->service->validateLang($request);
        return response()->json($this->service->getMovieById($request, $id));
    }
}
