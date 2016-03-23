<?php
namespace Presentation\Controller;

use Application\Command\LoginCommand;
use Application\Service\StatelessLoginService;
use Doctrine\ORM\EntityManager;
use Infrastructure\Repository\DoctrineUserRepository;
use Infrastructure\Security\RandomKeyGenerator;
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
        $loginForm = $this->createForm(LoginType::class, new LoginCommand());
        $loginForm->submit($request->request->all()); 

        if (!$loginForm->isValid()) {
            return $loginForm;
        }
        
        $em = $this->getDoctrine()->getManager();
        $loginService = $this->getLoginService($em);
        /* @var $loginCommand LoginCommand */
        $loginCommand = $loginForm->getData();
        try {
            $token = $loginService->createUserToken($loginCommand->getUsername(), $loginCommand->getPassword());
        } catch (BadCredentialsException $ex) {
            throw new BadRequestHttpException("Invalid username or password.");
        }
        return array('auth_token' => $token, 'entry_point_url' => $this->generateUrl('api_v1_entry_point'));
    }
    
    /**
     * @return StatelessLoginService
     */
    private function getLoginService(EntityManager $em)
    {
        $userRepository = new DoctrineUserRepository($em);
        $encoderFactory = $this->get('security.encoder_factory');
        $tokenGenerator = new RandomKeyGenerator($userRepository);
        return new StatelessLoginService($userRepository, $encoderFactory, $tokenGenerator);
    }
}
