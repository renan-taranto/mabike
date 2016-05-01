<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\OffsetRepresentation;
use JMS\Serializer\SerializationContext;
use Rtaranto\Application\EndpointAction\FiltersNormalizer;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\CgetPerformedMaintenanceAction;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\DeletePerformedMaintenanceAction;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\GetPerformedMaintenanceAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class PerformedMaintenanceController extends MotorcycleSubResourceController
{    
    abstract protected function createPostAction();
    abstract protected function createPatchAction();
    abstract protected function getPathForGetAction();
    abstract protected function getPathForCgetAction();
    abstract protected function getResourceCollectionName();
    abstract protected function getSubResourceIdParamNameForGetPath();
    abstract protected function getPerformedMaintenanceRepository();
    abstract protected function getMaintenanceRepository();
    
    protected function createGetAction()
    {
        $performedMaintenanceRepository = $this->getPerformedMaintenanceRepository();
        return new GetPerformedMaintenanceAction($performedMaintenanceRepository);
    }
    
    protected function createCgetAction()
    {
        $performedMaintenanceRepository = $this->getPerformedMaintenanceRepository();
        return new CgetPerformedMaintenanceAction($performedMaintenanceRepository);
    }
    
    protected function createDeleteAction()
    {
        $maintenanceRepository = $this->getMaintenanceRepository();
        $performedMaintenanceRepository = $this->getPerformedMaintenanceRepository();
        return new DeletePerformedMaintenanceAction(
            $maintenanceRepository,
            $performedMaintenanceRepository
        );
    }
    
    public function getAction($motorcycleId, $performedMaintenanceId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $getAction = $this->createGetAction();
        $performedMaintenance = $getAction->get($motorcycleId, $performedMaintenanceId);
        return $performedMaintenance;
    }
    
    public function cgetAction(ParamFetcher $paramFetcher, $motorcycleId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $filtersNormalizer = new FiltersNormalizer();
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher, $filtersNormalizer);
        $filters = $queryParamsFetcher->getFiltersParam();
        $orderBy = $queryParamsFetcher->getOrderByParam();
        $limit = $queryParamsFetcher->getLimitParam();
        $offset = $queryParamsFetcher->getOffsetParam();
        
        $cGetAction = $this->createCgetAction();
        $performedMaintenances = $cGetAction->cGet($motorcycleId, $filters, $orderBy, $limit, $offset);
        $resourceCollectionName = $this->getResourceCollectionName();
        $collectionRepresentation = new CollectionRepresentation(
            $performedMaintenances,
            $resourceCollectionName,
            $resourceCollectionName
        );
        
        $cGetPath = $this->getPathForCgetAction();
        $total = count($cGetAction->cGet($motorcycleId));
        
        $paginatedCollection = new OffsetRepresentation(
            $collectionRepresentation,
            $cGetPath,
            array('motorcycleId' => $motorcycleId),
            $offset,
            $limit,
            $total
        );
        
        return $paginatedCollection;
    }
    
    public function postAction($motorcycleId, Request $request)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $postAction = $this->createPostAction();
        try {
            $performedMaintenance = $postAction->post($motorcycleId, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        $location = $this->createLocationHeaderContent($motorcycleId, $performedMaintenance->getId(), $request);
        $headers = array('Location' => $location);
        return $this->view($performedMaintenance, Response::HTTP_CREATED, $headers);
    }
    
    public function patchAction($motorcycleId, $performedMaintenanceId, Request $request)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $patchAction = $this->createPatchAction();
        try {
            $performedMaintenance = $patchAction
                ->patch($motorcycleId, $performedMaintenanceId, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        return $performedMaintenance;
    }
    
    public function deleteAction($motorcycleId, $performedMaintenanceId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $deleteAction = $this->createDeleteAction();
        $deleteAction->delete($motorcycleId, $performedMaintenanceId);
    }
    
    protected function createViewWithSerializationContext($data = null, $statusCode = null, $headers = array())
    {
        $context = SerializationContext::create()->setGroups($this->getSerializationGroup());
        $view = $this->view($data, $statusCode, $headers);
        $view->setSerializationContext($context);
        return $view;
    }
    
    protected function createLocationHeaderContent($motorycleId, $performedOilChangeId, $request)
    {
        $subResourceIdParam = $this->getSubResourceIdParamNameForGetPath();
        $routeParameters = array(
            'motorcycleId'  => $motorycleId,
            $subResourceIdParam => $performedOilChangeId,
            '_format'           => $request->get('_format')
        );
        $path = $this->getPathForGetAction();
        return $this->generateUrl($path, $routeParameters);
    }
}
