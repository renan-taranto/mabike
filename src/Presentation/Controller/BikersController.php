<?php
namespace Presentation\Controller;

use Application\Command\Biker\PostBikerCommand;
use Application\Dto\Biker\BikerDTO;
use Application\Exception\ValidationFailedException;
use Application\Service\Endpoint\Action\Biker\BikersPutActionInterface;
use Application\Service\Endpoint\BikersEndpointService;
use Application\Service\Validator\Validator;
use Domain\Entity\Repository\BikerRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use Presentation\Form\Biker\BikerDTOType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BikersController extends FOSRestController implements ClassResourceInterface
{
    public function postAction(Request $request)
    {
        $postBikerForm = $this->createForm(BikerDTOType::class, new BikerDTO());
        $postBikerForm->submit($request->request->all()); 
        
        /* @var $validator Validator */
        $validator = $this->get('app.validator');
        if (!$validator->isValid($postBikerForm->getData())) {
            $errors = $validator->getErrors($postBikerForm->getData());
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        
        /* @var $bikersEndpointService BikersEndpointService */
        $bikersEndpointService = $this->get('app.endpoint.bikers');
        $bikerCommand = new PostBikerCommand($bikersEndpointService);
        try {
            $biker = $bikerCommand->execute($postBikerForm->getData());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        return $this->view($biker, Codes::HTTP_CREATED);
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
        /* @var $bikerRepository BikerRepository */
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
