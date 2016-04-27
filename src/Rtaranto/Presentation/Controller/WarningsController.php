<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use Rtaranto\Application\EndpointAction\Warnings\GetWarningsAction;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;

class WarningsController extends MotorcycleSubResourceController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warnings")
     */
    public function getAction($motorcycleId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $em = $this->getDoctrine()->getManager();
        $motorcycleRepository = new DoctrineMotorcycleRepository($em);
        $getWarningsAction = new GetWarningsAction($motorcycleRepository);
        return $getWarningsAction->get($motorcycleId);
    }
}
