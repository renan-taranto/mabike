<?php
namespace Tests\Rtaranto\Presentation\Controller;

class FrontTireChangeWarningConfigurationControllerTest extends MaintenanceWarningsConfigurationTestController
{
    protected function getEndpointUri()
    {
        return $this->getUrl(
            'api_v1_get_motorcycle_fronttirechangewarnings_configuration',
            array('motorcycleId' => 1)
        );
    }
}