<?php

namespace App\Form;

use App\Entity\Hero;
use App\Validator\Constraints\Bookable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookHeroType extends AbstractType {

    private $validator;

    public function __construct(ValidatorInterface $validator) {
        $this->validator = $validator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'label' => 'booking.name',
                'constraints' => [
                    new Length(['min' => 3, 'minMessage' => 'booking.name.short'])
                ],
                'attr' => ['autofocus' => true],
            ])
            ->add('email', EmailType::class, [
                'label' => 'booking.email',
                'constraints' => [
                    new Email(['message' => 'booking.email.invalid'])
                ],
            ])
            ->add('hero', EntityType::class, [
                'label' => 'booking.hero',
                'class' => Hero::class,
                'choice_label' => 'nameStatus',
                // Aktuell nicht buchbare Helden werden ausgegraut
                'choice_attr' => function(Hero $hero) {
                    return ['disabled' => !$hero->isBookable()];
                },
                // Buchbarkeit wird in jedem Fall server-seitig überprüft
                'constraints' => [
                    new Bookable(['message' => 'booking.hero.booked_out'])
                ],
                'placeholder' => 'booking.hero.placeholder',
            ])
            ->add('date', DateType::class, [
                'label' => 'booking.date',
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual(['value' => 'today', 'message' => 'booking.date.past'])
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'booking.msg',
                'constraints' => [
                    new Length(['max' => 500, 'maxMessage' => 'booking.msg.long'])
                ],
            ]);

        // Da das Formular mit einem Start-Wert versehen werden kann, prüfen wir schon direkt nach dem serverseitigen
        // Befüllen (Event: POST_SET_DATA), ob die Vorauswahl gültig ist. Für die Gültigkeitsprüfung wird automatisch
        // die oben definierten Constraints verwendet ...
        $resetFieldOnViolation = function (FormEvent $event) {
            $field = $event->getForm();
            foreach($this->validator->validate($field) as $violation) {
                // Fehlermeldung anzeigen
                $field->addError(new FormError($violation->getMessage()));
                // Vorauswahl zurücknehmen
                $field->setData(null);
            }
        };
        // ... diese Validierung machen wir aber nur für das Helden-Feld.
        $builder->get('hero')->addEventListener(FormEvents::POST_SET_DATA, $resetFieldOnViolation);
    }
}
