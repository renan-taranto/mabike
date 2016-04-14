<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Rtaranto\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonPostRequest;

class RegistrationControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures(array(LoadUserTestingData::class));
    }

    public function testSuccesfullyRegisterUserAsBiker()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $username = 'brand_new_biker';
        $email = 'user@email.com';
        $password = 'userpass';
        $data = array(
            'username' => $username,
            'email' => $email,
            'password' => $password);
        
        $response = $post->post($this->getRegistrationUri(), $data);
        
        /* @var $em EntityManagerInterface */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        /* @var $persistedUser User */
        $userRepository = $em->getRepository('Domain:User');
        $persistedUser = $userRepository->findOneBy(array('username' => $username));
        $this->assertEquals(array('ROLE_BIKER'), $persistedUser->getRoles());
        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testEmailAlreadyInUseReturnsBadRequest()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $data = array(
            'username' => 'test_user_1',
            'email' => 'testuser1@email.com',
            'password' => 'userpass');
        $response = $post->post($this->getRegistrationUri(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('E-email address already in use.', $content['errors']['email'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUsernameAlreadyInUserReturnsBadRequest()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $data = array(
            'username' => 'test_user_1',
            'email' => 'newemail@email.com',
            'password' => 'userpass');
        $response = $post->post($this->getRegistrationUri(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('Username already in use.', $content['errors']['username'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUsernameBelowMinLengthReturnsBadRequest()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $data = array('username' => 'us');
        $response = $post->post($this->getRegistrationUri(), $data);
        $content = json_decode($response->getContent(), true);

        $this->assertContains('Validation Failed', $content['message']);
        $this->assertContains('This value is too short.', $content['errors']['username'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUsernameOverMaxLengthReturnsBadRequest()
    {
        $client = static::createClient();
        $data = array('username' => str_repeat('u', 51));
        $post = new JsonPostRequest($client);
        $response = $post->post($this->getRegistrationUri(), $data);
        $content = json_decode($response->getContent(), true);

        $this->assertContains('Validation Failed', $content['message']);
        $this->assertContains('This value is too long.', $content['errors']['username'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testInvalidEmailReturnsBadRequest()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $data = array('email' => 'user@email');
        $response = $post->post($this->getRegistrationUri(), $data);
        $content = json_decode($response->getContent(), true);

        $this->assertContains('Validation Failed', $content['message']);
        $this->assertContains('Invalid e-mail address.', $content['errors']['email'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testPasswordBelowMinLengthReturnsBadRequest()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $data = array('password' => 12345);
        $response = $post->post($this->getRegistrationUri(), $data);

        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('Validation Failed', $content['message']);
        $this->assertContains('This value is too short.', $content['errors']['password'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testPasswordOverMaxLengthReturnsBadRequest()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $data = array('password' => str_repeat('p', 4097));
        $response = $post->post($this->getRegistrationUri(), $data);
        $content = json_decode($response->getContent(), true);

        $this->assertContains('Validation Failed', $content['message']);
        $this->assertContains('This value is too long.', $content['errors']['password'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testBlankUsernameReturnsBadRequest()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $data = array('username' => '');
        $response = $post->post($this->getRegistrationUri(), $data);
        $content = json_decode($response->getContent(), true);

        $this->assertContains('This value should not be blank.',$content['errors']['username'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    public function testBlankPasswordReturnsBadRequest()
    {
        $client = static::createClient();
        $post = new JsonPostRequest($client);
        $data = array('username' => '');
        $response = $post->post($this->getRegistrationUri(), $data);
        $content = json_decode($response->getContent(), true);
        
        $this->assertContains('This value should not be blank.',$content['errors']['password'][0]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    private function getRegistrationUri()
    {
        return $this->getUrl('api_v1_registration');
    }
}
