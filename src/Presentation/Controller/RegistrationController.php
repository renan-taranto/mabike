<?php
namespace Presentation\Controller;

use Application\Command\UserRegistrationCommand;
use Application\Dto\UserRegistration;
use Application\Exception\ValidationFailedException;
use Application\Service\Validator\Validator;
use FOS\RestBundle\Controller\FOSRestController;
use Presentation\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends FOSRestController
{
    /**
     * @FOS\RestBundle\Controller\Annotations\Post()
     */
    public function registrationAction(Request $request)
    {
        $registrationForm = $this->createForm(RegistrationType::class, new UserRegistration());
        $registrationForm->submit($request->request->all());

        /* @var $validator Validator */
        $validator = $this->get('app.validator');
        if (!$validator->isValid($registrationForm->getData())) {
            $errors = $validator->getErrors($registrationForm->getData());
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        
        $registerUserService = $this->get('app.service.register_user');
        $registerUserCommand = new UserRegistrationCommand($registerUserService);
        try {
            $registerUserCommand->execute($registrationForm->getData());
        } catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }

        $data = array(
            'code' => Response::HTTP_OK,
            'message' => 'User successfully registered.',
            'login_url' => $this->generateUrl('api_v1_login')
        );
        return $data;
    }
}
