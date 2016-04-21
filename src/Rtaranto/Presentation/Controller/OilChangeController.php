<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Rtaranto\Application\EndpointAction\OilChange\CgetPerformedOilChangeAction;
use Rtaranto\Application\EndpointAction\OilChange\DeletePerformedOilChangeAction;
use Rtaranto\Application\EndpointAction\OilChange\GetPerformedOilChangeAction;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedOilChangeRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Delete;


class OilChangeController extends BasePerformedMaintenanceController
{
    private static $PATH_GET_ACTION = 'api_v1_get_motorcycle_oil_change';
    private static $SERIALIZATION_GROUP = 'view';
    private static $PARAM_NAME_SUB_RESOURCE_ID = 'performedOilChangeId';
    private static $PARAM_NAME_MOTORCYCLE_ID = 'motorcycleId';
            
    /**
     * @Get("/motorcycles/{motorcycleId}/oil-changes")
     */
    public function cgetAction(ParamFetcher $paramFetcher, $motorcycleId)
    {
        return parent::cgetAction($paramFetcher, $motorcycleId);
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/oil-changes/{performedOilChangeId}")
     */
    public function getAction($motorcycleId, $performedOilChangeId)
    {
        return parent::getAction($motorcycleId, $performedOilChangeId);
    }
    
    /**
     * @Post("/motorcycles/{motorcycleId}/oil-changes")
     */
    public function postAction($motorcycleId, Request $request)
    {
        return parent::postAction($motorcycleId, $request);
    }
    
    /**
     * @Patch("/motorcycles/{motorcycleId}/oil-changes/{performedOilChangeId}")
     */
    public function patchAction($motorcycleId, $performedOilChangeId, Request $request)
    {
        return parent::patchAction($motorcycleId, $performedOilChangeId, $request);
    }
    
    /**
     * @Delete("/motorcycles/{motorcycleId}/oil-changes/{performedOilChangeId}")
     */
    public function deleteAction($motorcycleId, $performedOilChangeId)
    {
        parent::deleteAction($motorcycleId, $performedOilChangeId);
    }
    
    protected function getSubResourceIdParamNameForGetPath()
    {
        return self::$PARAM_NAME_SUB_RESOURCE_ID;
    }

    protected function getPathForGetAction()
    {
        return self::$PATH_GET_ACTION;
    }

    protected function getSerializationGroup()
    {
        return self::$SERIALIZATION_GROUP;
    }
    
    protected function getMotorcycleIdParamNameForGetPath()
    {
        return self::$PARAM_NAME_MOTORCYCLE_ID;
    }

    protected function createPostAction()
    {
        return $this->get('app.performed_oil_change.post_action');
    }

    protected function createPatchAction()
    {
        return $this->get('app.performed_oil_change.patch_action');
    }

    protected function createGetAction()
    {
        $performedOilChangeRepository = $this->getPerformedOilChangeRepository();
        return new GetPerformedOilChangeAction($performedOilChangeRepository);
    }

    protected function createCgetAction(ParamFetcher $paramFetcher)
    {
        $performedOilChangeRepository = $this->getPerformedOilChangeRepository();
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        return new CgetPerformedOilChangeAction($performedOilChangeRepository, $queryParamsFetcher);
    }

    protected function createDeleteAction()
    {
        $performedOilChangeRepository = $this->getPerformedOilChangeRepository();
        return new DeletePerformedOilChangeAction($performedOilChangeRepository);
    }

    private function getPerformedOilChangeRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrinePerformedOilChangeRepository($em);
    }
    
}
