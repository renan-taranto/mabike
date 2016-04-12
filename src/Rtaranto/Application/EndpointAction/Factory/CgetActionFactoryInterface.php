<?php
namespace Rtaranto\Application\EndpointAction\Factory;

use FOS\RestBundle\Request\ParamFetcherInterface;

interface CgetActionFactoryInterface
{
    public function createCgetAction(ParamFetcherInterface $paramFetcher);
}
