<?php
namespace AppBundle\Hateoas;

interface ResourceRelationsRule
{
    public function isAllowedForCurrentRoute();
}
