<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadPerformedFrontTireChangeData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;

class FrontTireChangeControllerTest extends BaseTestMaintenanceController
{
    protected function getReferenceBaseName()
    {
        return 'performed_front_tire_change';
    }

    protected function getResourceCollectionUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_fronttire_changes', $params);
    }

    protected function getResourceUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_fronttire_change', $params);
    }

    protected function getSubResourceIdParamNameForGetPath()
    {
        return 'performedFrontTireChangeId';
    }

    public function getFixtures()
    {
        return array(
            LoadUserTestingData::class,
            LoadMotorcycleTestingData::class,
            LoadPerformedFrontTireChangeData::class
        );
    }

}
