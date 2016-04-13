<?php
namespace Rtaranto\Presentation\Form\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MotorcycleDTOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model')
            ->add('kmsDriven')
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('allow_extra_fields' => true, 'data_class' => MotorcycleDTO::class));
    }
}
