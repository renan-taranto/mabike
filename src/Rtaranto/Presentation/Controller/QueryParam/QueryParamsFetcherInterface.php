<?php
namespace Rtaranto\Presentation\Controller\QueryParam;

interface QueryParamsFetcherInterface
{
    public function getCustomParam($name, $isArray = false, $requirements = null);
    public function getFiltersParam();
    public function getOrderByParam();
    public function getLimitParam();
    public function getOffsetParam();
}
