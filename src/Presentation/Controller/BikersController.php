<?php
namespace Presentation\Controller;

use Application\Command\Biker\PostBikerCommand;
use Application\Dto\Biker\PostBikerDTO;
use Application\Exception\ValidationFailedException;
use Application\Service\Endpoint\BikersEndpointService;
use Application\Service\Validator\Validator;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use Presentation\Form\Biker\PostBikerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BikersController extends FOSRestController implements ClassResourceInterface
{
    public function postAction(Request $request)
    {
        $postBikerForm = $this->createForm(PostBikerType::class, new PostBikerDTO());
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
}
