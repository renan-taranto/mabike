<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Rtaranto\Application\Command\Security\UserRegistrationCommand;
use Rtaranto\Application\Dto\Security\UserRegistrationDTO;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\User;
use Rtaranto\Presentation\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends FOSRestController
{
    /**
     * @FOS\RestBundle\Controller\Annotations\Post()
     */
    public function registrationAction(Request $request)
    {
        $registrationForm = $this->createForm(
            RegistrationType::class,
            new UserRegistrationDTO(array(User::ROLE_BIKER))
        );
        $registrationForm->submit($request->request->all());

        /* @var $validator Validator */
        $validator = $this->get('app.validator');
        if (!$validator->isValid($registrationForm->getData())) {
            $errors = $validator->getErrors($registrationForm->getData());
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        
        $registerUserService = $this->get('app.user_registration');
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
