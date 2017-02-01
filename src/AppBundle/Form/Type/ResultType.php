<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\Model\Result;
use AppBundle\Entity\Game;
use AppBundle\Repository\GameRepository;

class ResultType extends AbstractType
{
	private $entityManager;
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
    		->add('games', EntityType::class, [
                'class' => Game::class,
                'query_builder' => function (GameRepository $repo) {
        			return $repo->createGameForEnterResultQueryBuilder();
    			},
                'label' => 'Raspored'
            ])
            ->add('homeGoals', NumberType::class, [
                'label' => 'Domaći golovi'
            ])
            ->add('awayGoals', NumberType::class, [
                'label' => 'Gosti golovi'
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	    	'method' => 'post',
	        'data_class' => Result::class, 
	    ));
	}
}

