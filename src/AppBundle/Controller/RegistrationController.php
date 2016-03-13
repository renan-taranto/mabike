<?php
namespace AppBundle\Controller;

use AppBundle\Form\RegistrationType;
use AppBundle\Security\RegisterUserCommand;
use AppBundle\Security\RegisterUserCommandHandler;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RegistrationController extends FOSRestController
{
    /**
     * @FOS\RestBundle\Controller\Annotations\Post()
     */
    public function registrationAction(Request $request)
    {
        $form = $this->createForm(RegistrationType::class, new RegisterUserCommand());
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $registerUserCommandHandler = new RegisterUserCommandHandler($this->get('security.password_encoder'));
        $user = $registerUserCommandHandler->perform($form->getData());
        
        $validator = $this->get('validator');
        $errors = $validator->validate($user);
        if (count($errors)) {
            throw new BadRequestHttpException($errors[0]->getMessage());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $data = array(
            'code' => 200,
            'message' => 'User successfully registered.',
            'login_url' => $this->generateUrl('api_v1_login')
        );
        return $data;
    }

}
