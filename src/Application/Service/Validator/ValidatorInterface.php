<?php
namespace Application\Service\Validator;

interface ValidatorInterface
{
    public function isValid($object);
    public function getErrors($object);
    public function throwValidationFailedIfNotValid($object);
}
