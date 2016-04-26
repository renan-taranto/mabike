<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\RearTireChangeWarningObserver;
use Symfony\Component\HttpFoundation\Request;

class RearTireChangeWarningsConfigurationController extends MaintenanceWarningConfigurationController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warnings-configuration/rear-tire-change")
     */
    public function getAction($motorcycleId)
    {
        return parent::getAction($motorcycleId);
    }

    /**
     * @Patch("/motorcycles/{motorcycleId}/warnings-configuration/rear-tire-change")
     */
    public function patchAction($motorcycleId, Request $request)
    {
        return parent::patchAction($motorcycleId, $request);
    }
    
    protected function getMaintenanceClassName()
    {
        return RearTireChange::class;
    }
    protected function getMaintenanceWarningObserverClassName()
    {
        return RearTireChangeWarningObserver::class;
    }
}
