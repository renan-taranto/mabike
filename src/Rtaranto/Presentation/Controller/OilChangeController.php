<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedMaintenanceRepository;
use Symfony\Component\HttpFoundation\Request;

class OilChangeController extends PerformedMaintenanceController
{
    private static $PATH_GET_ACTION = 'api_v1_get_motorcycle_oil_change';
    private static $PATH_CGET_ACTION = 'api_v1_get_motorcycle_oil_changes';
    private static $PARAM_NAME_SUB_RESOURCE_ID = 'performedOilChangeId';
    private static $PARAM_NAME_MOTORCYCLE_ID = 'motorcycleId';
    private static $COLLECTION_NAME = 'oil-changes';
    
    /**
     * @Get("/motorcycles/{motorcycleId}/oil-changes")
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Oil Change",
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
     * @Get("/motorcycles/{motorcycleId}/oil-changes/{performedOilChangeId}")
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a Oil Change",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"},
     *      {"name"="performedOilChangeId", "dataType"="integer", "required"=true, "description"="Oil Change id"}
     *  }
     * )
     */
    public function getAction($motorcycleId, $performedOilChangeId)
    {
        return parent::getAction($motorcycleId, $performedOilChangeId);
    }
    
    /**
     * @Post("/motorcycles/{motorcycleId}/oil-changes")
     * @ApiDoc(
     *  description="Create a new Oil Change",
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
     * @Patch("/motorcycles/{motorcycleId}/oil-changes/{performedOilChangeId}")
     * @ApiDoc(
     *  description="Updates a Oil Change",
     * requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"},
     *      {"name"="performedOilChangeId", "dataType"="integer", "required"=true, "description"="Oil Change id"},
     *  },
     *  parameters={
     *      {"name"="kms_driven", "dataType"="integer", "required"=false, "description"="Motorcycle kms driven."},
     *      {"name"="date", "dataType"="string", "required"=false}
     *  }
     * )
     */
    public function patchAction($motorcycleId, $performedOilChangeId, Request $request)
    {
        return parent::patchAction($motorcycleId, $performedOilChangeId, $request);
    }
    
    /**
     * @Delete("/motorcycles/{motorcycleId}/oil-changes/{performedOilChangeId}")
     * @ApiDoc(
     *  description="Deletes a Oil Change",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"},
     *      {"name"="performedOilChangeId", "dataType"="integer", "required"=true, "description"="Oil Change id"},
     *  }
     * )
     */
    public function deleteAction($motorcycleId, $performedOilChangeId)
    {
        parent::deleteAction($motorcycleId, $performedOilChangeId);
    }
    
    protected function createPostAction()
    {
        return $this->get('app.performed_oil_change.post_action');
    }

    protected function createPatchAction()
    {
        return $this->get('app.performed_oil_change.patch_action');
    }

   
    protected function getSubResourceIdParamNameForGetPath()
    {
        return self::$PARAM_NAME_SUB_RESOURCE_ID;
    }

    protected function getPathForGetAction()
    {
        return self::$PATH_GET_ACTION;
    }

    protected function getMotorcycleIdParamNameForGetPath()
    {
        return self::$PARAM_NAME_MOTORCYCLE_ID;
    }

    protected function getMaintenanceRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrineMaintenanceRepository($em, OilChange::class);
    }

    protected function getPerformedMaintenanceRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrinePerformedMaintenanceRepository($em, PerformedOilChange::class);
    }

    protected function getPathForCgetAction()
    {
        return self::$PATH_CGET_ACTION;
    }

    protected function getResourceCollectionName()
    {
        return self::$COLLECTION_NAME;
    }

}
