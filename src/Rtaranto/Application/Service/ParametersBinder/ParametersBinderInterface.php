<?php
namespace Rtaranto\Application\Service\ParametersBinder;

interface ParametersBinderInterface
{
    public function bind(array $parameters, $object);
}
