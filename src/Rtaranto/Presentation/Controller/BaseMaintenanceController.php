<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\Serializer\SerializationContext;
use Rtaranto\Domain\Entity\User;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseMaintenanceController extends FOSRestController implements ClassResourceInterface
{    
    protected function throwExceptionIfNotBiker()
    {
        if (!$this->isGranted(User::ROLE_BIKER)) {
            throw new Exception('No Biker entity associated to the current User.'
                . ' Endpoint actions must be implemented for the current'
                . ' User role.'
            );
        }
    }
    
    /**
     * @param int $motorcycleId
     * @throws NotFoundHttpException
     */
    protected function throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId)
    {
        $em = $this->getDoctrine()->getManager();
        
        $bikerRepository = new DoctrineBikerRepository($em);
        $user = $this->getUser();
        $biker = $bikerRepository->findOneByUser($user);
        $motorcycleRepository = new DoctrineMotorcycleRepository($em);
        $motorcycle = $motorcycleRepository->findOneByBikerAndId($biker, $motorcycleId);
        
        if (empty($motorcycle)) {
            throw new NotFoundHttpException(
                sprintf('The Motorcycle resource of id \'%s\' was not found.', $motorcycleId)
            );
        }
    }
    
    protected function createViewWithSerializationContext($data = null, $statusCode = null, $headers = array())
    {
        $context = SerializationContext::create()->setGroups($this->getSerializationGroup());
        $view = $this->view($data, $statusCode, $headers);
        $view->setSerializationContext($context);
        return $view;
    }
    
    protected function createLocationHeaderContent($motorycleId, $performedOilChangeId, $request)
    {
        $motorcycleIdParam = $this->getMotorcycleIdParamNameForGetPath();
        $subResourceIdParam = $this->getSubResourceIdParamNameForGetPath();
        $routeParameters = array(
            $motorcycleIdParam  => $motorycleId,
            $subResourceIdParam => $performedOilChangeId,
            '_format'           => $request->get('_format')
        );
        $path = $this->getPathForGetAction();
        return $this->generateUrl($path, $routeParameters);
    }
    
    abstract protected function getSerializationGroup();
    abstract protected function getPathForGetAction();
    abstract protected function getSubResourceIdParamNameForGetPath();
    abstract protected function getMotorcycleIdParamNameForGetPath();
}
