<?php

namespace App\Services;

use GuzzleHttp\Client;

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
}
