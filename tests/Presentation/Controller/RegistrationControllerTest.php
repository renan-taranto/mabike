<?php

namespace Tests\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonPostRequest;

class RegistrationControllerTest extends WebTestCase
{

    private static $REGISTRATION_URI = '/api/v1/registration';

    public function setUp()
    {
        $this->loadFixtures(array(LoadUserTestingData::class));
    }

    public function testSuccesfullyRegisterUser()
    {
        $headers = $this->getHeaders();
        $data = array(
            'username' => 'user',
            'email' => 'user@email.com',
            'password' => 'userpass');
        $client = static::createClient();
        $post = new JsonPostRequest($client);

        $response = $post->post(self::$REGISTRATION_URI, $headers, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testEmailAlreadyInUseReturnsBadRequest()
    {
        $headers = $this->getHeaders();
        $data = array(
            'username' => 'test_user_1',
            'email' => 'testuser1@email.com',
            'password' => 'userpass');
        $client = static::createClient();
        $post = new JsonPostRequest($client);

        $response = $post->post(self::$REGISTRATION_URI, $headers, $data);
        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'E-email address already in use.', $content['message']
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testUsernameAlreadyInUserReturnsBadRequest()
    {
        $headers = $this->getHeaders();
        $data = array(
            'username' => 'test_user_1',
            'email' => 'newemail@email.com',
            'password' => 'userpass');
        $client = static::createClient();
        $post = new JsonPostRequest($client);

        $response = $post->post(self::$REGISTRATION_URI, $headers, $data);
        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'Username already in use.', $content['message']
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUsernameBelowMinLengthReturnsBadRequest()
    {
        $headers = $this->getHeaders();
        $data = array('username'=> 'us');
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$REGISTRATION_URI, $headers, $data);
        
        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'Validation Failed', $content['message']
        );
        $this->assertContains(
            'This value is too short.',
            $content['errors']['children']['username']['errors'][0]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testUsernameOverMaxLengthReturnsBadRequest()
    {
        $headers = $this->getHeaders();
        $data = array('username'=> str_repeat('u', 51));
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$REGISTRATION_URI, $headers, $data);
        
        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'Validation Failed', $content['message']
        );
        $this->assertContains(
            'This value is too long.',
            $content['errors']['children']['username']['errors'][0]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testInvalidEmailReturnsBadRequest()
    {
        $headers = $this->getHeaders();
        $data = array('email'=> 'user@email');
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$REGISTRATION_URI, $headers, $data);
        
        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'Validation Failed', $content['message']
        );
        $this->assertContains(
            'Invalid e-mail address.',
            $content['errors']['children']['email']['errors']
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testPasswordBelowMinLengthReturnsBadRequest()
    {
        $headers = $this->getHeaders();
        $data = array('password'=> 12345);
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$REGISTRATION_URI, $headers, $data);
        
        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'Validation Failed', $content['message']
        );
        $this->assertContains(
            'This value is too short.',
            $content['errors']['children']['password']['errors'][0]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testPasswordOverMaxLengthReturnsBadRequest()
    {
        $headers = $this->getHeaders();
        $data = array('password'=> str_repeat('p', 4097));
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$REGISTRATION_URI, $headers, $data);
        
        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'Validation Failed', $content['message']
        );
        $this->assertContains(
            'This value is too long.',
            $content['errors']['children']['password']['errors'][0]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    private function getHeaders()
    {
        return array('CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json');
    }
}