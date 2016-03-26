<?php
namespace Application\Service\Endpoint;

use Application\Service\Endpoint\Action\Biker\BikersPostAction;

class BikersEndpointService
{
    private $bikerPostAction;
    
    public function __construct(BikersPostAction $bikerPostAction)
    {
        $this->bikerPostAction = $bikerPostAction;
    }
    
    public function post($name, $email)
    {
        return $this->bikerPostAction->post($name, $email);
    }
}
