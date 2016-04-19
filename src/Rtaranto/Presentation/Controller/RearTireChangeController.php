<?php
namespace Rtaranto\Presentation\Controller;

use Rtaranto\Application\EndpointAction\RearTireChange\PostPerformedRearTireChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;

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
    public function cgetAction($motorcycleId)
    {
        
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/rear-tire-changes/{performedRearTireChangeId}")
     */
    public function getAction($motorcycleId, $performedRearTireChangeId)
    {
        
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
