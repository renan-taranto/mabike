<?php
namespace Rtaranto\Presentation\Controller\QueryParam;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Rtaranto\Application\EndpointAction\FiltersNormalizerInterface;

class QueryParamsFetcher implements QueryParamsFetcherInterface
{
    private $paramFetcher;
    private $filtersNormalizer;
    private static $PARAM_FILTERS = 'filters';
    private static $PARAM_ORDER_BY = 'orderBy';
    private static $PARAM_LIMIT = 'limit';
    private static $PARAM_OFFSET = 'offset';
    
    public function __construct(ParamFetcher $paramFetcher, FiltersNormalizerInterface $filtersNormalizer)
    {
        $this->paramFetcher = $paramFetcher;
        $this->filtersNormalizer = $filtersNormalizer;
    }
    
    public function getCustomParam($name, $isArray = false, $requirements = null)
    {
        $queryParam = new QueryParam();
        $queryParam->name = $name;
        $queryParam->array = $isArray;
        $queryParam->requirements = $requirements;
        $this->paramFetcher->addParam($queryParam);
        
        return $this->paramFetcher->get($name);
    }
    
    public function getFiltersParam()
    {
        $filtersParam = $this->getCustomParam(self::$PARAM_FILTERS, true);
        return $this->filtersNormalizer->normalizeFilters($filtersParam);
    }
    
    public function getOrderByParam()
    {
        return $this->getCustomParam(self::$PARAM_ORDER_BY, true);
    }
    
    public function getLimitParam($minLength = 5)
    {
        $limitParam = $this->getCustomParam(self::$PARAM_LIMIT);
        
        if ((int)$limitParam != $limitParam or (int)$limitParam < 1) {
            return $minLength;
        }
        return $limitParam;
    }
    
    public function getOffsetParam()
    {
        $offsetParam = $this->getCustomParam(self::$PARAM_OFFSET);
        
        if ((int)$offsetParam != $offsetParam or (int)$offsetParam < 1) {
            return 0;
        }
        return $offsetParam;
    }
}
