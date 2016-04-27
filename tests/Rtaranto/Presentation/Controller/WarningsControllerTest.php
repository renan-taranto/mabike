<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadPerformedFrontTireChangeData;
use AppBundle\DataFixtures\ORM\LoadPerformedOilChangeData;
use AppBundle\DataFixtures\ORM\LoadPerformedRearTireChangeData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\JsonGetRequest;
use Tests\JsonPatchRequest;

class WarningsControllerTest extends WebTestCase
{
    protected $fixtures;
    protected static $MOTORCYCLE_ID = 1;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(
            array(
                LoadUserTestingData::class,
                LoadMotorcycleTestingData::class,
                LoadPerformedFrontTireChangeData::class,
                LoadPerformedOilChangeData::class,
                LoadPerformedRearTireChangeData::class
            )
        );
        
        $this->configureOilChangeWarning();
        $this->configureFrontTireChangeWarning();
        $this->configureRearTireChangeWarning();
        $this->updateMotorcycleKmsDriven();
    }
    
    public function testGetWarningsReturnsResource()
    {
        $client = static::createClient();
        $getRequest = new JsonGetRequest($client);
        $uri = $this->getUrl('api_v1_get_motorcycle_warnings', array('motorcycleId' => self::$MOTORCYCLE_ID));
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        $response = $getRequest->get($uri, $apiKey);
        $content = json_decode($response->getContent(), true);
        
        $expectOilChangeWarning = array("description" => "Oil Change", "at_kms" => 2700);
        $expectRearTireChangeWarning = array("description" => "Rear Tire Change", "at_kms" => 2800);
        $expectFrontTireChangeWarning = array("description" => "Front Tire Change", "at_kms" => 2600);
        $this->assertContains($expectOilChangeWarning, $content);
        $this->assertContains($expectRearTireChangeWarning, $content);
        $this->assertContains($expectFrontTireChangeWarning, $content);
        $this->assertStatusCode(Response::HTTP_OK, $client);
    }
    
    protected function configureOilChangeWarning()
    {
        $oilChangeWarningsConfURI = $this->getUrl(
            'api_v1_get_motorcycle_oilchangewarnings_configuration',
            array('motorcycleId' => self::$MOTORCYCLE_ID)
        );
        $this->configureMaintenanceWarnings($oilChangeWarningsConfURI);
    }
    
    protected function configureRearTireChangeWarning()
    {
        $rearTireChangeWarningsConfURI = $this->getUrl(
            'api_v1_get_motorcycle_reartirechangewarnings_configuration',
            array('motorcycleId' => self::$MOTORCYCLE_ID)
        );
        $this->configureMaintenanceWarnings($rearTireChangeWarningsConfURI);
    }
    
    protected function configureFrontTireChangeWarning()
    {
        $frontTireChangeWarninsConfURI = $this->getUrl(
            'api_v1_get_motorcycle_fronttirechangewarnings_configuration',
            array('motorcycleId' => self::$MOTORCYCLE_ID)
        );
        $this->configureMaintenanceWarnings($frontTireChangeWarninsConfURI);
        
    }
    
    protected function configureMaintenanceWarnings($uri)
    {
        $client = static::createClient();
        $patchRequest = new JsonPatchRequest($client);
        $bodyData = array(
            'is_active' => true,
            'kms_per_maintenance' => 1500,
            'kms_in_advance' => 100
        );
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        $patchRequest->patch($uri, $bodyData, $apiKey);
    }
    
    protected function updateMotorcycleKmsDriven()
    {
        $client = static::createClient();
        $uri = $this->getUrl('api_v1_patch_motorcycle', array('id' => 1));
        $patchRequest = new JsonPatchRequest($client);
        $bodyData = array(
            'kms_driven' => 15000
        );
        
        $apiKey = $this->fixtures->getReferenceRepository()->getReference('biker_user_1')->getApiKey();
        $patchRequest->patch($uri, $bodyData, $apiKey);
    }
}
