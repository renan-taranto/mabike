<?php
namespace Tests\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonPostRequest;

class LoginControllerTest extends WebTestCase
{
    private static $LOGIN_URI = '/api/v1/login';
    
    public function setUp()
    {
        $this->loadFixtures(array(LoadUserTestingData::class));
    }
    
    public function testSuccesfullLoginReturnsToken()
    {
        $data = array('username' => 'test_user_1', 'password' => 123456);
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$LOGIN_URI, $post->getStandardHeaders(), $data);

        $content = json_decode($response->getContent(), true);
        $this->assertNotNull($content['auth_token']['key']);
        $this->assertNotNull($content['auth_token']['expiration_date_time']);
        $this->assertNotNull($content['entry_point_url']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testInvalidPasswordReturnsBadRequest()
    {
        $data = array('username' => 'test_user_1', 'password' => 123457);
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$LOGIN_URI, $post->getStandardHeaders(), $data);

        $content = json_decode($response->getContent(), true);
        $this->assertContains('Invalid username or password.', $content['message']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testUserNotFoundReturnsBadRequest()
    {
        $data = array('username' => 'test', 'password' => 123456);
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$LOGIN_URI, $post->getStandardHeaders(), $data);

        $content = json_decode($response->getContent(), true);
        $this->assertContains('Invalid username or password.', $content['message']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testBlankUsernameReturnsBadRequest()
    {
        $data = array('username' => '');
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$LOGIN_URI, $post->getStandardHeaders(), $data);

        $content = json_decode($response->getContent(), true);
        $this->assertContains('This value should not be blank.',
            $content['errors']['username'][0]);
        
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testBlankPasswordReturnsBadRequest()
    {
        $data = array('username' => '');
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $response = $post->post(self::$LOGIN_URI, $post->getStandardHeaders(), $data);

        $content = json_decode($response->getContent(), true);
        $this->assertContains('This value should not be blank.',
            $content['errors']['password'][0]);
        
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
