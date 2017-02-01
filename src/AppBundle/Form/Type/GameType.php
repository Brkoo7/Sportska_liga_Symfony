<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use AppBundle\Form\Model\Game;
use AppBundle\Repository\GameRepository;

class GameType extends AbstractType
{
	private $entityManager;
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('homeTeam', ChoiceType::class, [
                'choices' => $this->getTeamsChoices(),
                'expanded' => false,
                'multiple' => false,
                'label' => 'Domaći tim'
            ])
            ->add('awayTeam', ChoiceType::class, [
            	'choices' => $this->getTeamsChoices(),
            	'expanded' => false,
            	'multiple' => false,
            	'label' => 'Gostujući tim'
            ])
            ->add('date_', DateType::class, array(
    			'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                'html5' => false,
                'label' => 'Datum'
    		))
        ;
    }
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	    	'method' => 'post',
	        'data_class' => Game::class, 
	    ));
	}
	private function getTeamsChoices()
	{	
		$allTeams = $this->entityManager->getRepository('AppBundle:Team')->findAll();
		$teamChoices = [];
		foreach ($allTeams as $team)
			$teamChoices[$team->getName()] = $team->getName();
		return $teamChoices;
	}
}