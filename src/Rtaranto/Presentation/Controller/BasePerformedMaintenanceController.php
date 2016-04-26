<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\Serializer\SerializationContext;
use Rtaranto\Application\EndpointAction\FiltersNormalizer;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\CgetPerformedMaintenanceAction;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\DeletePerformedMaintenanceAction;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\GetPerformedMaintenanceAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Domain\Entity\User;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BasePerformedMaintenanceController extends FOSRestController implements ClassResourceInterface
{    
    abstract protected function createPostAction();
    abstract protected function createPatchAction();
    abstract protected function getSerializationGroup();
    abstract protected function getPathForGetAction();
    abstract protected function getSubResourceIdParamNameForGetPath();
    abstract protected function getPerformedMaintenanceRepository();
    abstract protected function getMaintenanceRepository();
    
    protected function createGetAction()
    {
        $performedMaintenanceRepository = $this->getPerformedMaintenanceRepository();
        return new GetPerformedMaintenanceAction($performedMaintenanceRepository);
    }
    
    protected function createCgetAction(ParamFetcher $paramFetcher)
    {
        $performedMaintenanceRepository = $this->getPerformedMaintenanceRepository();
        $filtersNormalizer = new FiltersNormalizer();
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher, $filtersNormalizer);
        return new CgetPerformedMaintenanceAction($queryParamsFetcher, $performedMaintenanceRepository);
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
        return $this->createViewWithSerializationContext($performedMaintenance);
    }
    
    public function cgetAction(ParamFetcher $paramFetcher, $motorcycleId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $cGetAction = $this->createCgetAction($paramFetcher);
        
        $performedMaintenances = $cGetAction->cGet($motorcycleId);
        return $this->createViewWithSerializationContext($performedMaintenances);
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
        return $this->createViewWithSerializationContext(
            $performedMaintenance,
            Response::HTTP_CREATED,
            array('Location' => $location)
        );
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
        
        return $this->createViewWithSerializationContext($performedMaintenance);
    }
    
    public function deleteAction($motorcycleId, $performedMaintenanceId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $deleteAction = $this->createDeleteAction();
        $deleteAction->delete($motorcycleId, $performedMaintenanceId);
    }
    
    protected function throwExceptionIfNotBiker()
    {
        if (!$this->isGranted(User::ROLE_BIKER)) {
            throw new Exception('No Biker entity associated to the current User.'
                . ' Endpoint actions must be implemented for the current'
                . ' User role.'
            );
        }
    }
    
    /**
     * @param int $motorcycleId
     * @throws NotFoundHttpException
     */
    protected function throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId)
    {
        $em = $this->getDoctrine()->getManager();
        
        $bikerRepository = new DoctrineBikerRepository($em);
        $user = $this->getUser();
        $biker = $bikerRepository->findOneByUser($user);
        $motorcycleRepository = new DoctrineMotorcycleRepository($em);
        $motorcycle = $motorcycleRepository->findOneByBikerAndId($biker, $motorcycleId);
        
        if (empty($motorcycle)) {
            throw new NotFoundHttpException(
                sprintf('The Motorcycle resource of id \'%s\' was not found.', $motorcycleId)
            );
        }
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
