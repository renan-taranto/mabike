<?php
namespace Rtaranto\Application\Service\ParametersBinder;

use Symfony\Component\Form\FormFactoryInterface;

class FormParametersBinder implements ParametersBinder
{
    private $formFactory;
    private $formTypeClassName;
    
    public function __construct(FormFactoryInterface $formFactory, $formTypeClassName)
    {
        $this->formFactory = $formFactory;
        $this->formTypeClassName = $formTypeClassName;
    }
    
    public function bind(array $parameters, $object)
    {
        $form = $this->formFactory->create($this->formTypeClassName, $object);
        $form->submit($parameters);
        return $form->getData();
    }
}
