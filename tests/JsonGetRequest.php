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
    
    public function get($uri, $headers)
    {
        $this->client->request('GET', $uri, array(), array(), $headers);
        $response = $this->client->getResponse();
        $this->client->restart();
        return $response;
    }
    
    public function getStandardHeaders()
    {
        return array('HTTP_ACCEPT' => 'application/json');
    }
    
    public function getStandardHeadersWithAuthenticationforUser1()
    {
        return array('HTTP_ACCEPT' => 'application/json', 'HTTP_X-AUTH-TOKEN' => 'testuserkey');
    }

    public function getStandardHeadersWithAuthenticationforUser2()
    {
        return array('HTTP_ACCEPT' => 'application/json', 'HTTP_X-AUTH-TOKEN' => 'testuser2key');
    }
    
    public function getStandardHeadersWithAuthenticationforUser3()
    {
        return array('HTTP_ACCEPT' => 'application/json', 'HTTP_X-AUTH-TOKEN' => 'testuser3key');
    }

}