<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Course;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class, ['attr' => array('class' => "select2"),'label' => 'Prénom'])
            ->add('lastName',TextType::class, ['label' => 'Nom'])
            ->add('birthDay', null, [
                'widget' => 'single_text',
                'label' => 'Date d\'anniversaire',
            ])
            ->add('promo', ChoiceType::class, [
                'choices' => $this->getDateRange(date('Y')-10,date('Y')+10)
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil (JPG/PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPG ou PNG.',
                    ]),
                ],
            ])
            ->add('company',TextType::class, ['label' => 'Entreprise'])
            ->add('description')
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'title',
                'label' => 'Formation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }

    public function getDateRange($start, $end): array
    {
        $years = [];
        for ($year = $start; $year <= $end; $year++) {
            $years[$year] = $year;
        }
        return $years;
    }
}
