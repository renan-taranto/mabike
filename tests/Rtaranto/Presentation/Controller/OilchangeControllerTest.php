<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadBikerTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use DateTime;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonPostRequest;

class OilchangeControllerTest extends WebTestCase
{
    private $fixtures;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(LoadUserTestingData::class, LoadBikerTestingData::class));
    }
    
    public function testPostReturnsOilChangeRepresentation()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $uri = $this->getOilChangesCollectionUri(array('motorcycleId' => 1));
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
        $expectedLocationHeader = $this->getOilChangeResourceUri(
            array('motorcycleId' => 1, 'oilChangeId' => 1)
        );
        $this->assertEquals($expectedLocationHeader, $returnedLocationHeader);
    }
    
    public function testPostInvalidKmsReturnsBadRequest()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $uri = $this->getOilChangesCollectionUri(array('motorcycleId' => 1));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        
        $data = array('kms_driven' => -1);
        
        $postRequest->post($uri, $data, $apiKey);
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals('This value should be 0 or more.',
            $content['errors']['kms_driven'][0]);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }
    
    public function testPostBlankParametersCreatesOilChangeWithCurrentDateAndCurrentKmsDriven()
    {
        $client = static::createClient();
        $postRequest = new JsonPostRequest($client);
        $uri = $this->getOilChangesCollectionUri(array('motorcycleId' => 1));
        $apiKey = $this->getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles();
        $data = array();
        
        $response = $postRequest->post($uri, $data, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $expectedDate = new \DateTime('now');
        $expectedDate = $expectedDate->format('Y-m-d');
        $expectedKmsDriven = $this->fixtures->getReferenceRepository()->getReference('ducati')->getKmsDriven();
        $expectedLocationHeader = $this->getOilChangeResourceUri(
            array('motorcycleId' => 1, 'oilChangeId' => 1)
        );
        
        $this->assertEquals($expectedKmsDriven, $content['kms_driven']);
        $this->assertEquals($expectedDate, $content['date']);
        $this->assertStatusCode(Response::HTTP_CREATED, $client);
        $returnedLocationHeader = $response->headers->get('Location');
        $this->assertEquals($expectedLocationHeader, $returnedLocationHeader);
    }
    
    private function getOilChangesCollectionUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_oilchanges', $params);
    }
    
    private function getOilChangeResourceUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_oilchange', $params);
    }
    
    private function getApiKeyForUserWithBikerRoleAndAssociatedMotorcycles()
    {
        return $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
    }
}
