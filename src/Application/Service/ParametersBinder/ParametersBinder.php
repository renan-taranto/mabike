<?php
namespace Application\Service\ParametersBinder;

interface ParametersBinder
{
    public function bind(array $parameters, $object);
}
