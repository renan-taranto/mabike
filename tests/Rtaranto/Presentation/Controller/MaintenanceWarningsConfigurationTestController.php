<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonGetRequest;
use Tests\JsonPatchRequest;

abstract class MaintenanceWarningsConfigurationTestController extends WebTestCase
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
            'kms_per_maintenance' => null,
            'kms_in_advance' => 0
        );
        
        $this->assertEquals($expectedRepresentation['is_active'], $content['is_active']);
        $this->assertEquals($expectedRepresentation['kms_per_maintenance'], $content['kms_per_maintenance']);
        $this->assertEquals($expectedRepresentation['kms_in_advance'], $content['kms_in_advance']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testPatchWillReturnPachedResource()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $uri = $this->getEndpointUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        
        $bodyData = array(
            'is_active' => true,
            'kms_per_maintenance' => 1500,
            'kms_in_advance' => 100
        );
        
        $response = $patchRequest->patch($uri, $bodyData, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals($bodyData['is_active'], $content['is_active']);
        $this->assertEquals($bodyData['kms_per_maintenance'], $content['kms_per_maintenance']);
        $this->assertEquals($bodyData['kms_in_advance'], $content['kms_in_advance']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testPatchPartialUpdateWillReturnPachedResource()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $uri = $this->getEndpointUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        
        $bodyData = array(
            'kms_per_maintenance' => 1680,
            'kms_in_advance' => 10
        );
        
        $response = $patchRequest->patch($uri, $bodyData, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $expectedBodyData = array(
            'is_active' => false,
            'kms_per_maintenance' => 1680,
            'kms_in_advance' => 10
        );

        $this->assertEquals($expectedBodyData['is_active'], $content['is_active']);
        $this->assertEquals($expectedBodyData['kms_per_maintenance'], $content['kms_per_maintenance']);
        $this->assertEquals($expectedBodyData['kms_in_advance'], $content['kms_in_advance']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testPatchInvalidKmsPerMaintenanceReturnsBadRequest()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $uri = $this->getEndpointUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        
        $bodyData = array(
            'kms_per_maintenance' => -1
        );
        
        $response = $patchRequest->patch($uri, $bodyData, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('This value should be 1 or more.', $content['errors']['kms_per_maintenance'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    
    public function testPatchInvalidKmsInAdvanceReturnsBadRequest()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $uri = $this->getEndpointUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        
        $bodyData = array(
            'kms_in_advance' => -1
        );
        
        $response = $patchRequest->patch($uri, $bodyData, $apiKey);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals('This value should be 0 or more.', $content['errors']['kms_in_advance'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testPatchInvalidKmsInAdvanceAndKmsPerMaintenanceReturnsBadRequest()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $uri = $this->getEndpointUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        
        $bodyData = array(
            'kms_per_maintenance' => -1,
            'kms_in_advance' => -1
        );
        
        $response = $patchRequest->patch($uri, $bodyData, $apiKey);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals('This value should be 1 or more.', $content['errors']['kms_per_maintenance'][0]);
        $this->assertEquals('This value should be 0 or more.', $content['errors']['kms_in_advance'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    abstract protected function getEndpointUri();
}
