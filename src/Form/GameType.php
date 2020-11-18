<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'pseudo',
                TextType::class,
                [
                    'label' => 'app.new_game.pseudo.label',
                ]
            )
            ->add(
                'number_players',
                ChoiceType::class,
                [
                    'choices' => [
                        'app.new_game.number_players.value.two' => 2,
                        'app.new_game.number_players.value.three' => 3,
                        'app.new_game.number_players.value.four' => 4,
                        'app.new_game.number_players.value.five' => 5,
                        'app.new_game.number_players.value.six' => 6,
                    ],
                    'label' => 'app.new_game.number_players.label',
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
}
