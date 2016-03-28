<?php
namespace Tests;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class JsonPutRequest implements PutRequest
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
    public function put($uri, $headers, array $data)
    {
        $this->client->request('PUT', $uri, array(), array(), $headers, json_encode($data));
        $response = $this->client->getResponse();
        $this->client->restart();
        return $response;
    }
    
    public function getStandardHeaders()
    {
        return array('CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json');
    }
    
    public function getStandardHeadersWithAuthentication()
    {
        return array('CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json', 'HTTP_X-AUTH-TOKEN' => 'testuserkey');
    }
}
