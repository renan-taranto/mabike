<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

interface BikersPostActionInterface
{
    /**
     * @param string $name
     * @param string $email
     */
    public function post($name, $email);
}
