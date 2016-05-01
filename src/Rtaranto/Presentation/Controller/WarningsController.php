<?php

namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Request\ParamFetcher;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\OffsetRepresentation;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Rtaranto\Application\EndpointAction\FiltersNormalizer;
use Rtaranto\Application\EndpointAction\Warnings\GetWarningsAction;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;

class WarningsController extends MotorcycleSubResourceController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warnings")
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Warning",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"}
     *  }
     * )
     */
    public function getAction($motorcycleId, ParamFetcher $paramFetcher)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);

        $em = $this->getDoctrine()->getManager();
        $motorcycleRepository = new DoctrineMotorcycleRepository($em);
        $getWarningsAction = new GetWarningsAction($motorcycleRepository);

        $filtersNormalizer = new FiltersNormalizer();
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher, $filtersNormalizer);
        $filters = $queryParamsFetcher->getFiltersParam();
        $orderBy = $queryParamsFetcher->getOrderByParam();
        $limit = $queryParamsFetcher->getLimitParam();
        $offset = $queryParamsFetcher->getOffsetParam();

        $warnings = $getWarningsAction->get($motorcycleId);
        $total = count($warnings);
        $warnings = $this->applyOffset($warnings, $offset);
        $warnings = $this->applyLimit($warnings, $limit);

        $collectionRepresentation = new CollectionRepresentation($warnings, 'warnings', 'warnings');

        $paginatedCollection = new OffsetRepresentation(
            $collectionRepresentation,
            'api_v1_get_motorcycle_warnings',
            array('motorcycleId' => $motorcycleId),
            $offset,
            $limit,
            $total
        );
        return $paginatedCollection;
    }

    private function applyOffset(array $warnings, $offset)
    {
        return array_slice($warnings, $offset);
    }

    private function applyLimit(array $warnings, $limit)
    {
        return array_slice($warnings, 0, $limit);
    }
}
