<?php
namespace Application\Service\Endpoint;

use Application\Service\Endpoint\Action\Biker\BikersPostActionInterface;

class BikersEndpointService
{
    private $bikersPostAction;
    
    public function __construct(BikersPostActionInterface $bikerPostAction)
    {
        $this->bikersPostAction = $bikerPostAction;
    }
    
    public function post($name, $email)
    {
        return $this->bikersPostAction->post($name, $email);
    }
}
