<?php
namespace Application\Service\Endpoint;

use Application\Service\Endpoint\Action\Biker\BikersCgetActionInterface;
use Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Application\Service\Endpoint\Action\Biker\BikersPostActionInterface;

class BikersEndpointService
{
    private $bikersPostAction;
    private $bikersGetAction;
    private $bikersCgetAction;
    
    public function __construct(
        BikersPostActionInterface $bikerPostAction,
        BikersGetActionInterface $bikersGetAtcion,
        BikersCgetActionInterface $bikersCgetAction
    )
    {
        $this->bikersPostAction = $bikerPostAction;
        $this->bikersGetAction = $bikersGetAtcion;
        $this->bikersCgetAction = $bikersCgetAction;
    }
    
    public function post($name, $email)
    {
        return $this->bikersPostAction->post($name, $email);
    }
    
    public function get($id)
    {
        return $this->bikersGetAction->get($id);
    }
    
    public function getAll()
    {
        return $this->bikersCgetAction->get();
    }
}
