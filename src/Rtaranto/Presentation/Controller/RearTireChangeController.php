<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Rtaranto\Application\EndpointAction\CgetSubResourceAction;
use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Application\EndpointAction\GetSubResourceAction;
use Rtaranto\Application\EndpointAction\RearTireChange\PostPerformedRearTireChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineSubResourceRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        /* @var $postPerformedRearTireChangeAction PostPerformedRearTireChangeAction */
        $postPerformedRearTireChangeAction = $this->get('app.performed_rear_tire_change.post_action');
        try {
            $oilChange = $postPerformedRearTireChangeAction->post($motorcycleId, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        $location = $this->createLocationHeaderContent($motorcycleId, $oilChange->getId(), $request);
        return $this->
            createViewWithSerializationContext($oilChange, Response::HTTP_CREATED, array('Location' => $location));
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/rear-tire-changes")
     */
    public function cgetAction(ParamFetcher $paramFetcher, $motorcycleId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        $cgetOilResourceAction = new CgetSubResourceAction($this->getSubResourceRepository(), $queryParamsFetcher);
        $performedOilChanges = $cgetOilResourceAction->cGet($motorcycleId);
        return $this->createViewWithSerializationContext($performedOilChanges);
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     */
    public function getAction($motorcycleId, $performedRearTireChangeId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $subResourceRepository = $this->getSubResourceRepository();
        $getSubResourceAction = new GetSubResourceAction($subResourceRepository);
        $performedRearTireChange = $getSubResourceAction->get($motorcycleId, $performedRearTireChangeId);
        return $this->createViewWithSerializationContext($performedRearTireChange);
    }
    
    /**
     * @Delete("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     */
    public function deleteAction($motorcycleId, $performedRearTireChangeId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $deleteSubResourceAction = new DeleteSubResourceAction($this->getSubResourceRepository());
        $deleteSubResourceAction->delete($motorcycleId, $performedRearTireChangeId);
    }
    
    /**
     * @Patch("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     */
    public function patchAction($motorcycleId, $performedRearTireChangeId, Request $request)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $performedRearTirePatchAction = $this->get('app.performed_rear_tire_change.patch_action');
        try {
            $performedOilChange = $performedRearTirePatchAction
                ->patch($motorcycleId, $performedRearTireChangeId, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        return $this->createViewWithSerializationContext($performedOilChange);
    }
    
    private function getSubResourceRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrineSubResourceRepository($em, 'motorcycle', PerformedRearTireChange::class);
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
