<?php
namespace Tests\Rtaranto\Presentation\Controller;

class RearTireChangeWarningConfigurationControllerTest extends MaintenanceWarningsConfigurationTestController
{
    protected function getEndpointUri()
    {
        return $this->getUrl(
            'api_v1_get_motorcycle_reartirechangewarning_configurations',
            array('motorcycleId' => 1)
        );
    }
}