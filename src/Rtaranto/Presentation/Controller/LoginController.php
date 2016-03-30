<?php
namespace Rtaranto\Presentation\Controller;

use Rtaranto\Application\Command\Security\LoginCommand;
use Rtaranto\Application\Dto\Security\LoginDTO;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Presentation\Form\LoginType;

use FOS\RestBundle\Controller\FOSRestController;

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
        $loginForm = $this->createForm(LoginType::class, new LoginDTO());
        $loginForm->submit($request->request->all()); 

        /* @var $validator Validator */
        $validator = $this->get('app.validator');
        if (!$validator->isValid($loginForm->getData())) {
            $errors = $validator->getErrors($loginForm->getData());
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        
        /* @var @statelessLoginService StatelessLoginService */
        $statelessLoginService = $this->get('app.login');
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
}
