<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EntryPointController extends Controller
{
    public function getAction(Request $request)
    {
        return new \Symfony\Component\HttpFoundation\JsonResponse(array('msg' => 'woot'));
    }
}
