<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadPerformedRearTireChangeData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;

class RearTireChangeControllerTest extends BaseTestMaintenanceController
{
    protected function getReferenceBaseName()
    {
        return 'performed_rear_tire_change';
    }

    protected function getResourceCollectionUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_reartire_changes', $params);
    }

    protected function getResourceUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_reartire_change', $params);
    }

    protected function getSubResourceIdParamNameForGetPath()
    {
        return 'performedRearTireChangeId';
    }

    public function getFixtures()
    {
        return array(
            LoadUserTestingData::class,
            LoadMotorcycleTestingData::class,
            LoadPerformedRearTireChangeData::class
        );
    }

}
