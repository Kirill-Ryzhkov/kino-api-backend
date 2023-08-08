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
        $request->validate([
            'query' => ['string'],
            'offset' => ['integer'],
            'limit' => ['integer'],
            'lang' => ['string', Rule::in(['en', 'fr', 'ar', 'ru', 'es'])]
        ]);

        $params = [
            'query' => $request->get('query', '*'),
            'offset' => $request->get('offset', 0),
            'limit_titles' => $request->get('limit', 50),
            'limit_suggestions' => $request->get('limit', 10),
            'lang' => $request->get('lang', 'en')
        ];

        return response()->json($this->service->cache('movies', 'https://netflix54.p.rapidapi.com/search/?', http_build_query($params)));
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
        $request->validate([
            'lang' => ['string', Rule::in(['en', 'fr', 'ar', 'ru', 'es'])]
        ]);

        $params = [
            'lang' => $request->get('lang', 'en'),
            'ids' => $id
        ];

        return response()->json($this->service->cache('movie', 'https://netflix54.p.rapidapi.com/title/details/?', http_build_query($params)));
    }
}
