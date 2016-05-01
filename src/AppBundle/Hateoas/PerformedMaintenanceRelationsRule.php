<?php
namespace AppBundle\Hateoas;

use Symfony\Component\HttpFoundation\RequestStack;

class PerformedMaintenanceRelationsRule implements ResourceRelationsRule
{
    private $requestStack;
    
    private static $GET_PATH_OIL_CHANGE = 'api_v1_get_motorcycle_oil_change';
    private static $PATCH_PATH_OIL_CHANGE = 'api_v1_patch_motorcycle_oil_change';
    private static $POST_PATH_OIL_CHANGE = 'api_v1_post_motorcycle_oil_change';
    
    private static $GET_PATH_REAR_TIRE_CHANGE = 'api_v1_get_motorcycle_reartire_change';
    private static $PATCH_PATH_REAR_TIRE_CHANGE = 'api_v1_patch_motorcycle_reartire_change';
    private static $POST_PATH_REAR_TIRE_CHANGE = 'api_v1_post_motorcycle_reartire_change';
    
    private static $GET_PATH_FRONT_TIRE_CHANGE = 'api_v1_get_motorcycle_fronttire_change';
    private static $PATCH_PATH_FRONT_TIRE_CHANGE = 'api_v1_patch_motorcycle_fronttire_change';
    private static $POST_PATH_FRONT_TIRE_CHANGE = 'api_v1_post_motorcycle_fronttire_change';

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function isAllowedForCurrentRoute()
    {
        $route = $this->requestStack->getCurrentRequest()->get('_route');
        
        switch ($route) {
            case self::$GET_PATH_OIL_CHANGE:
            case self::$PATCH_PATH_OIL_CHANGE:
            case self::$POST_PATH_OIL_CHANGE:
            case self::$GET_PATH_REAR_TIRE_CHANGE:
            case self::$PATCH_PATH_REAR_TIRE_CHANGE:
            case self::$POST_PATH_REAR_TIRE_CHANGE:
            case self::$GET_PATH_FRONT_TIRE_CHANGE:
            case self::$PATCH_PATH_FRONT_TIRE_CHANGE:
            case self::$POST_PATH_FRONT_TIRE_CHANGE:
                return true;
        }
        return false;
    }

}
