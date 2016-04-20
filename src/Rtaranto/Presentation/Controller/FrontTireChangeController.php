<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Rtaranto\Application\EndpointAction\CgetSubResourceAction;
use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Application\EndpointAction\GetSubResourceAction;
use Rtaranto\Application\EndpointAction\OilChange\PostPerformedOilChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineSubResourceRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Delete;

class FrontTireChangeController extends BasePerformedMaintenanceController
{
    private static $PATH_GET_ACTION = 'api_v1_get_motorcycle_fronttire_change';
    private static $SERIALIZATION_GROUP = 'view';
    private static $PARAM_NAME_SUB_RESOURCE_ID = 'performedFrontTireChangeId';
    private static $PARAM_NAME_MOTORCYCLE_ID = 'motorcycleId';
    
    /**
     * @Get("/motorcycles/{motorcycleId}/front-tire-changes/{performedFrontTireChangeId}")
     */
    public function getAction($motorcycleId, $performedFrontTireChangeId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $subResourceRepository = $this->getSubResourceRepository();
        $getSubResourceAction = new GetSubResourceAction($subResourceRepository);
        $performedFrontTireChange = $getSubResourceAction->get($motorcycleId, $performedFrontTireChangeId);
        return $this->createViewWithSerializationContext($performedFrontTireChange);
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/front-tire-changes")
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
     * @Post("/motorcycles/{motorcycleId}/front-tire-changes")
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
     * @Delete("/motorcycles/{motorcycleId}/front-tire-changes/{performedFrontTireChangeId}")
     */
    public function deleteAction($motorcycleId, $performedFrontTireChangeId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $deleteSubResourceAction = new DeleteSubResourceAction($this->getSubResourceRepository());
        $deleteSubResourceAction->delete($motorcycleId, $performedFrontTireChangeId);
    }
    
    /**
     * @Patch("/motorcycles/{motorcycleId}/front-tire-changes/{performedFrontTireChangeId}")
     */
    public function patchAction($motorcycleId, $performedFrontTireChangeId, Request $request)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $performedFrontTirePatchAction = $this->get('app.performed_front_tire_change.patch_action');
        try {
            $performedOilChange = $performedFrontTirePatchAction
                ->patch($motorcycleId, $performedFrontTireChangeId, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        return $this->createViewWithSerializationContext($performedOilChange);
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
    
    private function getSubResourceRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrineSubResourceRepository($em, 'motorcycle', PerformedFrontTireChange::class);
    }
}
