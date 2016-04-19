<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Rtaranto\Application\EndpointAction\Factory\OilChange\CgetPerformedOilChangeActionFactory;
use Rtaranto\Application\EndpointAction\OilChange\DeletePerformedOilChangeAction;
use Rtaranto\Application\EndpointAction\OilChange\GetPerformedOilChangeAction;
use Rtaranto\Application\EndpointAction\OilChange\PostPerformedOilChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
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
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $em = $this->getDoctrine()->getManager();
        $cgetOilChangeActionFactory = new CgetPerformedOilChangeActionFactory($em);
        $cgetOilChangeAction = $cgetOilChangeActionFactory->createCgetAction($paramFetcher);
        
        $performedOilChanges = $cgetOilChangeAction->cGet($motorcycleId);
        return $this->createViewWithSerializationContext($performedOilChanges);
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/oil-changes/{performedOilChangeId}")
     */
    public function getAction($motorcycleId, $performedOilChangeId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        /* @var $getOilChangeAction GetPerformedOilChangeAction */
        $getOilChangeAction = $this->get('app.performed_oil_change.get_action');
        $performedOilChange = $getOilChangeAction->get($motorcycleId, $performedOilChangeId);
        return $this->createViewWithSerializationContext($performedOilChange);
    }
    
    /**
     * @Post("/motorcycles/{motorcycleId}/oil-changes")
     */
    public function postAction($motorcycleId, Request $request)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        /* @var $postPerformedOilChangeAction PostPerformedOilChangeAction */
        $postPerformedOilChangeAction = $this->get('app.performed_oil_change.post_action');
        try {
            $oilChange = $postPerformedOilChangeAction->post($motorcycleId, $request->request->all());
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
     * @Patch("/motorcycles/{motorcycleId}/oil-changes/{performedOilChangeId}")
     */
    public function patchAction($motorcycleId, $performedOilChangeId, Request $request)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $oilChangePatchAction = $this->get('app.performed_oil_change.patch_action');
        try {
            $performedOilChange = $oilChangePatchAction
                ->patch($motorcycleId, $performedOilChangeId, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        return $this->createViewWithSerializationContext($performedOilChange);
    }
    
    /**
     * @Delete("/motorcycles/{motorcycleId}/oil-changes/{performedOilChangeId}")
     */
    public function deleteAction($motorcycleId, $performedOilChangeId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        /* @var $deletePerformedOilChangeAction DeletePerformedOilChangeAction */
        $deletePerformedOilChangeAction = $this->get('app.performed_oil_change.delete_action');
        $deletePerformedOilChangeAction->delete($motorcycleId, $performedOilChangeId);
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
