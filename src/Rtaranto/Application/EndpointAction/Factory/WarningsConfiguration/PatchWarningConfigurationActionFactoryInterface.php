<?php
namespace Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration;

interface PatchWarningConfigurationActionFactoryInterface
{
    public function createPatchAction($maintenanceClassName, $maintenanceWarningObserverClassName);
}
