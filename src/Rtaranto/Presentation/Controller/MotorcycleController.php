<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\CgetMotorcyclesActionFactory;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\PostMotorcycleActionFactory;
use Rtaranto\Application\EndpointAction\Motorcycle\PostMotorcycleAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MotorcycleController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cGetMotorcyclesActionFactory = new CgetMotorcyclesActionFactory($em, $user);
        $cGetMotorcyclesAction = $cGetMotorcyclesActionFactory->createCgetAction($paramFetcher);
        return $cGetMotorcyclesAction->cGet();
    }
    
    public function getAction($id)
    {
        
    }
    
    public function postAction(Request $request)
    {
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
    
    private function createLocationHeaderContent($id, $request)
    {
        $routeParameters = array(
            'id'      => $id,
            '_format' => $request->get('_format')
        );
        return $this->generateUrl('api_v1_get_motorcycle', $routeParameters);
    }
}
