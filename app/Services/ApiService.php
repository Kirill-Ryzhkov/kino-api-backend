<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

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
}
