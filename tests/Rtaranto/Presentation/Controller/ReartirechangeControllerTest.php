<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadPerformedOilChangeData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use DateTime;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonPostRequest;

class ReartirechangeControllerTest extends WebTestCase
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
    
    public function testPostReturnsCreatedResponseWithRepresentation()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $motorcycleId = 1;
        $uri = $this->getRearTireChangeCollectionUri(array('motorcycleId' => $motorcycleId));
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
        $expectedLocationHeader = $this->getRearTireChangeResourceUri(
            array('motorcycleId' => $motorcycleId, 'performedRearTireChangeId' => $content['id'])
        );
        $this->assertEquals($expectedLocationHeader, $returnedLocationHeader);
    }
    
    public function testPostInvalidKmsReturnsBadRequest()
    {
        
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $motorcycleId = 1;
        $uri = $this->getRearTireChangeCollectionUri(array('motorcycleId' => $motorcycleId));
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
        $uri = $this->getRearTireChangeCollectionUri(array('motorcycleId' => $motorcycleId));
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
    
    private function getRearTireChangeCollectionUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_reartirechanges', $params);
    }
    
    private function getRearTireChangeResourceUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_reartirechange', $params);
    }
    
    private function getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles()
    {
        return $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
    }
}
