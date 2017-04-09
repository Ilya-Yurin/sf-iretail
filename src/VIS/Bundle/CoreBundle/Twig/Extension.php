<?php
/**
 * User: iyurin
 * Date: 09.04.17
 * Time: 16:13
 */

namespace VIS\Bundle\CoreBundle\Twig;

/**
 * Class Extension
 * @package VIS\Bundle\CoreBundle\Twig
 */
class Extension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('ebase64', 'base64_encode'),
            new \Twig_SimpleFilter('dbase64', 'base64_decode'),
            new \Twig_SimpleFilter('eutf8', 'utf8_encode'),
            new \Twig_SimpleFilter('dutf8', 'utf8_decode'),
        );
    }

    public function getTests()
    {
        return array(
            'instanceof' => new \Twig_Function_Method($this, 'isInstanceof')
        );
    }

    /**
     * Usage {% if value is instanceof('DateTime') %}
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceof($var, $instance) {
        return  $var instanceof $instance;
    }

    public function getName()
    {
        return 'extension';
    }
}