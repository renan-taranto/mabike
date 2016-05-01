<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\OffsetRepresentation;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\CgetMotorcyclesActionFactory;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\DeleteMotorcycleActionFactory;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\GetMotorcycleActionFactory;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\PatchMotorcycleActionFactory;
use Rtaranto\Application\EndpointAction\Factory\Motorcycle\PostMotorcycleActionFactory;
use Rtaranto\Application\EndpointAction\FiltersNormalizer;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MotorcycleController extends FOSRestController implements ClassResourceInterface
{
    private static $PATH_CGET = 'api_v1_get_motorcycles';
    
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Motorcycle",
     *  filters={
     *      {"name"="offset", "dataType"="integer", "default": 0},
     *      {"name"="limit", "dataType"="integer", "default": 5},
     *      {"name"="orderBy", "dataType"="array", "pattern"="(id|kms_driven|model) ASC|DESC"},
     *      {"name"="filters", "dataType"="array", "pattern"="(id|kms_driven|model) VALUE"}
     *  }
     * )
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $this->throwExceptionIfNotBiker();
        
        $filtersNormalizer = new FiltersNormalizer();
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher, $filtersNormalizer);
        $filters = $queryParamsFetcher->getFiltersParam();
        $orderBy = $queryParamsFetcher->getOrderByParam();
        $limit = $queryParamsFetcher->getLimitParam();
        $offset = $queryParamsFetcher->getOffsetParam();
        
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cGetMotorcyclesActionFactory = new CgetMotorcyclesActionFactory($em, $user);
        
        $cGetMotorcyclesAction = $cGetMotorcyclesActionFactory->createCgetAction();
        $motorcycles = $cGetMotorcyclesAction->cGet($filters, $orderBy, $limit, $offset);
        $total = count($cGetMotorcyclesAction->cGet());
        $collectionRepresentation = new CollectionRepresentation($motorcycles, 'motorcycles', 'motorcycles');
        
        $paginatedCollection = new OffsetRepresentation(
            $collectionRepresentation,
            self::$PATH_CGET,
            array(),
            $offset,
            $limit,
            $total
        );
        return $paginatedCollection;
    }
    
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a Motorcycle",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Motorcycle id"}
     *  }
     * )
     */
    public function getAction($id)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($id);
        
        $em = $this->getDoctrine()->getManager();
        $getMotorcycleActionFactory = new GetMotorcycleActionFactory($em);
        $getMotorcycleAction = $getMotorcycleActionFactory->createGetAction();
        
        return $getMotorcycleAction->get($id);
    }
    
    /**
     * @ApiDoc(
     *  description="Create a new Motorcycle",
     *  parameters={
     *      {"name"="model", "dataType"="string", "required"=true, "description"="Motorcycle model"},
     *      {"name"="kms_driven", "dataType"="integer", "required"=false, "description"="Motorcycle kms driven. Default: 0"}
     *  }
     * )
     */
    public function postAction(Request $request)
    {
        $this->throwExceptionIfNotBiker();
        
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $postMotorcycleActionFactory = new PostMotorcycleActionFactory($user, $em, $formFactory, $sfValidator);
        $postMotorcycleAction = $postMotorcycleActionFactory->createPostAction();
        
        try {
            $motorcycle = $postMotorcycleAction->post($request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        $location = $this->createLocationHeaderContent($motorcycle->getId(), $request);
        $view = $this->view($motorcycle, Response::HTTP_CREATED, array('Location' => $location));
        
        return $view;
    }
    
    /**
     * @ApiDoc(
     *  description="Deletes a Motorcycle",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Motorcycle id"}
     *  }
     * )
     */
    public function deleteAction($id)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($id);
        
        $em = $this->getDoctrine()->getManager();
        $deleteMotorcycleActionFactory = new DeleteMotorcycleActionFactory($em);
        $deleteMotorcycleAction = $deleteMotorcycleActionFactory->createDeleteAction();
        $deleteMotorcycleAction->delete($id);
    }

    /**
     * @ApiDoc(
     *  description="Updates a Motorcycle",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Motorcycle id"}
     *  },
     *  parameters={
     *      {"name"="model", "dataType"="string", "required"=false, "description"="Motorcycle model"},
     *      {"name"="kms_driven", "dataType"="integer", "required"=false, "description"="Motorcycle kms driven."}
     *  }
     * )
     */
    public function patchAction($id, Request $request)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($id);
        
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $patchMotorcycleActionFactory = new PatchMotorcycleActionFactory($em, $formFactory, $sfValidator);
        $patchMotorcycleAction = $patchMotorcycleActionFactory->createPatchAction();
        
        try {
            $motorcycle = $patchMotorcycleAction->patch($id, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        return $motorcycle;
    }
    
    private function createLocationHeaderContent($id, $request)
    {
        $routeParameters = array(
            'id'      => $id,
            '_format' => $request->get('_format')
        );
        return $this->generateUrl('api_v1_get_motorcycle', $routeParameters);
    }
    
    private function throwExceptionIfNotBiker()
    {
        $em = $this->getDoctrine()->getManager();
        $doctrineBikerRepository = new DoctrineBikerRepository($em);
        $user = $this->getUser();
        $biker = $doctrineBikerRepository->findOneByUser($user);
        if (empty($biker)) {
            throw new \Exception('No Biker entity associated to the current User.'
                . ' Motorcycle endpoint actions must be created for the current'
                . ' User role.');
        }
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
}
