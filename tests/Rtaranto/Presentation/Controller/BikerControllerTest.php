<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\DeleteRequestImpl;
use Tests\JsonGetRequest;
use Tests\JsonPatchRequest;

class BikerControllerTest extends WebTestCase
{
    private $fixtures;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(LoadUserTestingData::class, LoadMotorcycleTestingData::class));
    }
   
    public function testGetReturnsBiker()
    {
        $client = static::createClient();
        
        $getRequest = new JsonGetRequest($client);
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri() . '/1', $apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expected = array('id' => 1, 'name' => 'Test Biker', 'email' => 'testbiker@email.com');
        $this->assertEquals($expected, $content);
        
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = array('PATCH', 'DELETE', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testGetReturnsNotFoundException()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $id = 123124213;
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri() . '/' . $id, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $expectedContent = array(
            'code' => Response::HTTP_NOT_FOUND,
            'message' => "The Biker resource of id '" . $id . "' was not found.");
        $this->assertEquals($expectedContent, $content);
        
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = array('OPTIONS', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
        
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikers()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri(), $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $expectedBiker1 = array('id' => 1, 'name' => 'Test Biker', 'email' => 'testbiker@email.com');
        $expectedBiker2 = array('id' => 2, 'name' => 'Test Biker2', 'email' => 'testbiker2@email.com');
        $this->assertEquals(array($expectedBiker1, $expectedBiker2), $content);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikersWithLimitQueryParam()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri() . '?&limit=1', $apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => 1, 'name' => 'Test Biker', 'email' => 'testbiker@email.com');
        $this->assertEquals(array($expectedBiker), $content);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikersWithOrderByQueryParam()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri() . '?&orderBy[id]=desc', $apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => 2, 'name' => 'Test Biker2', 'email' => 'testbiker2@email.com');
        $this->assertEquals($expectedBiker, $content[0]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikersWithOffsetQueryParam()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri() . '?&offset=1', $apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => 2, 'name' => 'Test Biker2', 'email' => 'testbiker2@email.com');
        $this->assertEquals($expectedBiker, $content[0]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikersWithFiltersQueryParam()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri() . '?&filters[name]=Test Biker2', $apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => 2, 'name' => 'Test Biker2', 'email' => 'testbiker2@email.com');
        $this->assertEquals($expectedBiker, $content[0]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikersWithMultipleQueryParams()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri() . '?&offset=0&limit=1', $apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => 1, 'name' => 'Test Biker', 'email' => 'testbiker@email.com');
        $this->assertEquals($expectedBiker, $content[0]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testPatchUpdatesAllBikerProperties()
    {
        $client = static::createClient();
        $getRequest = new JsonPatchRequest($client);
        
        $id = 1;
        $name = 'New Name';
        $email = 'email@gmail.com';
        $data = array('name' => $name, 'email' => $email);
        $expectedContent = array('id' => $id, 'name' => $name, 'email' => $email);
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->patch($this->getBikersEndpointUri() . '/' . $id, $data, $apiKey);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        
        $content = json_decode($response->getContent(), true);
        $this->assertEquals($expectedContent, $content);
        
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = array('PATCH', 'DELETE', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
        
    }
    
    public function testPatchUpdatesSingleBikerProperty()
    {
        $client = static::createClient();
        $getRequest = new JsonPatchRequest($client);
        
        $id = 1;
        $name = 'New Name';
        $data = array('name' => $name);
        $expectedContent = array('id' => $id, 'name' => $name, 'email' => 'testbiker@email.com');
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->patch($this->getBikersEndpointUri() . '/' . $id, $data, $apiKey);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        
        $content = json_decode($response->getContent(), true);
        $this->assertEquals($expectedContent, $content);
        
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = array('PATCH', 'DELETE', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
    }
    
    public function testPatchReturnsNotFound()
    {
        $client = static::createClient();
        $getRequest = new JsonPatchRequest($client);
        
        $id = 100;
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->patch($this->getBikersEndpointUri() . '/' . $id, array(), $apiKey);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    
    public function testPatchReturnsBadRequest()
    {
        $client = static::createClient();
        $getRequest = new JsonPatchRequest($client);
        $id = 1;
        $data = array('name' => 'sn');
        
        $apiKey = $this->getApiKeyForUserWithDevRole();
        $response = $getRequest->patch($this->getBikersEndpointUri() . '/' . $id, $data, $apiKey);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testOptionsResponseContainsCorrectAllowHeader()
    {
        $client = static::createClient();
        
        $client->request('OPTIONS', $this->getBikersEndpointUri(), array(), array(), array('HTTP_X-AUTH-TOKEN' => $this->getApiKeyForUserWithDevRole()));
        
        $response = $client->getResponse();
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = array('OPTIONS', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
    }
    
    public function testSuccessfullyDeleteBiker()
    {
        $client = static::createClient();
        $deleteRequest = new DeleteRequestImpl($client);
        $id = 1;
        $deleteResponse = $deleteRequest->delete(
            $this->getUrl('api_v1_get_biker', array('id' => $id)),
            $this->getApiKeyForUserWithDevRole()
        );
        
        $getRequest = new JsonGetRequest($client);
        $getResponse = $getRequest->get(
            $this->getUrl('api_v1_get_biker', array('id' => $id)),
            $this->getApiKeyForUserWithDevRole()
        );
        
        $content = json_decode($getResponse->getContent(), true);
        $expectedContent = array(
            'code' => Response::HTTP_NOT_FOUND,
            'message' => "The Biker resource of id '" . $id . "' was not found."
        );
        $this->assertEquals($expectedContent, $content);
        
        $this->assertEquals(Response::HTTP_NO_CONTENT, $deleteResponse->getStatusCode());
        
        $returnedAllowHeaderAsArray = explode(',', $deleteResponse->headers->get('Allow'));
        $expectedAllowHeaders = array('PATCH', 'DELETE', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
        
        $this->assertEquals(Response::HTTP_NOT_FOUND, $getResponse->getStatusCode());
    }
    
    public function testDeleteBikerReturnNotFoundResponse()
    {
        $client = static::createClient();
        $deleteRequest = new DeleteRequestImpl($client);
        $id = 100000;
        $response = $deleteRequest->delete(
            $this->getUrl('api_v1_get_biker', array('id' => $id)),
            $this->getApiKeyForUserWithDevRole()
        );
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    
    public function testGetReturnsForbiddenForUserWithoutDevRole()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $apiKey = $this->getApiKeyForUserWithoutDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri() . '/1', $apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expected = $this->createForbiddenMessageAsArray();
        $this->assertEquals($expected, $content);
        
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = array('PATCH', 'DELETE', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
        
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
    
    public function testCgetReturnsForbiddenForUserWithoutDevRole()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $apiKey = $this->getApiKeyForUserWithoutDevRole();
        $response = $getRequest->get($this->getBikersEndpointUri() ,$apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expected = $this->createForbiddenMessageAsArray();
        $this->assertEquals($expected, $content);
        
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = $this->createExpectedAllowHeadersForCollectionEndpoint();
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
        
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
        
    public function testPatchReturnsForbiddenForUserWithoutDevRole()
    {
        $client = static::createClient();
        $getRequest = new JsonPatchRequest($client);
        
        $id = 100;
        
        $apiKey = $this->getApiKeyForUserWithoutDevRole();
        $response = $getRequest->patch($this->getBikersEndpointUri() . '/' . $id, array(), $apiKey);
        
        $content = json_decode($response->getContent(), true);
        $expected = $this->createForbiddenMessageAsArray();
        $this->assertEquals($expected, $content);
        
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
    
    private function createExpectedAllowHeadersForCollectionEndpoint()
    {
        return array('GET', 'OPTIONS');
    }
    
    private function createForbiddenMessageAsArray()
    {
        return array('code' => 403, 'message' => 'Access Denied.');
    }
    
    private function getBikersEndpointUri()
    {
        return $this->getUrl('api_v1_get_bikers');
    }
    
    private function getApiKeyForUserWithDevRole()
    {
        return $this->fixtures->getReferenceRepository()->getReference('dev_user')->getApiKey();
    }
    
    private function getApiKeyForUserWithoutDevRole()
    {
        return $this->fixtures->getReferenceRepository()->getReference('user')->getApiKey();
    }
}
