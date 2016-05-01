<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *  description="Create a new Rear Tire Change",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"}
     * },
     *  parameters={
     *      {"name"="kms_driven", "dataType"="integer", "required"=false, "description"="Motorcycle kms driven. Default: Current Kms Driven"},
     *      {"name"="date", "dataType"="string", "required"=false, "description"="Default: Current date"}
     *  }
     * )
     */
    public function postAction($motorcycleId, Request $request)
    {
        return parent::postAction($motorcycleId, $request);
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/rear-tire-changes")
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Rear Tire Change",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"}
     * },
     *  filters={
     *      {"name"="offset", "dataType"="integer", "default": 0},
     *      {"name"="limit", "dataType"="integer", "default": 5},
     *      {"name"="orderBy", "dataType"="array", "pattern"="(id|kms_driven|date) ASC|DESC"},
     *      {"name"="filters", "dataType"="array", "pattern"="(id|kms_driven|date) VALUE"}
     *  }
     * )
     */
    public function cgetAction(ParamFetcher $paramFetcher, $motorcycleId)
    {
        return parent::cgetAction($paramFetcher, $motorcycleId);
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a Rear Tire Change",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"},
     *      {"name"="performedRearTireChangeId", "dataType"="integer", "required"=true, "description"="Rear Tire Change id"}
     *  }
     * )
     */
    public function getAction($motorcycleId, $performedRearTireChangeId)
    {
        return parent::getAction($motorcycleId, $performedRearTireChangeId);
    }
    
    /**
     * @Delete("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     * @ApiDoc(
     *  description="Deletes a Rear Tire Change",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"},
     *      {"name"="performedRearTireChangeId", "dataType"="integer", "required"=true, "description"="Rear Tire Change id"},
     *  }
     * )
     */
    public function deleteAction($motorcycleId, $performedRearTireChangeId)
    {
        parent::deleteAction($motorcycleId, $performedRearTireChangeId);
    }
    
    /**
     * @Patch("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     * @ApiDoc(
     *  description="Updates a Rear Tire Change",
     * requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"},
     *      {"name"="performedRearTireChangeId", "dataType"="integer", "required"=true, "description"="Rear Tire Change id"},
     *  },
     *  parameters={
     *      {"name"="kms_driven", "dataType"="integer", "required"=false, "description"="Motorcycle kms driven."},
     *      {"name"="date", "dataType"="string", "required"=false}
     *  }
     * )
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
