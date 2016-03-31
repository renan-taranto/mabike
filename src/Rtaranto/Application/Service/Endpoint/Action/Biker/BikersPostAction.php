<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;

class BikersPostAction implements BikersPostActionInterface
{
    private $bikerRepository;
    private $validator;
    
    public function __construct(BikerRepositoryInterface $bikerRepository, ValidatorInterface $validator)
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
