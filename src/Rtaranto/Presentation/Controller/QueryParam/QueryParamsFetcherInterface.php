<?php
namespace Rtaranto\Presentation\Controller\QueryParam;

interface QueryParamsFetcherInterface
{
    public function getParam($name, $isArray = false, $requirements = null);
}
