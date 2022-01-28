<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): Void
    {
        $builder
            ->add('fullName',TextType::class, ['label' => 'Your name',])
            ->add('email',EmailType::class, ['label' => 'Your Email'])
            ->add('subject',TextType::class, ['label' => 'Subject'])
            ->add('message', CKEditorType::class, array(
                'constraints' => array(new \Symfony\Component\Validator\Constraints\NotBlank(['message' => 'Oops, your message is empty. It can\'t be sent.']),
                )));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
