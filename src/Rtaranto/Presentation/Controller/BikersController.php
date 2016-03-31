<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPostActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPutActionInterface;
use Rtaranto\Application\Service\Endpoint\BikersEndpointService;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BikersController extends FOSRestController implements ClassResourceInterface
{
    public function postAction(Request $request)
    {
        /* @var $bikersPostAction BikersPostActionInterface */
        $bikersPostAction = $this->get('app.action.bikers.post_action');
        try {            
            $biker = $bikersPostAction->post($request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        $view = $this->view($biker, Response::HTTP_CREATED);
        return $view;
    }
    
    public function getAction($id)
    {
        /* @var $bikersEndpointService BikersEndpointService */
        $bikersEndpointService = $this->get('app.endpoint.bikers');
        return $bikersEndpointService->get($id);
    }
    
    public function cgetAction()
    {
        /* @var $bikersEndpointService BikersEndpointService */
        $bikersEndpointService = $this->get('app.endpoint.bikers');
        return $bikersEndpointService->getAll();
    }
    
    public function putAction($id, Request $request)
    {
        $responseStatusCode = Response::HTTP_OK;
        /* @var $bikerRepository BikerRepositoryInterface */
        $bikerRepository = $this->get('infra.repository.biker');
        if (empty($bikerRepository->get($id))) {
            $responseStatusCode = Response::HTTP_CREATED;
        }
        
        /* @var $bikersPutAction BikersPutActionInterface */
        $bikersPutAction = $this->get('app.action.bikers.put_action');
        try {
            $biker = $bikersPutAction->put($id, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        $view = $this->view($biker, $responseStatusCode);
        return $view;
    }
}
