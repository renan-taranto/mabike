<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Rtaranto\Application\EndpointAction\Factory\CgetActionFactoryInterface;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Endpoint\Action\Biker\DeleteActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\GetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\PatchActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\PostActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BikerController extends FOSRestController implements ClassResourceInterface
{
    public function postAction(Request $request)
    {
        /* @var $bikersPostAction PostActionInterface */
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
        /* @var $bikersGetAction GetActionInterface */
        $bikersGetAction = $this->get('app.action.bikers.get_action');
        return $bikersGetAction->get($id);
    }
    
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /* @var $cGetBikersFactory CgetActionFactoryInterface */
        $cGetBikersFactory = $this->get('app.action.cget_bikers_factory');
        $bikersCgetAction = $cGetBikersFactory->createCgetAction($paramFetcher);
        return $bikersCgetAction->cGet();
    }
    
    public function patchAction($id, Request $request)
    {
        /* @var $bikersPatchAction PatchActionInterface */
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
    
    public function optionsAction()
    {
        $response = new Response();
        $response->headers->set('Allow', 'POST, GET, OPTIONS');
        return $response;
    }
    
    public function deleteAction($id)
    {
        /* @var $deleteBiker DeleteActionInterface */
        $deleteBiker = $this->get('app.bikers.delete_action');
        $deleteBiker->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
    
    private function createLocationHeaderContent($id, $request)
    {
        $routeParameters = array(
            'id'      => $id,
            '_format' => $request->get('_format')
        );
        return $this->generateUrl('api_v1_get_biker', $routeParameters);
    }
}
