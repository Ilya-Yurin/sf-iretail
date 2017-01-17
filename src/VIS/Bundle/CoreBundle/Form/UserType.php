<?php
/**
 * User: iyurin
 * Date: 15.11.16
 * Time: 0:54
 */

namespace VIS\Bundle\CoreBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VIS\Bundle\CoreBundle\Entity\User;

/**
 * Class UserType
 * @package VIS\Bundle\CoreBundle\Form
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array())
            ->add('lastName', 'text', array())
            ->add('email', 'email', array())
            ->add('password', 'password', array(
            ))
            ->add('status', 'choice', array(
                'choices' => array(
                    User::STATUS_ACTIVE => User::STATUS_ACTIVE,
                    User::STATUS_INACTIVE => User::STATUS_INACTIVE,
                )
            ))
            ->add('userType', 'choice', array(
                'choices' => array(
                    User::TYPE_USER => User::TYPE_USER,
                    User::TYPE_MANAGER_ADMIN => User::TYPE_MANAGER_ADMIN,
                    User::TYPE_SUPER_ADMIN => User::TYPE_SUPER_ADMIN,
                )
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
        return 'user';
    }
}