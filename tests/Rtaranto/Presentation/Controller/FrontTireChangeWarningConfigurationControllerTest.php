<?php
namespace Tests\Rtaranto\Presentation\Controller;

class FrontTireChangeWarningConfigurationControllerTest extends MaintenanceWarningsConfigurationTestController
{
    protected function getEndpointUri()
    {
        return $this->getUrl(
            'api_v1_get_motorcycle_fronttirechangewarning_configurations',
            array('motorcycleId' => 1)
        );
    }
}