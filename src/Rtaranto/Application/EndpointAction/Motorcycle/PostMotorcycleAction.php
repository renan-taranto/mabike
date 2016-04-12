<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\PostActionInterface;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\Security\Core\User\User;

class PostMotorcycleAction implements PostActionInterface
{
    private $user;
    private $motorcycleRepository;
    
    public function __construct(User $user, MotorcycleRepositoryInterface $motorcycleRepository, BikerRepositoryInterface $bikerRepository)
    {
        $this->user = $user;
        $this->motorcycleRepository = $motorcycleRepository;
        $bikerRepository->findOneByUser($user);
    }
    
    public function post(array $requestBodyParameters)
    {
//        if ($user->get)
//        $username = 'username';
//        $email = 'useremail@email.com';
//        $password = 'pass';
//        $user = new User($username, $email, $password);
//        
//        $bikerName = 'biker';
//        $bikerEmail = 'biker@email.com';
//        $biker = new Biker($bikerName, $bikerEmail);
//        $biker->setUser($user);
//        
//        $model = 'YBR';
//        $kmsDriven = 43278;
//        $motorcycle = new Motorcycle($model, $kmsDriven);
//        $motorcycle->setBiker($biker);
//        
//        return $motorcycle;
    }
}
