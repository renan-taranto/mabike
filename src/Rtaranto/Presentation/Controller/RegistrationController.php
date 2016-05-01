<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Rtaranto\Application\EndpointAction\Factory\Registration\PostBikerRegistrationActionFactory;
use Rtaranto\Application\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends FOSRestController
{
    /**
     * @FOS\RestBundle\Controller\Annotations\Post()
     */
    public function registrationAction(Request $request)
    {
        $postBikerRegistrationAction = $this->createPostBikerRegistrationAction();
        
        try {
            $postBikerRegistrationAction->post($request->request->all());
        } catch (ValidationFailedException $ex) {
            $errors = $ex->getErrors();
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }

        $data = array(
            'code' => Response::HTTP_OK,
            'message' => 'User successfully registered.',
            '_links' => array('login' => array('href' => $this->generateUrl('api_v1_login')))
        );
        return $data;
    }
    
    private function createPostBikerRegistrationAction()
    {
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $bikerRegistrationService = $this->get('app.biker_registration');
        $postBikerRegistrationActionFactory = new PostBikerRegistrationActionFactory(
            $formFactory,
            $sfValidator,
            $bikerRegistrationService
        );
        
        return $postBikerRegistrationActionFactory->createPostAction();
    }
}
