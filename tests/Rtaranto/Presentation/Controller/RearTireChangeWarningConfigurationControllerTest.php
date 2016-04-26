<?php
namespace Tests\Rtaranto\Presentation\Controller;

class RearTireChangeWarningConfigurationControllerTest extends MaintenanceWarningsConfigurationTestController
{
    protected function getEndpointUri()
    {
        return $this->getUrl(
            'api_v1_get_motorcycle_reartirechangewarnings_configuration',
            array('motorcycleId' => 1)
        );
    }
}