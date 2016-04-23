<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\Repository\OilChangeRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;

class DeletePerformedOilChangeAction extends DeleteSubResourceAction
{
    private $oilChangeRepository;
    private $performedOilChangeRepository;
    
    public function __construct(
        OilChangeRepositoryInterface $oilChangeRepository,
        PerformedOilChangeRepositoryInterface $performedOilChangeRepository
    ) {
        $this->oilChangeRepository = $oilChangeRepository;
        $this->performedOilChangeRepository = $performedOilChangeRepository;
    }
    
    protected function deleteSubResource($parentResourceId, $subResource)
    {
        /* @var $oilChange OilChange */
        $oilChange = $this->oilChangeRepository->findOneByMotorcycle($parentResourceId);
        $oilChange->removePerformedMaintenance($subResource);
        $this->oilChangeRepository->update($oilChange);
    }

    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedOilChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }
}

