<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\CgetMotorcyclesActionFactory;

class MotorcycleController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cGetMotorcyclesActionFactory = new CgetMotorcyclesActionFactory($em, $user);
        $cGetMotorcyclesAction = $cGetMotorcyclesActionFactory->createCgetAction($paramFetcher);
        return $cGetMotorcyclesAction->cGet();
    }
}
