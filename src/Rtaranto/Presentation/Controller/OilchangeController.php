<?php
namespace Rtaranto\Presentation\Controller;

use Exception;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\Serializer\SerializationContext;
use Rtaranto\Application\EndpointAction\Factory\OilChange\CgetPerformedOilChangeActionFactory;
use Rtaranto\Application\EndpointAction\OilChange\DeletePerformedOilChangeAction;
use Rtaranto\Application\EndpointAction\OilChange\GetPerformedOilChangeAction;
use Rtaranto\Application\EndpointAction\OilChange\PostPerformedOilChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Domain\Entity\User;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OilchangeController extends FOSRestController implements ClassResourceInterface
{
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
    
    public function getAction($motorcycleId, $performedOilChangeId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        /* @var $getOilChangeAction GetPerformedOilChangeAction */
        $getOilChangeAction = $this->get('app.performed_oil_change.get_action');
        $performedOilChange = $getOilChangeAction->get($motorcycleId, $performedOilChangeId);
        return $this->createViewWithSerializationContext($performedOilChange);
    }
    
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
    
    public function deleteAction($motorcycleId, $performedOilChangeId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        /* @var $deletePerformedOilChangeAction DeletePerformedOilChangeAction */
        $deletePerformedOilChangeAction = $this->get('app.performed_oil_change.delete_action');
        $deletePerformedOilChangeAction->delete($motorcycleId, $performedOilChangeId);
    }
    
    /**
     * @param int $motorcycleId
     * @throws NotFoundHttpException
     */
    private function throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId)
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
    
    private function createLocationHeaderContent($motorycleId, $performedOilChangeId, $request)
    {
        $routeParameters = array(
            'motorcycleId'      => $motorycleId,
            'performedOilChangeId'      => $performedOilChangeId,
            '_format' => $request->get('_format')
        );
        return $this->generateUrl('api_v1_get_motorcycle_oilchange', $routeParameters);
    }
    
    private function throwExceptionIfNotBiker()
    {
        if (!$this->isGranted(User::ROLE_BIKER)) {
            throw new Exception('There is no class that implements'
                . 'the PostActionInterface for this given user role.'
            );
        }
    }
    
    private function createViewWithSerializationContext($data = null, $statusCode = null, $headers = array())
    {
        $context = SerializationContext::create()->setGroups(array('view'));
        $view = $this->view($data, $statusCode, $headers);
        $view->setSerializationContext($context);
        return $view;
    }
}
