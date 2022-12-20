<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label' => "E-mail *",
                'attr' => ['placeholder' => "e-mail"],
                'constraints'=> [
                    new NotBlank(['message'=> 'Le mail est obligatoire']),
                    new Email(['message' => 'Indiquez un mail valide'])
                ],
                'empty_data' => '',
                'required' => false
            ])
            ->add('lastname',TextType::class,[
                'label' => "Nom de famille *",
                'attr' => ['placeholder' => "nom de famille"],
                "constraints"=>[
                    new NotBlank(['message'=>'Indiquez votre nom'])
                ],
                'empty_data' => '',
                'required' => false
            ])
            ->add('firstname',TextType::class,[
                'label' => "Prénom *",
                'attr' => ['placeholder' => "prénom"],
                "constraints"=>[
                    new NotBlank(['message'=>'Indiquez votre prénom'])
                ],
                'empty_data' => '',
                'required' => false
            ])
            ->add('phone',TextType::class,[
                'label' => "Numéro de téléphone",
                'attr' => ['placeholder' => "téléphone"],
                'empty_data' => '',
                'required' => false
            ])
            ->add('file',FileType::class,[
                'label' => "Photo de profil",
                'attr' => ['placeholder' => "Choisissez une photo"],
                'mapped' => false,
                "constraints" => [
                    new File([
                        "mimeTypes" => ['image/*'],
                        "mimeTypesMessage" => "Veuillez choisir une image",
                        "maxSize" => "2m",
                        "maxSizeMessage" => "L'image ne doit pas faire plus de 2m"
                    ])
                ],
                'required' => false
            ])
            ->add('submit',SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary',
                    'style' => "display:block;margin:0 auto;margin-top:40px;"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
