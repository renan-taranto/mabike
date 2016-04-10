<?php
namespace Rtaranto\Application\EndpointAction;

use Rtaranto\Domain\Entity\Biker;

interface PatchActionInterface
{
    /**
     * @param integer $id
     * @param array $requestBodyParameters
     * @return Biker
     */
    public function patch($id, $requestBodyParameters);
}
