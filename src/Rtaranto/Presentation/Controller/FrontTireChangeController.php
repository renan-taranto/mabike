<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Rtaranto\Application\EndpointAction\FrontTireChange\CgetPerformedFrontTireChangeAction;
use Rtaranto\Application\EndpointAction\FrontTireChange\DeletePerformedFrontTireChangeAction;
use Rtaranto\Application\EndpointAction\FrontTireChange\GetPerformedFrontTireChangeAction;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedFrontTireChangeRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Delete;

class FrontTireChangeController extends BasePerformedMaintenanceController
{
    private static $PATH_GET_ACTION = 'api_v1_get_motorcycle_fronttire_change';
    private static $SERIALIZATION_GROUP = 'view';
    private static $PARAM_NAME_SUB_RESOURCE_ID = 'performedFrontTireChangeId';
    
    /**
     * @Get("/motorcycles/{motorcycleId}/front-tire-changes/{performedFrontTireChangeId}")
     */
    public function getAction($motorcycleId, $performedFrontTireChangeId)
    {
        return parent::getAction($motorcycleId, $performedFrontTireChangeId);
    }
    
    /**
     * @Get("/motorcycles/{motorcycleId}/front-tire-changes")
     */
    public function cgetAction(ParamFetcher $paramFetcher, $motorcycleId)
    {
        return parent::cgetAction($paramFetcher, $motorcycleId);
    }
    
    /**
     * @Post("/motorcycles/{motorcycleId}/front-tire-changes")
     */
    public function postAction($motorcycleId, Request $request)
    {
        return parent::postAction($motorcycleId, $request);
    }
    
    /**
     * @Delete("/motorcycles/{motorcycleId}/front-tire-changes/{performedFrontTireChangeId}")
     */
    public function deleteAction($motorcycleId, $performedFrontTireChangeId)
    {
        parent::deleteAction($motorcycleId, $performedFrontTireChangeId);
    }
    
    /**
     * @Patch("/motorcycles/{motorcycleId}/front-tire-changes/{performedFrontTireChangeId}")
     */
    public function patchAction($motorcycleId, $performedFrontTireChangeId, Request $request)
    {
        return parent::patchAction($motorcycleId, $performedFrontTireChangeId, $request);
    }
    
    protected function createGetAction()
    {
        $performedFrontTireChangeRepository = $this->getPerformedFrontTireChangeRepository();
        return new GetPerformedFrontTireChangeAction($performedFrontTireChangeRepository);
    }

    protected function createCgetAction(ParamFetcher $paramFetcher)
    {
        $performedFrontTireChangeRepository = $this->getPerformedFrontTireChangeRepository();
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        return new CgetPerformedFrontTireChangeAction(
            $performedFrontTireChangeRepository,
            $queryParamsFetcher
        );
    }
    
    protected function createPostAction()
    {
        return $this->get('app.performed_front_tire_change.post_action');
    }
    
    protected function createPatchAction()
    {
        return $this->get('app.performed_front_tire_change.patch_action');
    }

    protected function createDeleteAction()
    {
        $performedFrontTireChangeRepository = $this->getPerformedFrontTireChangeRepository();
        return new DeletePerformedFrontTireChangeAction($performedFrontTireChangeRepository);
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
    
    private function getPerformedFrontTireChangeRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrinePerformedFrontTireChangeRepository($em);
    }
}
