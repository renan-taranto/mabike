<?php
namespace Rtaranto\Application\Service\Validator;

use Rtaranto\Application\Exception\ValidationFailedException;

interface ValidatorInterface
{
    /**
     * @param object $object
     * @throws ValidationFailedException
     */
    public function throwValidationFailedIfNotValid($object);
}
