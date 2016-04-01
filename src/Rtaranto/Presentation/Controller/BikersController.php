<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersCgetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPostActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPutActionInterface;
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
        
        $location = $this->createLocationHeaderContent($biker->getId(), $request);
        $view = $this->view($biker, Response::HTTP_CREATED, array('Location' => $location));
        
       return $view;
    }
    
    public function getAction($id)
    {
        /* @var $bikersGetAction BikersGetActionInterface */
        $bikersGetAction = $this->get('app.action.bikers.get_action');
        return $bikersGetAction->get($id);
    }
    
    public function cgetAction()
    {
        /* @var $bikersCgetAction BikersCgetActionInterface */
        $bikersCgetAction = $this->get('app.action.bikers.cget_action');
        return $bikersCgetAction->getAll();
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
    
    private function createLocationHeaderContent($id, $request)
    {
        $routeParameters = array(
            'id'      => $id,
            '_format' => $request->get('_format')
        );
        return $this->generateUrl('api_v1_get_bikers', $routeParameters);
    }
}
