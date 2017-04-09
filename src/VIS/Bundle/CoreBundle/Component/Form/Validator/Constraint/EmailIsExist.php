<?php
/**
 * User: iyurin
 * Date: 09.04.17
 * Time: 15:58
 */

namespace VIS\Bundle\CoreBundle\Component\From\Validator\Constrains;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailIsExist extends Constraint
{
    public $emailIsNotExist = "Email %email% is not exist";

    public function __construct($options = array())
    {
        parent::__construct($options);
    }

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}