<?php
namespace Tests;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class JsonPostRequest implements PostRequest
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * @param string $uri
     * @param array $bodyParameters
     * @param string $apiKey
     * @param array $headers
     * @return Response
     */
    public function post($uri, array $bodyParameters = null, $apiKey = null, array $headers = null)
    {
        $headers = JsonHeadersFactory::createHeaders($headers, $apiKey);
        $this->client->request('POST', $uri, array(), array(), $headers, json_encode($bodyParameters));
        $response = $this->client->getResponse();
        $this->client->restart();
        return $response;
    }
}
