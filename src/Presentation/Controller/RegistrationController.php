<?php
namespace Presentation\Controller;

use Application\Command\RegisterUserCommand;
use Application\Command\RegisterUserDTO;
use Exception;
use FOS\RestBundle\Controller\FOSRestController;
use Presentation\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class RegistrationController extends FOSRestController
{
    /**
     * @FOS\RestBundle\Controller\Annotations\Post()
     */
    public function registrationAction(Request $request)
    {
        $form = $this->createForm(RegistrationType::class, new RegisterUserDTO());
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }
        
        $registerUserService = $this->get('app.service.register_user');
        $registerUserCommand = new RegisterUserCommand($registerUserService);
        try {
            $registerUserCommand->execute($form->getData());
        } catch (Exception $ex) {
            throw new BadRequestHttpException($ex->getMessage());
        }

        $data = array(
            'code' => 200,
            'message' => 'User successfully registered.',
            'login_url' => $this->generateUrl('api_v1_login')
        );
        return $data;
    }
}
