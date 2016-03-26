<?php
namespace Presentation\Controller;

use Application\Command\LoginCommand;
use Application\Dto\Login;
use Application\Factory\RandomAuthenticationTokenFactory;
use Application\Service\StatelessLoginService;
use Application\Service\UserTokenService;
use Doctrine\ORM\EntityManager;
use Infrastructure\Repository\DoctrineUserRepository;
use Infrastructure\Security\SaltedPasswordValidator;
use Presentation\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginController extends Controller
{
    /**
     * @FOS\RestBundle\Controller\Annotations\Post()
     */
    public function loginAction(Request $request)
    {
        $loginForm = $this->createForm(LoginType::class, new Login());
        $loginForm->submit($request->request->all()); 

        if (!$loginForm->isValid()) {
            return $loginForm;
        }
        
        $em = $this->getDoctrine()->getManager();
        $statelessLoginService = $this->createLoginService($em);
        $loginCommand = new LoginCommand($statelessLoginService);
        /* @var $loginCommand LoginCommand */
        $login = $loginForm->getData();
        try {
            $token = $loginCommand->execute($login);
        } catch (BadCredentialsException $ex) {
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
