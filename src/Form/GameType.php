<?php

namespace App\Form;

use App\Model\GameModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'creator',
                TextType::class,
                [
                    'label' => 'app.new_game.pseudo.label',
                ]
            )
            ->add(
                'creatorColor',
                ChoiceType::class,
                [
                    'choices' => [
                        'app.new_game.creator_color.value.red' => 'red',
                        'app.new_game.creator_color.value.blue' => 'blue',
                        'app.new_game.creator_color.value.green' => 'green',
                        'app.new_game.creator_color.value.yellow' => 'yellow',
                        'app.new_game.creator_color.value.purple' => 'purple',
                        'app.new_game.creator_color.value.orange' => 'orange',
                    ],
                    'label' => 'app.new_game.creator_color.label',
                ]
            )
            ->add(
                'numberOfPlayers',
                ChoiceType::class,
                [
                    'choices' => [
                        'app.new_game.number_of_players.value.two' => 2,
                        'app.new_game.number_of_players.value.three' => 3,
                        'app.new_game.number_of_players.value.four' => 4,
                        'app.new_game.number_of_players.value.five' => 5,
                        'app.new_game.number_of_players.value.six' => 6,
                    ],
                    'label' => 'app.new_game.number_of_players.label',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'app.new_game.play.label',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameModel::class,
        ]);
    }
}
