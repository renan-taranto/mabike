<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Request\ParamFetcher;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedMaintenanceRepository;
use Symfony\Component\HttpFoundation\Request;

class FrontTireChangeController extends PerformedMaintenanceController
{
    private static $PATH_GET_ACTION = 'api_v1_get_motorcycle_fronttire_change';
    private static $PATH_CGET_ACTION = 'api_v1_get_motorcycle_fronttire_changes';
    private static $PARAM_NAME_SUB_RESOURCE_ID = 'performedFrontTireChangeId';
    private static $COLLECTION_NAME = 'front-tire-changes';
    
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
    
    protected function createPostAction()
    {
        return $this->get('app.performed_front_tire_change.post_action');
    }
    
    protected function createPatchAction()
    {
        return $this->get('app.performed_front_tire_change.patch_action');
    }

    protected function getMaintenanceRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrineMaintenanceRepository($em, FrontTireChange::class);
    }

    protected function getPerformedMaintenanceRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return new DoctrinePerformedMaintenanceRepository($em, PerformedFrontTireChange::class);
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

    protected function getResourceCollectionName()
    {
        return self::$COLLECTION_NAME;
    }
}
