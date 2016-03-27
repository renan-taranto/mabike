<?php
namespace Presentation\Controller;

use Application\Command\Biker\PostBikerCommand;
use Application\Dto\Biker\PostBiker;
use Exception;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use Presentation\Form\Biker\PostBikerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BikersController extends FOSRestController implements ClassResourceInterface
{
    public function postAction(Request $request)
    {
        $postBikeForm = $this->createForm(PostBikerType::class, new PostBiker());
        $postBikeForm->submit($request->request->all()); 

        if (!$postBikeForm->isValid()) {
            return $postBikeForm;
        }
        
        $bikerEndpointActions = $this->get('app.endpoint.bikers');
        $bikerCommand = new PostBikerCommand($bikerEndpointActions);
        
        try {
            $biker = $bikerCommand->execute($postBikeForm->getData());
        }
        catch (Exception $ex) {
            throw new BadRequestHttpException($ex->getMessage());
        }
        
        $view = $this->view($biker, Codes::HTTP_CREATED);
        
        return $view;
    }
}
