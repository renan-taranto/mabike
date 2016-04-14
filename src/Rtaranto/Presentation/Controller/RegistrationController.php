<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Rtaranto\Application\EndpointAction\Factory\Registration\PostBikerRegistrationActionFactory;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Security\Factory\BikerRegistrationServiceFactory;
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
            'login_url' => $this->generateUrl('api_v1_login')
        );
        return $data;
    }
    
    private function createPostBikerRegistrationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userPasswordEncoder = $this->get('security.password_encoder');
        $sfValidator = $this->get('validator');
        
        $bikerRegistrationServiceFactory = new BikerRegistrationServiceFactory(
            $em,
            $userPasswordEncoder,
            $sfValidator
        );
        $bikerRegistrationService = $bikerRegistrationServiceFactory->createUserRegistrationService();
        
        $formFactory = $this->get('form.factory');
        $postBikerRegistrationActionFactory = new PostBikerRegistrationActionFactory(
            $formFactory,
            $sfValidator,
            $bikerRegistrationService
        );
        
        return $postBikerRegistrationActionFactory->createPostAction();
    }
}
