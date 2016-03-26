<?php
namespace Tests\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadBikerTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
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
        $this->assertContains($name, $content['name']);
        $this->assertContains($email, $content['email']);
    }
    
    public function testBlankNameReturnsBadRequest()
    {
        $client = static::createClient();
        
        $post = new JsonPostRequest($client);
        $data = array();
        
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('This value should not be blank.',
            $content['errors']['children']['name']['errors'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testBlankEmailReturnsBadRequest()
    {
        $client = static::createClient();
        
        $post = new JsonPostRequest($client);
        $data = array();
        
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('This value should not be blank.',
            $content['errors']['children']['email']['errors'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testEmailAlreadyInUseReturnsBadRequest()
    {
        $data = array(
            'name' => 'new test biker',
            'email' => 'testbiker@email.com');
        $client = static::createClient();
        $post = new JsonPostRequest($client);

        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'E-email address already in use.', $content['message']
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testNameAlreadyInUseReturnsBadRequest()
    {
        $data = array(
            'name' => 'Test Biker',
            'email' => 'testnewbiker@email.com');
        $client = static::createClient();
        $post = new JsonPostRequest($client);

        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);
        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'Name already in use.', $content['message']
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testNameBelowMinLengthReturnsBadRequest()
    {
        $data = array('name' => str_repeat('u', 7));
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);

        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'Validation Failed', $content['message']
        );
        $this->assertContains(
                'This value is too short.', $content['errors']['children']['name']['errors'][0]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testNameOverMinLengthReturnsBadRequest()
    {
        $data = array('name' => str_repeat('u', 51));
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$URI, $post->getStandardHeadersWithAuthentication(), $data);

        $content = json_decode($response->getContent(), true);
        $this->assertContains(
                'Validation Failed', $content['message']
        );
        $this->assertContains(
                'This value is too long.', $content['errors']['children']['name']['errors'][0]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
