<?php
/**
 * User: iyurin
 * Date: 14.11.16
 * Time: 0:41
 */

namespace VIS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AccessTokenType
 * @package VIS\Bundle\CoreBundle\Form
 */
class AccessTokenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailAddress', 'email', array(
                'required' => true,
                'constraints' => array(
                    new NotBlank
                )
            ))
            ->add('password', 'password', array(
                'required' => true,
                'constraints' => array(
                    new NotBlank
                )
            ))
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return null;
    }
}