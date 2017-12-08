<?php
namespace App\Form;

use App\Entity\Availability;
use App\Entity\Hero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeroType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', null, ['label' => 'Name'])
            ->add('price', null, ['label' => 'Preis'])
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(Availability::getStates(), Availability::getStates()),
            ])
            ->add('text', null, ['label' => 'Beschreibungstext']);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefault('data_class', Hero::class);
    }
}
