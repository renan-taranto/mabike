<?php
namespace Rtaranto\Presentation\Controller\QueryParam;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;

class QueryParamsFetcher implements QueryParamsFetcherInterface
{
    private $paramFetcher;
    private static $PARAM_FILTERS = 'filters';
    private static $PARAM_ORDER_BY = 'orderBy';
    private static $PARAM_LIMIT = 'limit';
    private static $PARAM_OFFSET = 'offset';
    
    public function __construct(ParamFetcher $paramFetcher)
    {
        $this->paramFetcher = $paramFetcher;
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
        return $this->getCustomParam(self::$PARAM_FILTERS, true);
    }
    
    public function getOrderByParam()
    {
        return $this->getCustomParam(self::$PARAM_ORDER_BY, true);
    }
    
    public function getLimitParam()
    {
        return $this->getCustomParam(self::$PARAM_LIMIT);
    }
    
    public function getOffsetParam()
    {
        return $this->getCustomParam(self::$PARAM_OFFSET);
    }
}
