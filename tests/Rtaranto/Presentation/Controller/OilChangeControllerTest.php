<?php
namespace Tests\Rtaranto\Presentation\Controller;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadPerformedOilChangeData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;

class OilChangeControllerTest extends BaseTestMaintenanceController
{
    protected function getReferenceBaseName()
    {
        return 'performed_oil_change';
    }

    protected function getResourceCollectionUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_oil_changes', $params);
    }

    protected function getResourceUri(array $params = array())
    {
        return $this->getUrl('api_v1_get_motorcycle_oil_change', $params);
    }

    protected function getSubResourceIdParamNameForGetPath()
    {
        return 'performedOilChangeId';
    }

    public function getFixtures()
    {
        return array(
            LoadUserTestingData::class,
            LoadMotorcycleTestingData::class,
            LoadPerformedOilChangeData::class
        );
    }

}
