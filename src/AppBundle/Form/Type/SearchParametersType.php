<?php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Form\Model\SearchParameters;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SearchParametersType extends AbstractType
{
    private $entityManager;
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, array(
                'widget' => 'single_text',
                'label' => 'Datum od',
                'attr' => ['class' => 'js-datepicker'],
                'html5' => false
            ))
            ->add('dateTo', DateType::class, array(
                'widget' => 'single_text',
                'label' => 'Datum do',
                'attr' => ['class' => 'js-datepicker'],
                'html5' => false
            ))
       ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'get',
            'data_class' => SearchParameters::class,
            'csrf_protection' => false,
        ));
    }
}