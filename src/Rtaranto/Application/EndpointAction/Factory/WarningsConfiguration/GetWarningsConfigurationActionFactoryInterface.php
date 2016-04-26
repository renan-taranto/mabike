<?php
namespace Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration;

interface GetWarningsConfigurationActionFactoryInterface
{
    public function createGetAction($maintenanceClassName, $maintenanceWarningObserverClassName);
}
