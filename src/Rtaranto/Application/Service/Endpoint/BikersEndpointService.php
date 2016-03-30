<?php
namespace Rtaranto\Application\Service\Endpoint;

use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersCgetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPostActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPutActionInterface;

class BikersEndpointService
{
    private $bikersPostAction;
    private $bikersGetAction;
    private $bikersCgetAction;
    private $bikersPutAction;
    
    public function __construct(
        BikersPostActionInterface $bikerPostAction,
        BikersGetActionInterface $bikersGetAtcion,
        BikersCgetActionInterface $bikersCgetAction,
        BikersPutActionInterface $bikersPutAction
    )
    {
        $this->bikersPostAction = $bikerPostAction;
        $this->bikersGetAction = $bikersGetAtcion;
        $this->bikersCgetAction = $bikersCgetAction;
        $this->bikersPutAction = $bikersPutAction;
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
    
    public function put($id, array $requestBodyParameters)
    {
        return $this->bikersPutAction->put($id, $requestBodyParameters);
    }
}
