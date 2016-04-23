<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Rtaranto\Application\EndpointAction\RearTireChange\CgetPerformedRearTireChangeAction;
use Rtaranto\Application\EndpointAction\RearTireChange\DeletePerformedRearTireChangeAction;
use Rtaranto\Application\EndpointAction\RearTireChange\GetPerformedRearTireChangeAction;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedRearTireChangeRepository;
use Rtaranto\Infrastructure\Repository\DoctrineRearTireChangeRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Delete;

class RearTireChangeController extends BasePerformedMaintenanceController
{
    private static $PATH_GET_ACTION = 'api_v1_get_motorcycle_reartire_change';
    private static $SERIALIZATION_GROUP = 'view';
    private static $PARAM_NAME_SUB_RESOURCE_ID = 'performedRearTireChangeId';
    private static $PARAM_NAME_MOTORCYCLE_ID = 'motorcycleId';
    
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
    
    protected function createGetAction()
    {
        $performedRearTireChangeRepository = $this->getPerformedRearTireChangeRepository();
        return new GetPerformedRearTireChangeAction($performedRearTireChangeRepository);
    }

    protected function createCgetAction(ParamFetcher $paramFetcher)
    {
        $performedRearTireChangeRepository = $this->getPerformedRearTireChangeRepository();
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        return new CgetPerformedRearTireChangeAction($performedRearTireChangeRepository, $queryParamsFetcher);
    }

    protected function createPostAction()
    {
        return $this->get('app.performed_rear_tire_change.post_action');
    }

    protected function createPatchAction()
    {
        return $this->get('app.performed_rear_tire_change.patch_action');
    }

    protected function createDeleteAction()
    {
        $rearTireChangeRepository = $this->getRearTireChangeRepository();
        $performedRearTireChangeRepository = $this->getPerformedRearTireChangeRepository();
        return new DeletePerformedRearTireChangeAction(
            $rearTireChangeRepository,
            $performedRearTireChangeRepository
        );
    }
    
    private function getPerformedRearTireChangeRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrinePerformedRearTireChangeRepository($em);
    }
    
    private function getRearTireChangeRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrineRearTireChangeRepository($em);
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
}
