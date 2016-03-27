<?php
namespace Application\Service\Endpoint\Action\Biker;

use Application\Exception\ValidationFailedException;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;

class BikersPostAction implements BikersPostActionInterface
{
    private $bikerRepository;
    private $validator;
    
    public function __construct(BikerRepository $bikerRepository, ValidatorInterface $validator)
    {
        $this->bikerRepository = $bikerRepository;
        $this->validator = $validator;
    }
    
    public function post($name, $email)
    {
        $biker = new Biker($name, $email);
        
        if (!$this->validator->isValid($biker)) {
            throw new ValidationFailedException($this->validator->getErrors($biker));
        }
        
        $biker = $this->bikerRepository->add($biker);
        return $biker;
    }
}
