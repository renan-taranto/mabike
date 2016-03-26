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
     * @param array $headers
     * @param string $data
     * @return Response
     */
    public function post($uri, $headers, array $data = null)
    {
        $this->client->request('POST', $uri, array(), array(), $headers, json_encode($data));
        $response = $this->client->getResponse();
        $this->client->restart();
        return $response;
    }
    
    public function getStandardHeaders()
    {
        return array('CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json');
    }
}
