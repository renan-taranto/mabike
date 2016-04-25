<?php
namespace Tests;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class JsonPatchRequest implements PatchRequest
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * @param string $uri
     * @param array $headers
     * @param array $bodyData
     * @return Response
     */
    public function patch($uri, array $bodyData = null, $apiKey = null, array $headers = null)
    {
        $headers = JsonHeadersFactory::createHeaders($headers, $apiKey);
        $this->client->request('PATCH', $uri, array(), array(), $headers, json_encode($bodyData));
        $response = $this->client->getResponse();
        $this->client->restart();
        return $response;
    }
}
