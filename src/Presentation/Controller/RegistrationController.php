<?php
namespace Presentation\Controller;

use Application\Command\RegisterUserCommand;
use Application\Command\RegisterUserCommandHandler;
use Application\Service\RegisterUserService;
use Exception;
use FOS\RestBundle\Controller\FOSRestController;
use Infrastructure\Repository\DoctrineUserRepository;
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
        $form = $this->createForm(RegistrationType::class, new RegisterUserCommand());
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $registerUserCommandHandler = new RegisterUserCommandHandler($this->get('security.password_encoder'));
        $user = $registerUserCommandHandler->perform($form->getData());
        
        $em = $this->getDoctrine()->getManager();
        $userRepository = new DoctrineUserRepository($em);
        $validator = $this->get('validator');
        $registerUserService = new RegisterUserService($userRepository, $validator);
        try {
            $registerUserService->registerUser($user);
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
