<?php
namespace Rtaranto\Application\ParametersBinder;

interface ParametersBinderInterface
{
    public function bind(array $parameters, $object);
    public function bindIgnoringMissingFields(array $parameters, $object);
}
