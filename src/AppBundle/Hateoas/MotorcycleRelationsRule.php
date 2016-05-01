<?php
namespace AppBundle\Hateoas;

use Symfony\Component\HttpFoundation\RequestStack;

class MotorcycleRelationsRule implements ResourceRelationsRule
{
    private $requestStack;
    
    private static $GET_PATH = 'api_v1_get_motorcycle';
    private static $PATCH_PATH = 'api_v1_patch_motorcycle';
    private static $POST_PATH = 'api_v1_post_motorcycle';
    
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    public function isAllowedForCurrentRoute()
    {
        $route = $this->requestStack->getCurrentRequest()->get('_route');
        switch ($route) {
            case self::$GET_PATH:
            case self::$PATCH_PATH:
            case self::$POST_PATH:
                return true;
        }
        return false;
    }
}
