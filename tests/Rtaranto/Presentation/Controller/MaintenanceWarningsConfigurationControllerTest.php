<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonGetRequest;
use Tests\JsonPatchRequest;

class MaintenanceWarningsConfigurationControllerTest extends WebTestCase
{
    private $fixtures;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(LoadUserTestingData::class, LoadMotorcycleTestingData::class));
    }
    
    public function testGetWarningsConfigurationReturnsResourceRepresentation()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $uri = $this->getEndpointUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        $response = $getRequest->get($uri, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $expectedRepresentation = array(
            'is_active' => false,
            'kms_per_oil_change' => null,
            'kms_in_advance' => 0
        );
        
        $this->assertEquals($expectedRepresentation, $content);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testPatchWillReturnPachedResource()
    {
        $client = static::createClient();
        $getRequest = new JsonPatchRequest($client);
        $uri = $this->getEndpointUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        
        $bodyData = array(
            'is_active' => true,
            'kms_per_oil_change' => 1500,
            'kms_in_advance' => 100
        );
        
        $response = $getRequest->patch($uri, $bodyData, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals($bodyData, $content);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    private function getEndpointUri()
    {
        return $this->getUrl(
            'api_v1_get_motorcycle_oilchangewarnings_configuration',
            array('motorcycleId' => 1)
        );
    }
}
