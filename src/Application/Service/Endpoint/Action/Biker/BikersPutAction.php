<?php
namespace Application\Service\Endpoint\Action\Biker;

use Application\Exception\ValidationFailedException;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Repository\BikerRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BikersPutAction implements BikersPutActionInterface
{
    private $bikerRepository;
    private $validator;
    
    public function __construct(BikerRepository $bikerRepository, ValidatorInterface $validator)
    {
        $this->bikerRepository = $bikerRepository;
        $this->validator = $validator;
    }
    
    public function put($id, $name, $email)
    {
        $biker = $this->bikerRepository->get($id);
        
        if (empty($biker)) {
           throw new NotFoundHttpException(
                sprintf('The Biker resource of id \'%s\' was not found. PUT method must be used only for updating.', $id));
        }
        
        $biker->setName($name);
        $biker->setEmail($email);
        
        if (!$this->validator->isValid($biker)) {
            $errors = $this->validator->getErrors($biker);
            throw new ValidationFailedException($errors);
        }
        
        return $this->bikerRepository->update($biker);
    }
}
