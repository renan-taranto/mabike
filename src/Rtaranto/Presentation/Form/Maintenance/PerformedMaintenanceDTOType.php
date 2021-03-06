<?php
namespace Rtaranto\Presentation\Form\Maintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerformedMaintenanceDTOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kmsDriven')
            ->add('date', TextType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('allow_extra_fields' => true, 'data_class' => PerformedMaintenanceDTO::class));
    }
}
