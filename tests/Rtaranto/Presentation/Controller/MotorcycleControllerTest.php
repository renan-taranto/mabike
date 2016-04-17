<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\DeleteRequestImpl;
use Tests\JsonGetRequest;
use Tests\JsonPatchRequest;
use Tests\JsonPostRequest;

class MotorcycleControllerTest extends WebTestCase
{
    private $fixtures;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(LoadUserTestingData::class, LoadMotorcycleTestingData::class));
    }
    
    public function testCgetMotorcyclesReturnsCollection()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $uri = $this->getMotorcyclesCollectionUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        $response = $getRequest->get($uri, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $expectedMotorcycle = array('id' => 1, 'model' => 'Ducati Hypermotard 796', 'kms_driven' => 1560);

        $this->assertEquals(array($expectedMotorcycle), $content);        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetMotorcyclesForUserWithoutBikerRoleReturnsForbidden()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $uri = $this->getMotorcyclesCollectionUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('user')->getApiKey();
        $response = $getRequest->get($uri, $apiKey);
        
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
    
    public function testCgetMotorcyclesForBikerWithoutMotorcyclesReturnsEmptyArray()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $uri = $this->getMotorcyclesCollectionUri();
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_2')->getApiKey();
        $response = $getRequest->get($uri, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $expectedMotorcycle = array();

        $this->assertEquals($expectedMotorcycle, $content);        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testPostMotorcyclesReturnsCreatedResponse()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $uri = $this->getMotorcyclesCollectionUri();
        $apiKey = $this->getApiKeyForUserWithBikerRole();
        $model = 'CRF 450';
        $kmsDriven = 321412;
        $data = array('model' => $model, 'kms_driven' => $kmsDriven);
        
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals($model, $content['model']);
        $this->assertEquals($kmsDriven, $content['kms_driven']);
        $this->assertStatusCode(Response::HTTP_CREATED, $client);
        $returnedLocationHeader = $response->headers->get('Location');
        $expectedLocationHeader = $this->getUrl('api_v1_get_motorcycle', array('id' => 2));
        $this->assertEquals($expectedLocationHeader, $returnedLocationHeader);
        
    }
    
    public function testPostMotorcycleWithEmptyModelReturnsBadRequest()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $uri = $this->getMotorcyclesCollectionUri();
        $apiKey = $this->getApiKeyForUserWithBikerRole();
        $data = array('model' => '');
        
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('This value should not be blank.',
            $content['errors']['model'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testPostMotorcycleWithInvalidValuesReturnsBadRequest()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $uri = $this->getMotorcyclesCollectionUri();
        $apiKey = $this->getApiKeyForUserWithBikerRole();
        $data = array('model' => '1', 'kms_driven' => -1);
        
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals('This value is too short. It should have 2 characters or more.',
            $content['errors']['model'][0]);
        $this->assertEquals('This value should be 0 or more.',
            $content['errors']['kms_driven'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testGetReturnsMotorcycleRepresentation()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $id = 1;
        $uri = $this->getMotorcyclesCollectionUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $response = $getRequest->get($uri, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertArrayHasKey('model', $content[0]);
        $this->assertArrayHasKey('kms_driven', $content[0]);
    }
    
    public function testGetReturnsNotFound()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $id = 100;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $response = $getRequest->get($uri, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
        $this->assertArrayHasKey('code', $content);
        $this->assertArrayHasKey('message', $content);
    }
    
    public function testSuccessfullyDeleteReturnsNoContent()
    {
        $client = static::createClient();
        $deleteRequest = new DeleteRequestImpl($client);
        $id = 1;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $deleteRequest->delete($uri, $apiKey);
        
        $this->assertStatusCode(Response::HTTP_NO_CONTENT, $client);
    }
    
    public function testDeleteReturnsNotFound()
    {
        $client = static::createClient();
        $deleteRequest = new DeleteRequestImpl($client);
        $id = 100;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $deleteRequest->delete($uri, $apiKey);
        
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    public function testPatchUpdatesAllProperties()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $id = 1;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $model = 'patched model';
        $kmsDriven = 21231;
        $data = array('model' => $model, 'kms_driven'=> $kmsDriven);
        $response = $patchRequest->patch($uri, $data, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expectedContent = array_merge(array('id' => $id), $data);
        
        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertEquals($expectedContent, $content);
    }
    
    public function testPatchUpdatesKmsDriven()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $id = 1;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $kmsDriven = 12343;
        $data = array('kms_driven' => $kmsDriven);
        $response = $patchRequest->patch($uri, $data, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $model = 'Ducati Hypermotard 796';
        $expectedContent = array('id' => $id, 'model' => $model, 'kms_driven' => $kmsDriven);
        
        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertEquals($expectedContent, $content);
    }
    
    public function testPatchUpdatesModel()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $id = 1;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $model = 'new model';
        $data = array('model' => $model);
        $response = $patchRequest->patch($uri, $data, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $expectedContent = array('id' => $id, 'model' => $model, 'kms_driven' => 1560);
        
        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertEquals($expectedContent, $content);
    }
    
    public function testPatchInvalidKmsDrivenReturnsBadRequest()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $id = 1;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $kmsDriven = -12343;
        $data = array('kms_driven' => $kmsDriven);
        $response = $patchRequest->patch($uri, $data, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
        $this->assertEquals('This value should be 0 or more.',
            $content['errors']['kms_driven'][0]);
    }
    
    public function testPatchInvalidModelReturnsBadRequest()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $id = 1;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $model = '1';
        $data = array('model' => $model);
        $response = $patchRequest->patch($uri, $data, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $expectedContent = array('id' => $id, 'model' => $model, 'kms_driven' => 1560);
        
        $this->assertEquals('This value is too short. It should have 2 characters or more.',
            $content['errors']['model'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
        
    }
    
    public function testPatchBlankDataReturnsUnchangedResource()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $id = 1;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $data = array();
        $response = $patchRequest->patch($uri, $data, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $expectedContent = array('id' => $id, 'model' => 'Ducati Hypermotard 796', 'kms_driven' => 1560);
        
        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertEquals($expectedContent, $content);
    }
    
    public function testPatchReturnsNotFound()
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $id = 10000;
        $uri = $this->getMotorcycleResourceUri(array('id' => $id));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $data = array();
        $response = $patchRequest->patch($uri, $data, $apiKey);
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
    
    private function getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles()
    {
        return $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
    }
    
    private function getApiKeyForUserWithBikerRole()
    {
        return $this->fixtures->getReferenceRepository()->getReference('biker_user_2')->getApiKey();
    }
    
    private function getMotorcyclesCollectionUri()
    {
        return $this->getUrl('api_v1_get_motorcycles');
    }
    
    private function getMotorcycleResourceUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle', $params);
    }
}
