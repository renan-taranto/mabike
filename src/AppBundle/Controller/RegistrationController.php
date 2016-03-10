<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @FOS\RestBundle\Controller\Annotations\Post()
     */
    public function registerAction(Request $request)
    {
        $user = new User('auser', 'user@mail.com', array('ROLE_USER'));
        $password = $this->get('security.password_encoder')
                ->encodePassword($user, '12345');
        $user->setPassword($password);
        $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
    }
   
}
