<?php
namespace Application\Service\Endpoint\Action\Biker;

use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BikersPostAction
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
        
        $errors = $this->validator->validate($biker);
        if (count($errors)) {
            throw new Exception($errors[0]->getMessage());
        }
        
        $biker = $this->bikerRepository->add($biker);
        return $biker;
    }
}
