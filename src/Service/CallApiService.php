<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client=$client;
    }
    
    public function getMeteoData($lat,$long){
        $response = $this->client->request(
            'GET',
            "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$long&current_weather=true&timezone=Europe%2FBerlin"
        );
        return $response->toArray()['current_weather']['temperature'];
    }
}