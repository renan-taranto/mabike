<?php
namespace AppBundle\Controller;

use AppBundle\Form\LoginType;
use AppBundle\Security\LoginCommand;
use AppBundle\Security\LoginService;
use AppBundle\Security\TokenGenerator;
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
        
        $loginService = $this->getLoginService();
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
     * @return LoginService
     */
    private function getLoginService()
    {
        $userRepository = $this->getDoctrine()->getRepository('Domain:User');
        $encoderFactory = $this->get('security.encoder_factory');
        $tokenGenerator = new TokenGenerator($userRepository);
        return new LoginService($userRepository, $encoderFactory, $tokenGenerator);
    }
}
