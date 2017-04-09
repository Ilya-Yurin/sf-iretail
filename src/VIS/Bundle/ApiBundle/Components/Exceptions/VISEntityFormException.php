<?php
/**
 * Created by PhpStorm.
 * User: iyurin
 * Date: 09.04.17
 * Time: 19:49
 */

namespace VIS\Bundle\ApiBundle\Components\Exeptions;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class VISEntityFormException
 * @package VIS\Bundle\ApiBundle\Components\Exeptions
 */
class VISEntityFormException extends BadRequestHttpException
{
    /**
     * @var Form
     */
    private $form;


    /**
     * APPEntityFormException constructor.
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
        parent::__construct(null, null, 0);
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }
}