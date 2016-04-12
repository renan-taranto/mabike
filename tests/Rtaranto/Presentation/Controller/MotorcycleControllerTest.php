<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadBikerTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonGetRequest;

class MotorcycleControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures(array(LoadUserTestingData::class, LoadBikerTestingData::class));
    }
    
    public function testCgetMotorcyclesReturnsCollection()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $uri = $this->getMotorcyclesEndpointUri();
        $response = $getRequest->get($uri, $getRequest->getStandardHeadersWithAuthenticationforUser1());
        
        $content = json_decode($response->getContent(), true);
        
        $expectedMotorcycle = array('id' => 1, 'model' => 'Ducati Hypermotard 796', 'kms_driven' => 0);

        $this->assertEquals(array($expectedMotorcycle), $content);        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetMotorcyclesForUserWithoutBikerReturnsEmptyArray()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $uri = $this->getMotorcyclesEndpointUri();
        $response = $getRequest->get($uri, $getRequest->getStandardHeadersWithAuthenticationforUser2());
        
        $content = json_decode($response->getContent(), true);
        
        $expectedMotorcycle = array();

        $this->assertEquals($expectedMotorcycle, $content);        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetMotorcyclesForBikerWithoutMotorcyclesReturnsEmptyArray()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $uri = $this->getMotorcyclesEndpointUri();
        $response = $getRequest->get($uri, $getRequest->getStandardHeadersWithAuthenticationforUser3());
        
        $content = json_decode($response->getContent(), true);
        
        $expectedMotorcycle = array();

        $this->assertEquals($expectedMotorcycle, $content);        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    private function getMotorcyclesEndpointUri()
    {
        return $this->getUrl('api_v1_get_motorcycles');
    }
}
