<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersCgetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPatchActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPostActionInterface;
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
    
    public function patchAction($id, Request $request)
    {
        /* @var $bikersPatchAction BikersPatchActionInterface */
        $bikersPatchAction = $this->get('app.action.bikers.patch_action');
        try {
            $biker = $bikersPatchAction->patch($id, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        return $biker;
        
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
