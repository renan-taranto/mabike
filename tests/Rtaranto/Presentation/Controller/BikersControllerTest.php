<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadBikerTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonGetRequest;
use Tests\JsonPostRequest;
use Tests\JsonPutRequest;

class BikersControllerTest extends WebTestCase
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
        
        $this->assertTrue($response->headers->contains('Content-Type','application/json'));
        $this->assertNotEmpty($response->headers->get('Location'));
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
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
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testGetReturnsNotFoundException()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        
        $id = 123124213;
        $response = $getRequest->get(self::$URI . '/' . $id, $getRequest->getStandardHeadersWithAuthentication());
        $content = json_decode($response->getContent(), true);
        
        $expectedArray = array(
            'code' => Response::HTTP_NOT_FOUND,
            'message' => "The Biker resource of id '" . $id . "' was not found.");
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals($expectedArray, $content);
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
    
    public function testPutSuccessfullyPutBiker()
    {
        $client = static::createClient();
        $getRequest = new JsonPutRequest($client);
        
        $id = 1;
        $data = array('name' => 'Test Update Biker', 'email' => 'testupdatebiker@email.com');
        $response = $getRequest->put(self::$URI . '/' . $id , $getRequest->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => $id, 'name' => 'Test Update Biker', 'email' => 'testupdatebiker@email.com');
        $this->assertEquals($expectedBiker, $content);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testPutReturnsBadRequest()
    {
        $client = static::createClient();
        $getRequest = new JsonPutRequest($client);
        $id = 1;
        $data = array();
        $response = $getRequest->put(self::$URI . '/' . $id , $getRequest->getStandardHeadersWithAuthentication(), $data);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testPutNameAlreadyInUseReturnsBadRequest()
    {
        $client = static::createClient();
        
        $getRequest = new JsonPutRequest($client);
        $id = 2;
        $data = array(
            'id' => $id,
            'name' => 'Test Biker',
            'email' => 'testnewbiker@email.com');
        
        $response = $getRequest->put(self::$URI . '/' . $id , $getRequest->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        $this->assertContains('Name already in use.', $content['errors']['name'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testPutSuccessfullyCreateNewBikerAtGivenURI()
    {
        $client = static::createClient();
        $getRequest = new JsonPutRequest($client);
        
        $id = 100;
        $data = array('id' => $id, 'name' => 'Test Create Biker With Put', 'email' => 'testupdatebiker@email.com');
        $response = $getRequest->put(self::$URI . '/' . $id , $getRequest->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        $expectedBiker = array('id' => $id, 'name' => 'Test Create Biker With Put', 'email' => 'testupdatebiker@email.com');
        $this->assertEquals($expectedBiker, $content);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
