<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('username', TextType::class,['label'=>'Username'])
            ->add('email', EmailType::class,['label'=>'E-Mail'])
            ->add('plainPassword', RepeatedType::class,[
                'type'=>PasswordType::class,
                'first_options' => ['label'=>'Password'],
                'second_options' => ['label'=>'Repeated Password']
            ])
            ->add('fullname', TextType::class,['label'=>'Fullname'])
            ->add('termsAgreed', CheckboxType::class,[
                'mapped'=> false,
                'constraints' => new isTrue(),
                'label' => 'I agree to the terms of service'
            ])

            ->add('Register',SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

}