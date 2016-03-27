<?php
namespace Presentation\Controller;

use Application\Command\Security\LoginCommand;
use Application\Dto\Security\Login;
use Application\Factory\RandomAuthenticationTokenFactory;
use Application\Service\Security\StatelessLoginService;
use Application\Service\Security\UserTokenService;
use Application\Service\Validator\Validator;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use Infrastructure\Repository\DoctrineUserRepository;
use Infrastructure\Security\SaltedPasswordValidator;
use Presentation\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends FOSRestController
{
    /**
     * @FOS\RestBundle\Controller\Annotations\Post()
     */
    public function loginAction(Request $request)
    {
        $loginForm = $this->createForm(LoginType::class, new Login());
        $loginForm->submit($request->request->all()); 

        /* @var $validator Validator */
        $validator = $this->get('app.validator');
        if (!$validator->isValid($loginForm->getData())) {
            $errors = $validator->getErrors($loginForm->getData());
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        
        $em = $this->getDoctrine()->getManager();
        $statelessLoginService = $this->createLoginService($em);
        $loginCommand = new LoginCommand($statelessLoginService);
        /* @var $loginCommand LoginCommand */
        $login = $loginForm->getData();
        try {
            $token = $loginCommand->execute($login);
        } catch (\Exception $ex) {
            throw new BadRequestHttpException("Invalid username or password.");
        }
        return array('auth_token' => $token, 'entry_point_url' => $this->generateUrl('api_v1_entry_point'));
    }
    
    /**
     * @return StatelessLoginService
     */
    private function createLoginService(EntityManager $em)
    {
        $userRepository = new DoctrineUserRepository($em);
        $encoderFactory = $this->get('security.encoder_factory');
        $passwordValidator = new SaltedPasswordValidator($encoderFactory);
        $authenticationTokenFactory = new RandomAuthenticationTokenFactory();
        $userTokenService = new UserTokenService($userRepository, $authenticationTokenFactory);
        return new StatelessLoginService($userRepository, $passwordValidator, $userTokenService);
    }
}
