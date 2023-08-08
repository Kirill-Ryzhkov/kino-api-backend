<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ApiService
{
    private Client $client;
    private array $headers = [];
    public function __construct()
    {
        $this->client = new Client();
        $this->headers = [
            'X-RapidAPI-Host' => env('API_NETFLIX_HOST'),
            'X-RapidAPI-Key' => env('API_NETFLIX_KEY'),
        ];
    }

    public function getData($url)
    {
        $options = [
            'headers' => $this->headers
        ];
        $response = $this->client->request('GET', $url, $options);
        return json_decode($response->getBody()->getContents());
    }

    public function cache($name, $url, $httpParams = '')
    {
        $cacheData = cache()->get('movies' . $httpParams);
        if (is_null($cacheData)) {
            $cacheData = $this->getData($url . $httpParams);
            cache()->set('movies' . $httpParams, $cacheData, env('DEFAULT_EXPIRATION_TIME'));
        }
        return $cacheData;
    }

    public function getListOfMovies(Request $request)
    {
        $params = [
            'query' => $request->get('query', '*'),
            'offset' => $request->get('offset', 0),
            'limit_titles' => $request->get('limit', 50),
            'limit_suggestions' => $request->get('limit', 10),
            'lang' => $request->get('lang', 'en')
        ];
        return $this->cache('movies', 'https://netflix54.p.rapidapi.com/search/?', http_build_query($params));
    }
    public function getMovieById(Request $request, $id)
    {
        $params = [
            'lang' => $request->get('lang', 'en'),
            'ids' => $id
        ];
        return $this->cache('movie', 'https://netflix54.p.rapidapi.com/title/details/?', http_build_query($params));
    }

    public function validateLang(Request $request)
    {
        return $request->validate([
            'lang' => ['string', Rule::in(['en', 'fr', 'ar', 'ru', 'es'])]
        ]);
    }

    public function validateData(Request $request)
    {
        return $request->validate([
            'query' => ['string'],
            'offset' => ['integer'],
            'limit' => ['integer'],
            'lang' => ['string', Rule::in(['en', 'fr', 'ar', 'ru', 'es'])]
        ]);
    }
}
