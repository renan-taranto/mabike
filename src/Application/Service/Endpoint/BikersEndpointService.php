<?php
namespace Application\Service\Endpoint;

use Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Application\Service\Endpoint\Action\Biker\BikersPostActionInterface;

class BikersEndpointService
{
    private $bikersPostAction;
    private $bikersGetAction;
    
    public function __construct(BikersPostActionInterface $bikerPostAction, BikersGetActionInterface $bikersGetAtcion)
    {
        $this->bikersPostAction = $bikerPostAction;
        $this->bikersGetAction = $bikersGetAtcion;
    }
    
    public function post($name, $email)
    {
        return $this->bikersPostAction->post($name, $email);
    }
    
    public function get($id)
    {
        return $this->bikersGetAction->get($id);
    }
}
