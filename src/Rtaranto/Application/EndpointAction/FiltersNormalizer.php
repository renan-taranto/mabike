<?php
namespace Rtaranto\Application\EndpointAction;

use DateTime;

class FiltersNormalizer implements FiltersNormalizerInterface
{
    public function normalizeFilters(array $filters)
    {
        foreach ($filters as $k => $v) {
            if ($k == 'date') {
                $filters[$k] = new DateTime($v);
            }
        }
        $keys = array_map(array($this, 'normalizeToCamelCase'), array_keys($filters));
        return array_combine($keys, $filters);
    }
    
    protected function normalizeToCamelCase($string)
    {
        return lcfirst(str_replace(' ','',ucwords(str_replace('_',' ',$string))));
    }
}
