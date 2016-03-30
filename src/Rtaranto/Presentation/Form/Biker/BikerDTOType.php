<?php
namespace Rtaranto\Presentation\Form\Biker;

use Rtaranto\Application\Dto\Biker\BikerDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BikerDTOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('allow_extra_fields' => true, 'data_class' => BikerDTO::class));
    }
}
