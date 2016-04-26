<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Symfony\Component\HttpFoundation\Request;

class OilChangeWarningsConfigurationController extends MaintenanceWarningConfigurationController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warnings-configuration/oil-change")
     */
    public function getAction($motorcycleId)
    {
        return parent::getAction($motorcycleId);
    }
    
    /**
     * @Patch("/motorcycles/{motorcycleId}/warnings-configuration/oil-change")
     */
    public function patchAction($motorcycleId, Request $request)
    {
        return parent::patchAction($motorcycleId, $request);
    }

    protected function getMaintenanceClassName()
    {
        return OilChange::class;
    }

    protected function getMaintenanceWarningObserverClassName()
    {
        return OilChangeWarningObserver::class;
    }
}
