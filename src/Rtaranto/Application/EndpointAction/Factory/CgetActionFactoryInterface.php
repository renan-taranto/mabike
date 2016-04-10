<?php
namespace Rtaranto\Application\EndpointAction\Factory;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;

interface CgetActionFactoryInterface
{
    public function createCgetAction(ParamFetcherInterface $paramFetcher);
}
