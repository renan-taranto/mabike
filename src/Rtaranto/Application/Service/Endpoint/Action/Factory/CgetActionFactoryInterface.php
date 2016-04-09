<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Factory;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;

interface CgetActionFactoryInterface
{
    public function createCgetAction(EntityManagerInterface $em, ParamFetcherInterface $paramFetcher);
}
