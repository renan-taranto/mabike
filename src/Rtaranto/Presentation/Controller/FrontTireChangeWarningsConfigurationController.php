<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\FrontTireChangeWarningObserver;
use Symfony\Component\HttpFoundation\Request;

class FrontTireChangeWarningsConfigurationController extends MaintenanceWarningConfigurationController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warnings-configuration/front-tire-change")
     */
    public function getAction($motorcycleId)
    {
        return parent::getAction($motorcycleId);
    }

    /**
     * @Patch("/motorcycles/{motorcycleId}/warnings-configuration/front-tire-change")
     */
    public function patchAction($motorcycleId, Request $request)
    {
        return parent::patchAction($motorcycleId, $request);
    }
    
    protected function getMaintenanceClassName()
    {
        return FrontTireChange::class;
    }
    protected function getMaintenanceWarningObserverClassName()
    {
        return FrontTireChangeWarningObserver::class;
    }
}
