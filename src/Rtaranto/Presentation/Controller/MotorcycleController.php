<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\CgetMotorcyclesActionFactory;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\DeleteMotorcycleActionFactory;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\GetMotorcycleActionFactory;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\PatchMotorcycleActionFactory;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\PostMotorcycleActionFactory;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MotorcycleController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $this->throwExceptionIfNotBiker();
        
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cGetMotorcyclesActionFactory = new CgetMotorcyclesActionFactory($em, $user);
        $cGetMotorcyclesAction = $cGetMotorcyclesActionFactory->createCgetAction($paramFetcher);
        
        return $cGetMotorcyclesAction->cGet();
    }
    
    public function getAction($id)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($id);
        
        $em = $this->getDoctrine()->getManager();
        $getMotorcycleActionFactory = new GetMotorcycleActionFactory($em);
        $getMotorcycleAction = $getMotorcycleActionFactory->createGetAction();
        
        return $getMotorcycleAction->get($id);
    }
    
    public function postAction(Request $request)
    {
        $this->throwExceptionIfNotBiker();
        
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $postMotorcycleActionFactory = new PostMotorcycleActionFactory($user, $em, $formFactory, $sfValidator);
        $postMotorcycleAction = $postMotorcycleActionFactory->createPostAction();
        
        try {
            $motorcycle = $postMotorcycleAction->post($request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        $location = $this->createLocationHeaderContent($motorcycle->getId(), $request);
        $view = $this->view($motorcycle, Response::HTTP_CREATED, array('Location' => $location));
        
        return $view;
    }
    
    public function deleteAction($id)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($id);
        
        $em = $this->getDoctrine()->getManager();
        $deleteMotorcycleActionFactory = new DeleteMotorcycleActionFactory($em);
        $deleteMotorcycleAction = $deleteMotorcycleActionFactory->createDeleteAction();
        $deleteMotorcycleAction->delete($id);
    }

    public function patchAction($id, Request $request)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($id);
        
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $patchMotorcycleActionFactory = new PatchMotorcycleActionFactory($em, $formFactory, $sfValidator);
        $patchMotorcycleAction = $patchMotorcycleActionFactory->createPatchAction();
        
        try {
            $motorcycle = $patchMotorcycleAction->patch($id, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        return $motorcycle;
    }
    
    private function createLocationHeaderContent($id, $request)
    {
        $routeParameters = array(
            'id'      => $id,
            '_format' => $request->get('_format')
        );
        return $this->generateUrl('api_v1_get_motorcycle', $routeParameters);
    }
    
    private function throwExceptionIfNotBiker()
    {
        $em = $this->getDoctrine()->getManager();
        $doctrineBikerRepository = new DoctrineBikerRepository($em);
        $user = $this->getUser();
        $biker = $doctrineBikerRepository->findOneByUser($user);
        if (empty($biker)) {
            throw new \Exception('No Biker entity associated to the current User.'
                . ' Motorcycle endpoint actions must be created for the current'
                . ' User role.');
        }
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
}
