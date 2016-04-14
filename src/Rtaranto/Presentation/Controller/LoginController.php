<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Rtaranto\Application\EndpointAction\Factory\Login\PostLoginActionFactory;
use Rtaranto\Application\EndpointAction\Login\PostLoginAction;
use Rtaranto\Application\Exception\ValidationFailedException;
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
        $postLoginAction = $this->createPostLoginAction();
        
        try {
            $token = $postLoginAction->post($request->request->all());
        } catch (ValidationFailedException $ex) {
            $errors = $ex->getErrors();
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        } catch (\Exception $ex) {
            throw new BadRequestHttpException("Invalid username or password.");
        }
        
        return array('auth_token' => $token, 'entry_point_url' => $this->generateUrl('api_v1_entry_point'));
    }
    
    /**
     * @return PostLoginAction
     */
    private function createPostLoginAction()
    {
        $loginService = $this->get('app.login');
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $postLoginActionFactory = new PostLoginActionFactory($loginService, $formFactory, $sfValidator);
        return $postLoginActionFactory->createPostAction();
    }
}
