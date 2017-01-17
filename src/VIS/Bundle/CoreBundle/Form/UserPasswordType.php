<?php
/**
 * User: iyurin
 * Date: 15.11.16
 * Time: 0:31
 */

namespace VIS\Bundle\CoreBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserPasswordType
 * @package VIS\Bundle\CoreBundle\Form
 */
class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'There is password matching problem. Perhaps you sent an invalid field.',
                'required' => true,
                'first_name' => 'password',
                'second_name' => 'confirmPassword',
            ))
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VIS\Bundle\CoreBundle\Entity\User',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'user_password';
    }
}