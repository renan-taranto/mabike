<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadPerformedRearTireChangeData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use DateTime;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\DeleteRequestImpl;
use Tests\JsonGetRequest;
use Tests\JsonPatchRequest;
use Tests\JsonPostRequest;

class RearTireChangeControllerTest extends WebTestCase
{
    private $fixtures;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            LoadUserTestingData::class,
            LoadMotorcycleTestingData::class,
            LoadPerformedRearTireChangeData::class
        ));
    }
    
    public function testPostReturnsCreatedResponseWithRepresentation()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $motorcycleId = 1;
        $uri = $this->getRearTireChangeCollectionUri(array('motorcycleId' => $motorcycleId));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $kmsDriven = 321412;
        $date = new DateTime('2016-04-15');
        $date = $date->format('Y-m-d');
        $data = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals($kmsDriven, $content['kms_driven']);
        $this->assertEquals($date, $content['date']);
        $this->assertStatusCode(Response::HTTP_CREATED, $client);
        $returnedLocationHeader = $response->headers->get('Location');
        $expectedLocationHeader = $this->getRearTireChangeResourceUri(
            array('motorcycleId' => $motorcycleId, 'performedRearTireChangeId' => $content['id'])
        );
        $this->assertEquals($expectedLocationHeader, $returnedLocationHeader);
    }
    
    public function testPostInvalidKmsReturnsBadRequest()
    {
        
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $motorcycleId = 1;
        $uri = $this->getRearTireChangeCollectionUri(array('motorcycleId' => $motorcycleId));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $kmsDriven = -1;
        $date = new DateTime('2016-04-15');
        $date = $date->format('Y-m-d');
        $data = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('This value should be 0 or more.',
            $content['errors']['kms_driven'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testPostInvalidDateReturnsBadRequest()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $motorcycleId = 1;
        $uri = $this->getRearTireChangeCollectionUri(array('motorcycleId' => $motorcycleId));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $kmsDriven = -1;
        $date = 'asdsad';
        $data = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('This value is not a valid date.',
            $content['errors']['date'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testGetReturnsPerformedRearTireRepresentation()
    {
        $client = static::createClient();
        $uri = $this->getRearTireChangeResourceUri(array('motorcycleId' => 1, 'performedRearTireChangeId' => 2));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $response = $getRequest->get($uri, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $referenceRepo = $this->fixtures->getReferenceRepository();
        $performedRearTireChange2 = $referenceRepo->getReference('performed_rear_tire_change_2');
        
        $this->assertEquals($performedRearTireChange2->getId(), $content['id']);
        $this->assertEquals($performedRearTireChange2->getKmsDriven(), $content['kms_driven']);
        $this->assertEquals($performedRearTireChange2->getDate()->format('Y-m-d'), $content['date']);
        
        $this->assertStatusCode(200, $client);
    }
    
    public function testGetReturnsNotFound()
    {
        $client = static::createClient();
        $uri = $this->getRearTireChangeResourceUri(array('motorcycleId' => 1, 'performedRearTireChangeId' => 1000));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $getRequest->get($uri, $apiKey);
        
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testGetPerformedRearTireChangeOfAnotherMotorcycleReturnsNotFound()
    {
        $client = static::createClient();
        $xj6PerformedRearTireChangeId = 4;
        $ducatiId = 1;
        $uri = $this->getRearTireChangeResourceUri(
            array('motorcycleId' => $ducatiId, 'performedRearTireChangeId' => $xj6PerformedRearTireChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $getRequest->get($uri, $apiKey);
        
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testDeletePerformedRearTireChangeReturnsNoContent()
    {
        $performedRearTireChangeId = 1;
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getRearTireChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedRearTireChangeId' => $performedRearTireChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $deleteRequest = new DeleteRequestImpl($client);
        
        $deleteRequest->delete($uri, $apiKey);
        $this->assertStatusCode(Response::HTTP_NO_CONTENT, $client);
        
        $getRequest = new JsonGetRequest($client);
        $getRequest->get($uri, $apiKey);
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testCgetReturnsCollection()
    {
        $client = static::createClient();
        $uri = $this->getRearTireChangeCollectionUri(array('motorcycleId' => 2));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $response = $getRequest->get($uri, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $referenceRepo = $this->fixtures->getReferenceRepository();
        $performedRearTireChange4 = $referenceRepo->getReference('performed_rear_tire_change_4');
        $performedRearTireChange5 = $referenceRepo->getReference('performed_rear_tire_change_5');
        
        $this->assertEquals($performedRearTireChange4->getId(), $content[0]['id']);
        $this->assertEquals($performedRearTireChange4->getKmsDriven(), $content[0]['kms_driven']);
        $this->assertEquals($performedRearTireChange5->getId(), $content[1]['id']);
        $this->assertEquals($performedRearTireChange5->getKmsDriven(), $content[1]['kms_driven']);
        
        $this->assertStatusCode(200, $client);
    }
    
    
    public function testPatchUpdatesAllProperties()
    {
        $performedRearTireChangeId = 1; 
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getRearTireChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedRearTireChangeId' => $performedRearTireChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $patchRequest = new JsonPatchRequest($client);
        
        $kmsDriven = 100;
        $date = '2010-01-10';
        $data = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $response = $patchRequest->patch($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertEquals($kmsDriven, $content['kms_driven']);
        $this->assertEquals($date, $content['date']);
        $this->assertEquals($performedRearTireChangeId, $content['id']);
        
        $referenceRepo = $this->fixtures->getReferenceRepository();
        $performedRearTireChange1 = $referenceRepo->getReference('performed_rear_tire_change_1');
        $this->assertEquals($performedRearTireChange1->getKmsDriven(), $content['kms_driven']);
        $this->assertEquals($performedRearTireChange1->getDate()->format('Y-m-d'), $content['date']);
    }
    
    private function getRearTireChangeCollectionUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_reartire_changes', $params);
    }
    
    private function getRearTireChangeResourceUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_reartire_change', $params);
    }
    
    private function getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles()
    {
        return $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
    }
}
