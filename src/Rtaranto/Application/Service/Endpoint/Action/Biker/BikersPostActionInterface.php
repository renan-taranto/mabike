<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

interface BikersPostActionInterface
{
    /**
     * @param array $requestBodyParameters
     * @return Biker $biker
     */
    public function post(array $requestBodyParameters);
}
