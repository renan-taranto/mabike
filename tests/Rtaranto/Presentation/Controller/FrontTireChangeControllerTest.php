<?php
namespace Tests\Rtaranto\Presentation\Controller;

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

}
