<?php
/**
 * User: iyurin
 * Date: 09.04.17
 * Time: 15:59
 */

namespace VIS\Bundle\CoreBundle\Component\From\Validator\Constrains;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class EmailIsExistValidator
 * @package VIS\Bundle\CoreBundle\Component\From\Validator\Constrains
 */
class EmailIsExistValidator extends ConstraintValidator
{
    protected $orm;

    public function __construct(Registry $orm)
    {
        $this->orm = $orm;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value){
            return;
        }

        if (!$this->orm->getRepository('VISCoreBundle:User')->findOneByEmailAddress($value)){
            $this->context->addViolation($constraint->emailIsNotExist, array('%email%' => $value));
        }
    }
}