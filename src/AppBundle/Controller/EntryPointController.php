<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EntryPointController extends Controller
{
    public function getAction(Request $request)
    {
        if ($this->isGranted('ROLE_DEV', $this->getUser())) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(array('msg' => 'developer'));
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse(array('msg' => 'normal user'));
    }
}
