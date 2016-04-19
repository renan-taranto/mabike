<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadPerformedOilChangeData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use DateTime;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Symfony\Component\HttpFoundation\Response;
use Tests\DeleteRequestImpl;
use Tests\JsonGetRequest;
use Tests\JsonPatchRequest;
use Tests\JsonPostRequest;

class OilchangeControllerTest extends WebTestCase
{
    private $fixtures;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            LoadUserTestingData::class,
            LoadMotorcycleTestingData::class,
            LoadPerformedOilChangeData::class
        ));
    }
    
    public function testPostReturnsOilChangeRepresentation()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $motorcycleId = 1;
        $uri = $this->getOilChangesCollectionUri(array('motorcycleId' => $motorcycleId));
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
        $expectedLocationHeader = $this->getOilChangeResourceUri(
            array('motorcycleId' => $motorcycleId, 'performedOilChangeId' => $content['id'])
        );
        $this->assertEquals($expectedLocationHeader, $returnedLocationHeader);
    }
    
    public function testPostInvalidKmsReturnsBadRequest()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $uri = $this->getOilChangesCollectionUri(array('motorcycleId' => 1));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $data = array('kms_driven' => -1);
        
        $postRequest->post($uri, $data, $apiKey);
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('This value should be 0 or more.',
            $content['errors']['kms_driven'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testPostBlankParametersCreatesOilChangeWithCurrentDateAndCurrentKmsDriven()
    {
        $motorcycleId = 1;
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $uri = $this->getOilChangesCollectionUri(array('motorcycleId' => $motorcycleId));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $data = array();
        
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);

        $expectedDate = new \DateTime('now');
        $expectedDate = $expectedDate->format('Y-m-d');
        $expectedKmsDriven = $this->fixtures->getReferenceRepository()->getReference('ducati')->getKmsDriven();
        $expectedLocationHeader = $this->getOilChangeResourceUri(
            array('motorcycleId' => $motorcycleId, 'performedOilChangeId' => $content['id'])
        );
        
        $this->assertEquals($expectedKmsDriven, $content['kms_driven']);
        $this->assertEquals($expectedDate, $content['date']);
        $this->assertStatusCode(Response::HTTP_CREATED, $client);
        $returnedLocationHeader = $response->headers->get('Location');
        $this->assertEquals($expectedLocationHeader, $returnedLocationHeader);
    }
    
    public function testCgetReturnsCollection()
    {
        $client = static::createClient();
        $uri = $this->getOilChangesCollectionUri(array('motorcycleId' => 1));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $response = $getRequest->get($uri, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $referenceRepo = $this->fixtures->getReferenceRepository();
        $performedOilChange1 = $referenceRepo->getReference('performed_oil_change_1');
        $performedOilChange2 = $referenceRepo->getReference('performed_oil_change_2');
        $performedOilChange3 = $referenceRepo->getReference('performed_oil_change_3');
        
        $this->assertEquals($performedOilChange1->getId(), $content[0]['id']);
        $this->assertEquals($performedOilChange1->getKmsDriven(), $content[0]['kms_driven']);
        $this->assertEquals($performedOilChange2->getId(), $content[1]['id']);
        $this->assertEquals($performedOilChange2->getKmsDriven(), $content[1]['kms_driven']);
        $this->assertEquals($performedOilChange3->getId(), $content[2]['id']);
        $this->assertEquals($performedOilChange3->getKmsDriven(), $content[2]['kms_driven']);
        
        $this->assertStatusCode(200, $client);
    }
    
    public function testGetReturnsPerformedOilChangeRepresentation()
    {
        
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array('motorcycleId' => 1, 'performedOilChangeId' => 2));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $response = $getRequest->get($uri, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $referenceRepo = $this->fixtures->getReferenceRepository();
        $performedOilChange2 = $referenceRepo->getReference('performed_oil_change_2');
        
        $this->assertEquals($performedOilChange2->getId(), $content['id']);
        $this->assertEquals($performedOilChange2->getKmsDriven(), $content['kms_driven']);
        
        $this->assertStatusCode(200, $client);
    }
    
    public function testGetReturnsNotFound()
    {
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array('motorcycleId' => 1, 'performedOilChangeId' => 1000));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $getRequest->get($uri, $apiKey);
        
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testPatchUpdatesAllProperties()
    {
        $performedOilChangeId = 1; 
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedOilChangeId' => $performedOilChangeId)
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
        $this->assertEquals($performedOilChangeId, $content['id']);
    }
 
    public function testPatchUpdatesOnlyKmsDriven()
    {
        $performedOilChangeId = 1; 
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedOilChangeId' => $performedOilChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $patchRequest = new JsonPatchRequest($client);
        
        $kmsDriven = 1640;
        $data = array('kms_driven' => $kmsDriven);
        
        $referenceRepo = $this->fixtures->getReferenceRepository();
        
        /* @var $performedOilChange1 PerformedOilChange */
        $performedOilChange1 = $referenceRepo->getReference('performed_oil_change_1');
        
        $response = $patchRequest->patch($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertEquals($kmsDriven, $content['kms_driven']);
        $this->assertEquals($performedOilChange1->getDate()->format('Y-m-d'), $content['date']);
        $this->assertEquals($performedOilChange1->getId(), $content['id']);
    }
    
    public function testPatchInvalidKmsDrivenReturnsBadRequest()
    {
        $performedOilChangeId = 1; 
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedOilChangeId' => $performedOilChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $patchRequest = new JsonPatchRequest($client);
        
        $kmsDriven = -1;
        $data = array('kms_driven' => $kmsDriven);
        
        $response = $patchRequest->patch($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
        $this->assertEquals('This value should be 0 or more.',
            $content['errors']['kms_driven'][0]);
    }

    public function testPatchInvalidDateReturnsBadRequest()
    {
        $performedOilChangeId = 1; 
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedOilChangeId' => $performedOilChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $patchRequest = new JsonPatchRequest($client);
        
        $date = 'ivalid date';
        $data = array('date' => $date);
        
        $response = $patchRequest->patch($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
        $this->assertEquals('This value is not a valid date.',
            $content['errors']['date'][0]);
    }
    
    public function testPatchBlankParamsReturnsInalteredResource()
    {
        $performedOilChangeId = 1;
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedOilChangeId' => $performedOilChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $patchRequest = new JsonPatchRequest($client);
        $data = array();
        
        $response = $patchRequest->patch($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $referenceRepo = $this->fixtures->getReferenceRepository();
        /* @var $performedOilChange1 PerformedOilChange */
        $performedOilChange1 = $referenceRepo->getReference('performed_oil_change_1');
        
        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertEquals($performedOilChange1->getKmsDriven(), $content['kms_driven']);
        $this->assertEquals($performedOilChange1->getDate()->format('Y-m-d'), $content['date']);
        $this->assertEquals($performedOilChange1->getId(), $content['id']);
    }
    
    public function testDeleteOilChangeReturnsNoContent()
    {
        $performedOilChangeId = 1;
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedOilChangeId' => $performedOilChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $deleteRequest = new DeleteRequestImpl($client);
        
        $deleteRequest->delete($uri, $apiKey);
        $this->assertStatusCode(Response::HTTP_NO_CONTENT, $client);
        
        $getRequest = new JsonGetRequest($client);
        $getRequest->get($uri, $apiKey);
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testDeleteOilChangeReturnsNotFoundIfMotorcycleDoesntBelongsToBiker()
    {
        $performedOilChangeId = 1;
        $motorcycleId = 1000;
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedOilChangeId' => $performedOilChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $deleteRequest = new DeleteRequestImpl($client);
        
        $deleteRequest->delete($uri, $apiKey);
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testDeleteInexistentOilChangeReturnsNotFound()
    {
        $performedOilChangeId = 1000;
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedOilChangeId' => $performedOilChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $deleteRequest = new DeleteRequestImpl($client);
        
        $deleteRequest->delete($uri, $apiKey);
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testPatchInexistentPerformedOilChangeThrowsException()
    {
        $performedOilChangeId = 1000;
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getOilChangeResourceUri(array(
            'motorcycleId' => $motorcycleId,
            'performedOilChangeId' => $performedOilChangeId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $patchRequest = new JsonPatchRequest($client);
        $data = array();
        
        $patchRequest->patch($uri, $data, $apiKey);
        
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    private function getOilChangesCollectionUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_oilchanges', $params);
    }
    
    private function getOilChangeResourceUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_oilchange', $params);
    }
    
    private function getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles()
    {
        return $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
    }
}
