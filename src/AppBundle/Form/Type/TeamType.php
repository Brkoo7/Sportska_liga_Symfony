<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Form\Model\Team;

class TeamType extends AbstractType
{
	private $entityManager;
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
            	'label' => 'Ime tima'
            ])
            ->add('address', TextType::class, [
            	'label' => 'Adresa tima'
            ])
       ;
    }
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	    	'method' => 'post',
	        'data_class' => Team::class,
	        'csrf_protection' => true,
	    ));
	}
}