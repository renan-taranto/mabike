<?php
namespace Rtaranto\Presentation\Controller\QueryParam;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;

class QueryParamsFetcher implements QueryParamsFetcherInterface
{
    private $paramFetcher;
    
    public function __construct(ParamFetcher $paramFetcher)
    {
        $this->paramFetcher = $paramFetcher;
    }
    
    public function getParam($name, $isArray = false, $requirements = null)
    {
        $queryParam = new QueryParam();
        $queryParam->name = $name;
        $queryParam->array = $isArray;
        $queryParam->requirements = $requirements;
        $this->paramFetcher->addParam($queryParam);
        
        return $this->paramFetcher->get($name);
    }
}
