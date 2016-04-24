<?php
namespace Rtaranto\Presentation\Controller;

use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Rtaranto\Infrastructure\Repository\DoctrineOilChangeRepository;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;

class OilChangeWarningsConfigurationController extends BikerSubResourceController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warnings-configuration/oil-change")
     */
    public function getAction($motorcycleId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $em = $this->getDoctrine()->getManager();
        $oilChangeWarningObserverRepository = $em->getRepository(OilChangeWarningObserver::class);
        /* @var $oilChangeWarningObserver OilChangeWarningObserver */
        $oilChangeWarningObserver = $oilChangeWarningObserverRepository->
            findOneBy(array('motorcycle' => $motorcycleId));
        $isActive = $oilChangeWarningObserver->isActive();
        $kmsInAdvance = $oilChangeWarningObserver->getKmsInAdvance();
        
        $oilChangeRepository = new DoctrineOilChangeRepository($em);
        /* @var $oilChange OilChange */
        $oilChange = $oilChangeRepository->findOneByMotorcycle($motorcycleId);
        $kmsPerMaintenance = $oilChange->getKmsPerMaintenance();
        
        return array(
            'is_active' => $isActive,
            'kms_per_oil_change' => $kmsPerMaintenance,
            'kms_in_advance' => $kmsInAdvance
        );
    }
}
