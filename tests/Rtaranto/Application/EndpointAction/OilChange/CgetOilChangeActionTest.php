<?php
namespace Tests\Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\OilChange\CgetOilChangeAction;
use Rtaranto\Domain\Entity\Repository\MaintenancePerformerRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetOilChangeActionTest extends \PHPUnit_Framework_TestCase
{
    public function testCgetReturnsCollection()
    {
        $motorcycleId = 1;
        $maintenancePerformerRepository = $this->prophesize(MaintenancePerformerRepositoryInterface::class);
        $maintenancePerformerRepository->findAllPerformedOilChangesByMotorcycle($motorcycleId, null, null, null, null)
            ->willReturn(array());
        $queryParamsFetcher = $this->prophesize(QueryParamsFetcherInterface::class);
        
        $cgetOilChangeAction = new CgetOilChangeAction(
            $maintenancePerformerRepository->reveal(),
            $queryParamsFetcher->reveal()
        );
        $oilChangeCollection = $cgetOilChangeAction->cGet($motorcycleId);
        $this->assertInternalType('array', $oilChangeCollection);
    }
}
