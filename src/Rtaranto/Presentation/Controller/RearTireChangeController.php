<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Request\ParamFetcher;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedMaintenanceRepository;
use Symfony\Component\HttpFoundation\Request;

class RearTireChangeController extends PerformedMaintenanceController
{
    private static $PATH_GET_ACTION = 'api_v1_get_motorcycle_reartire_change';
    private static $PATH_CGET_ACTION = 'api_v1_get_motorcycle_reartire_changes';
    private static $PARAM_NAME_SUB_RESOURCE_ID = 'performedRearTireChangeId';
    private static $PARAM_NAME_MOTORCYCLE_ID = 'motorcycleId';
    private static $COLLECTION_NAME = 'rear-tire-changes';
    
    /**
     * @Post("/motorcycles/{motorcycleId}/rear-tire-changes")
     */
    public function postAction($motorcycleId, Request $request)
    {
        return parent::postAction($motorcycleId, $request);
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/rear-tire-changes")
     */
    public function cgetAction(ParamFetcher $paramFetcher, $motorcycleId)
    {
        return parent::cgetAction($paramFetcher, $motorcycleId);
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     */
    public function getAction($motorcycleId, $performedRearTireChangeId)
    {
        return parent::getAction($motorcycleId, $performedRearTireChangeId);
    }
    
    /**
     * @Delete("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     */
    public function deleteAction($motorcycleId, $performedRearTireChangeId)
    {
        parent::deleteAction($motorcycleId, $performedRearTireChangeId);
    }
    
    /**
     * @Patch("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     */
    public function patchAction($motorcycleId, $performedRearTireChangeId, Request $request)
    {
        return parent::patchAction($motorcycleId, $performedRearTireChangeId, $request);
    }
    
    protected function createPostAction()
    {
        return $this->get('app.performed_rear_tire_change.post_action');
    }

    protected function createPatchAction()
    {
        return $this->get('app.performed_rear_tire_change.patch_action');
    }

    protected function getPerformedMaintenanceRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrinePerformedMaintenanceRepository($em, PerformedRearTireChange::class);
    }
    
    protected function getMaintenanceRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrineMaintenanceRepository($em, RearTireChange::class);
    }
    
    protected function getSubResourceIdParamNameForGetPath()
    {
        return self::$PARAM_NAME_SUB_RESOURCE_ID;
    }

    protected function getPathForGetAction()
    {
        return self::$PATH_GET_ACTION;
    }
    
    protected function getPathForCgetAction()
    {
        return self::$PATH_CGET_ACTION;
    }

    protected function getMotorcycleIdParamNameForGetPath()
    {
        return self::$PARAM_NAME_MOTORCYCLE_ID;
    }
    
    protected function getResourceCollectionName()
    {
        return self::$COLLECTION_NAME;
    }
}
