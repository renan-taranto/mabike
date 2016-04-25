<?php
namespace Rtaranto\Application\EndpointAction;

interface InputProcessorInterface
{
    public function processInput($data, $targetDTO);
    public function processInputIgnoringMissingFields($data, $targetDTO);
}
