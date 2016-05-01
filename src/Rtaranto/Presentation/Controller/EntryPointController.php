<?php
namespace Rtaranto\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EntryPointController extends Controller
{
    /**
     * @FOS\RestBundle\Controller\Annotations\Get("/")
     */
    public function entryPointAction(Request $request)
    {
        return array(
            '_links' => array(
                'motorcycles' => array('href' => $this->generateUrl('api_v1_get_motorcycles'))
            )
        );
    }
}
