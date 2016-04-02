<?php
namespace Rtaranto\Application\Command\Biker;

use Rtaranto\Application\Dto\Biker\BikerDTO;
use Rtaranto\Domain\Entity\Biker;

class PatchBikerCommand
{
    private $biker;
    
    public function __construct(Biker $biker)
    {
        $this->biker = $biker;
    }
    
    public function execute(BikerDTO $bikerDTO)
    {
        if (!empty($bikerDTO->getName())) {
            $this->biker->setName($bikerDTO->getName());
        }
        if (!empty($bikerDTO->getEmail())) {
            $this->biker->setEmail($bikerDTO->getEmail());
        }
    }
}
