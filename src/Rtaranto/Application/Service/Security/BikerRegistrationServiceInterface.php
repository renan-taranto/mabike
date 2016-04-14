<?php
namespace Rtaranto\Application\Service\Security;

interface BikerRegistrationServiceInterface
{
    public function registerBiker($username, $email, $password);
}
