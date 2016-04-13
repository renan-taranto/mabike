<?php
namespace Tests;

use Symfony\Component\HttpKernel\Client;

class JsonGetRequest implements GetRequest
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    public function get($uri, $apiKey = null, $headers = null)
    {
        $headers = JsonHeadersFactory::createHeaders($headers, $apiKey);
        $this->client->request('GET', $uri, array(), array(), $headers);
        $response = $this->client->getResponse();
        $this->client->restart();
        return $response;
    }
}