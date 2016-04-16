<?php
namespace Rtaranto\Presentation\Controller;

use Exception;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\Serializer\SerializationContext;
use Rtaranto\Application\EndpointAction\Factory\OilChange\BikerPostOilChangeActionFactory;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Domain\Entity\User;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OilchangeController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(ParamFetcher $paramFetcher, $motorcycleId)
    {
        
    }
    
    public function getAction($motorcycleId, $oilChangeId)
    {
        
    }
    
    public function postAction($motorcycleId, Request $request)
    {
        if (!$this->isGranted(User::ROLE_BIKER)) {
            throw new Exception('There is no class that implements'
                . 'the PostActionInterface for this given user role.'
            );
        }
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $factory = new BikerPostOilChangeActionFactory($formFactory, $sfValidator, $em);
        $action = $factory->createPostAction();
        
        
        try {
            $oilChange = $action->post($motorcycleId, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        $context = SerializationContext::create()->setGroups(array('view')); 
        $location = $this->createLocationHeaderContent($motorcycleId, $oilChange->getId(), $request);
        $view = $this->view($oilChange, Response::HTTP_CREATED, array('Location' => $location));
        $view->setSerializationContext($context);
        
        return $view;
    }
    
    /**
     * @param int $motorcycleId
     * @throws NotFoundHttpException
     */
    private function throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId)
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
    
    private function createLocationHeaderContent($motorycleId, $oilChangeId, $request)
    {
        $routeParameters = array(
            'motorcycleId'      => $motorycleId,
            'oilChangeId'      => $oilChangeId,
            '_format' => $request->get('_format')
        );
        return $this->generateUrl('api_v1_get_motorcycle_oilchange', $routeParameters);
    }
}
