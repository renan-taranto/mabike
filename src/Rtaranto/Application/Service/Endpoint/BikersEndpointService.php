<?php
namespace Rtaranto\Application\Service\Endpoint;

use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersCgetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPutActionInterface;

class BikersEndpointService
{
    private $bikersGetAction;
    private $bikersCgetAction;
    private $bikersPutAction;
    
    public function __construct(
        BikersGetActionInterface $bikersGetAtcion,
        BikersCgetActionInterface $bikersCgetAction
    )
    {
        $this->bikersGetAction = $bikersGetAtcion;
        $this->bikersCgetAction = $bikersCgetAction;
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
