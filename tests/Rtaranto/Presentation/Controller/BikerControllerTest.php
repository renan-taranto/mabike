<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadBikerTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\DeleteRequestImpl;
use Tests\JsonGetRequest;
use Tests\JsonPatchRequest;
use Tests\JsonPostRequest;

class BikerControllerTest extends WebTestCase
{
    private static $URI = 'api/v1/bikers';
    
    public function setUp()
    {
        $this->loadFixtures(array(LoadUserTestingData::class, LoadBikerTestingData::class));
    }
    
    public function testSuccessfullyPostBiker()
    {
        $client = static::createClient();
        
        $name = 'Test Post Biker';
        $email = 'testpostbiker@email.com';
        
        $post = new JsonPostRequest($client);
        $data = array('name' => $name, 'email' => $email);
        
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        
        
        $this->assertTrue($response->headers->contains('Content-Type','application/json'));
        
        $returnedLocationHeader = $response->headers->get('Location');
        $expectedLocationHeader = $this->getUrl('api_v1_get_biker', array('id' => 3));
        $this->assertEquals($expectedLocationHeader, $returnedLocationHeader);
        
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = array('POST', 'OPTIONS', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
        
        $this->assertContains($name, $content['name']);
        $this->assertContains($email, $content['email']);
    }
    
    public function testPostBlankNameReturnsBadRequest()
    {
        $client = static::createClient();
        
        $post = new JsonPostRequest($client);
        $data = array();
        
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('This value should not be blank.',
            $content['errors']['name'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testPostBlankEmailReturnsBadRequest()
    {
        $client = static::createClient();
        
        $post = new JsonPostRequest($client);
        $data = array();
        
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('This value should not be blank.',
            $content['errors']['email'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testPostEmailAlreadyInUseReturnsBadRequest()
    {
        $client = static::createClient();
        
        $post = new JsonPostRequest($client);
        $data = array(
            'name' => 'new test biker',
            'email' => 'testbiker@email.com');
        
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('E-mail address already in use.', $content['errors']['email'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testPostNameAlreadyInUseReturnsBadRequest()
    {
        $client = static::createClient();
        
        $post = new JsonPostRequest($client);
        $data = array(
            'name' => 'Test Biker',
            'email' => 'testnewbiker@email.com');
        
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('Name already in use.', $content['errors']['name'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testPostNameBelowMinLengthReturnsBadRequest()
    {
        $client = static::createClient();
        
        $post = new JsonPostRequest($client);
        $data = array('name' => str_repeat('u', 7));
        
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('Validation Failed', $content['message']);
        $this->assertContains('This value is too short.', $content['errors']['name'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testPostNameOverMinLengthReturnsBadRequest()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $data = array('name' => str_repeat('u', 51));
        
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('Validation Failed', $content['message']);
        $this->assertContains('This value is too long.', $content['errors']['name'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testGetReturnsBiker()
    {
        $client = static::createClient();
        
        $getRequest = new JsonGetRequest($client);
        $response = $getRequest->get(self::$URI . '/1', $getRequest->getStandardHeadersWithAuthentication());
        
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
        $response = $getRequest->get(self::$URI . '/' . $id, $getRequest->getStandardHeadersWithAuthentication());
        $content = json_decode($response->getContent(), true);
        
        $expectedContent = array(
            'code' => Response::HTTP_NOT_FOUND,
            'message' => "The Biker resource of id '" . $id . "' was not found.");
        $this->assertEquals($expectedContent, $content);
        
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = array('POST', 'OPTIONS', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
        
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikers()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $response = $getRequest->get(self::$URI, $getRequest->getStandardHeadersWithAuthentication());
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
        $response = $getRequest->get(self::$URI . '?&limit=1', $getRequest->getStandardHeadersWithAuthentication());
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => 1, 'name' => 'Test Biker', 'email' => 'testbiker@email.com');
        $this->assertEquals(array($expectedBiker), $content);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikersWithOrderByQueryParam()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $response = $getRequest->get(self::$URI . '?&orderBy[id]=desc', $getRequest->getStandardHeadersWithAuthentication());
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => 2, 'name' => 'Test Biker2', 'email' => 'testbiker2@email.com');
        $this->assertEquals($expectedBiker, $content[0]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikersWithOffsetQueryParam()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $response = $getRequest->get(self::$URI . '?&offset=1', $getRequest->getStandardHeadersWithAuthentication());
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => 2, 'name' => 'Test Biker2', 'email' => 'testbiker2@email.com');
        $this->assertEquals($expectedBiker, $content[0]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikersWithFiltersQueryParam()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $response = $getRequest->get(self::$URI . '?&filters[name]=Test Biker2', $getRequest->getStandardHeadersWithAuthentication());
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => 2, 'name' => 'Test Biker2', 'email' => 'testbiker2@email.com');
        $this->assertEquals($expectedBiker, $content[0]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testCgetReturnsBikersWithMultipleQueryParams()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $response = $getRequest->get(self::$URI . '?&offset=0&limit=1', $getRequest->getStandardHeadersWithAuthentication());
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
        
        $response = $getRequest->patch(self::$URI . '/' . $id, $getRequest->getStandardHeadersWithAuthentication(), $data);
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
        
        $response = $getRequest->patch(self::$URI . '/' . $id, $getRequest->getStandardHeadersWithAuthentication(), $data);
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
        
        $response = $getRequest->patch(self::$URI . '/' . $id, $getRequest->getStandardHeadersWithAuthentication(), array());
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    
    public function testPatchReturnsBadRequest()
    {
        $client = static::createClient();
        $getRequest = new JsonPatchRequest($client);
        $id = 1;
        $data = array('name' => 'sn');
        $response = $getRequest->patch(self::$URI . '/' . $id, $getRequest->getStandardHeadersWithAuthentication(), $data);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testOptionsResponseContainsCorrectAllowHeader()
    {
        $client = static::createClient();
        $headers = array('HTTP_X-AUTH-TOKEN' => 'testuserkey');
        $client->request('OPTIONS', self::$URI, array(), array(), $headers);
        
        $response = $client->getResponse();
        $returnedAllowHeaderAsArray = explode(',', $response->headers->get('Allow'));
        $expectedAllowHeaders = array('OPTIONS', 'POST', 'GET');
        $this->assertEquals(sort($expectedAllowHeaders), sort($returnedAllowHeaderAsArray));
    }
    
    public function testSuccessfullyDeleteBiker()
    {
        $client = static::createClient();
        $deleteRequest = new DeleteRequestImpl($client);
        $id = 1;
        $deleteResponse = $deleteRequest->delete(
            $this->getUrl('api_v1_get_biker', array('id' => $id)),
            $deleteRequest->getAuthenticationHeader()
        );
        
        $getRequest = new JsonGetRequest($client);
        $getResponse = $getRequest->get(
            $this->getUrl('api_v1_get_biker', array('id' => $id)),
            $getRequest->getStandardHeadersWithAuthentication()
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
            $deleteRequest->getAuthenticationHeader()
        );
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
