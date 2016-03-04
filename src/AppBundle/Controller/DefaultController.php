<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return new \Symfony\Component\HttpFoundation\JsonResponse(array('msg' => 'woot'));
//        $m = new \AppBundle\Entity\Motorcycle();
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($m);
//        $em->flush();
//        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
//        ]);
    }
}
