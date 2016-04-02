<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Domain\Entity\Biker;

interface BikersPatchActionInterface
{
    /**
     * @param integer $id
     * @param array $requestBodyParameters
     * @return Biker
     */
    public function patch($id, $requestBodyParameters);
}
