<?php
namespace Tests\Rtaranto\Presentation\Controller;

class OilChangeWarningsConfigurationControllerTest extends MaintenanceWarningsConfigurationTestController
{
    protected function getEndpointUri()
    {
        return $this->getUrl(
            'api_v1_get_motorcycle_oilchangewarnings_configuration',
            array('motorcycleId' => 1)
        );
    }
}
