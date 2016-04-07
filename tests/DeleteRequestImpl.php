<?php
namespace Tests;

use Symfony\Component\HttpKernel\Client;

class DeleteRequestImpl implements DeleteRequest
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    public function delete($uri, $headers)
    {
        $this->client->request('DELETE', $uri, array(), array(), $headers);
        $response = $this->client->getResponse();
        $this->client->restart();
        return $response;
    }
    
    public function getAuthenticationHeader()
    {
        return array('HTTP_X-AUTH-TOKEN' => 'testuserkey');
    }
}
