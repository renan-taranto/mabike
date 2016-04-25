<?php
namespace Tests\Rtaranto\Presentation\Controller;

use DateTime;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\DeleteRequestImpl;
use Tests\JsonGetRequest;
use Tests\JsonPatchRequest;
use Tests\JsonPostRequest;

abstract class BaseTestMaintenanceController extends WebTestCase
{
    private $fixtures;
    
    abstract function getFixtures();
    
    public function setUp()
    {
        $fixtures = $this->getFixtures();
        $this->fixtures = $this->loadFixtures($fixtures);
    }
    
    public function testPostReturnsCreatedResponseWithRepresentation()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $motorcycleId = 1;
        $uri = $this->getResourceCollectionUri(array('motorcycleId' => $motorcycleId));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $kmsDriven = 500;
        $date = new DateTime('2016-04-15');
        $date = $date->format('Y-m-d');
        $data = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals($kmsDriven, $content['kms_driven']);
        $this->assertEquals($date, $content['date']);
        $this->assertStatusCode(Response::HTTP_CREATED, $client);
        $returnedLocationHeader = $response->headers->get('Location');
        $expectedLocationHeader = $this->getResourceUri(
            array('motorcycleId' => $motorcycleId, $this->getSubResourceIdParamNameForGetPath() => $content['id'])
        );
        $this->assertEquals($expectedLocationHeader, $returnedLocationHeader);
    }
    
    public function testPostInvalidKmsReturnsBadRequest()
    {
        
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $motorcycleId = 1;
        $uri = $this->getResourceCollectionUri(array('motorcycleId' => $motorcycleId));
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
        $uri = $this->getResourceCollectionUri(array('motorcycleId' => $motorcycleId));
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
    
    public function testPostKmsGreatherThanCurKmsDrivenReturnsBadRequest()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $motorcycleId = 1;
        $uri = $this->getResourceCollectionUri(array('motorcycleId' => $motorcycleId));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $ducati = $this->fixtures->getReferenceRepository()->getReference('ducati');
        $kmsDriven = $ducati->getKmsDriven() + 1;
        $date = '2016-02-03';
        $bodyParameters = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $response = $postRequest->post($uri, $bodyParameters, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('Kms exceeds current motorcycle '
                . 'kms driven. Update motorcycle kms driven if needed before '
                . 'trying again.',
            $content['errors']['kms_driven'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testGetReturnsPerformedMaintenanceRepresentation()
    {
        $client = static::createClient();
        $uri = $this->getResourceUri(array('motorcycleId' => 1, $this->getSubResourceIdParamNameForGetPath() => 2));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $response = $getRequest->get($uri, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $performedMaintenance2 = $this->getResourceFromReferenceRepository(2);
        
        $this->assertEquals($performedMaintenance2->getId(), $content['id']);
        $this->assertEquals($performedMaintenance2->getKmsDriven(), $content['kms_driven']);
        $this->assertEquals($performedMaintenance2->getDate()->format('Y-m-d'), $content['date']);
        
        $this->assertStatusCode(200, $client);
    }
    
    public function testGetReturnsNotFound()
    {
        $client = static::createClient();
        $uri = $this->getResourceUri(array('motorcycleId' => 1, $this->getSubResourceIdParamNameForGetPath() => 1000));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $getRequest->get($uri, $apiKey);
        
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testGetPerformedMaintenanceOfAnotherMotorcycleReturnsNotFound()
    {
        $client = static::createClient();
        $xj6PerformedMaintenanceId = 4;
        $ducatiId = 1;
        $uri = $this->getResourceUri(
            array('motorcycleId' => $ducatiId, $this->getSubResourceIdParamNameForGetPath() => $xj6PerformedMaintenanceId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $getRequest->get($uri, $apiKey);
        
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testDeletePerformedMaintenanceReturnsNoContent()
    {
        $performedMaintenanceId = 1;
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getResourceUri(array(
            'motorcycleId' => $motorcycleId,
            $this->getSubResourceIdParamNameForGetPath() => $performedMaintenanceId)
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
        $uri = $this->getResourceCollectionUri(array('motorcycleId' => 2));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $getRequest = new JsonGetRequest($client);
        
        $response = $getRequest->get($uri, $apiKey);
        $content = json_decode($response->getContent(), true);

        $performedMaintenance4 = $this->getResourceFromReferenceRepository(4);
        $performedMaintenance5 = $this->getResourceFromReferenceRepository(5);
        
        $this->assertEquals($performedMaintenance4->getId(), $content[0]['id']);
        $this->assertEquals($performedMaintenance4->getKmsDriven(), $content[0]['kms_driven']);
        $this->assertEquals($performedMaintenance5->getId(), $content[1]['id']);
        $this->assertEquals($performedMaintenance5->getKmsDriven(), $content[1]['kms_driven']);
        
        $this->assertStatusCode(200, $client);
    }
    
    public function testPatchUpdatesAllProperties()
    {
        $performedMaintenanceId = 1; 
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getResourceUri(array(
            'motorcycleId' => $motorcycleId,
            $this->getSubResourceIdParamNameForGetPath() => $performedMaintenanceId)
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
        $this->assertEquals($performedMaintenanceId, $content['id']);
        
        $performedMaintenance1 = $this->getResourceFromReferenceRepository(1);
        $this->assertEquals($performedMaintenance1->getKmsDriven(), $content['kms_driven']);
        $this->assertEquals($performedMaintenance1->getDate()->format('Y-m-d'), $content['date']);
    }
    
    public function testPatchInvalidKmsDrivenReturnsBadRequest()
    {
        $performedMaintenanceId = 1; 
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getResourceUri(array(
            'motorcycleId' => $motorcycleId,
            $this->getSubResourceIdParamNameForGetPath() => $performedMaintenanceId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $patchRequest = new JsonPatchRequest($client);
        
        $kmsDriven = -1;
        $date = '2010-01-10';
        $data = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $response = $patchRequest->patch($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('This value should be 0 or more.',
            $content['errors']['kms_driven'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testPatchInvalidDateReturnsBadRequest()
    {
        $performedMaintenanceId = 1; 
        $motorcycleId = 1;
        $client = static::createClient();
        $uri = $this->getResourceUri(array(
            'motorcycleId' => $motorcycleId,
            $this->getSubResourceIdParamNameForGetPath() => $performedMaintenanceId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $patchRequest = new JsonPatchRequest($client);
        
        $kmsDriven = 100;
        $date = 'invalid date';
        $data = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $response = $patchRequest->patch($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('This value is not a valid date.',
            $content['errors']['date'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testPatchKmsGreatherThanCurKmsDrivenReturnsBadRequest()
    {
        $performedMaintenanceId = 1; 
        $client = static::createClient();
        $postRequest = new JsonPatchRequest($client);
        $motorcycleId = 1;
            $uri = $this->getResourceUri(array(
            'motorcycleId' => $motorcycleId,
            $this->getSubResourceIdParamNameForGetPath() => $performedMaintenanceId)
        );
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $ducati = $this->fixtures->getReferenceRepository()->getReference('ducati');
        $kmsDriven = $ducati->getKmsDriven() + 1;
        $bodyParameters = array('kms_driven' => $kmsDriven);
        
        $response = $postRequest->patch($uri, $bodyParameters, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('Kms exceeds current motorcycle '
                . 'kms driven. Update motorcycle kms driven if needed before '
                . 'trying again.',
            $content['errors']['kms_driven'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    protected function getResourceFromReferenceRepository($id)
    {
        $referenceRepo = $this->fixtures->getReferenceRepository();
        $baseName = $this->getReferenceBaseName();
        return $referenceRepo->getReference($baseName . '_' . $id);
    }
    
    abstract protected function getSubResourceIdParamNameForGetPath();

    abstract protected function getReferenceBaseName();
    
    abstract protected function getResourceCollectionUri(array $params = array());
    
    abstract protected function getResourceUri(array $params = array());
    
    protected function getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles()
    {
        return $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
    }
}