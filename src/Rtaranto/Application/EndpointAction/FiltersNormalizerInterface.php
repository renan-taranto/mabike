<?php
namespace Rtaranto\Application\EndpointAction;

interface FiltersNormalizerInterface
{
    public function normalizeFilters(array $filters);
}
