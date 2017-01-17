<?php
/**
 * User: iyurin
 * Date: 13.11.16
 * Time: 14:46
 */

namespace VIS\Bundle\ApiBundle\Security\Authentication\Toke;


use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Class UserAccessToken
 * @package VIS\Bundle\ApiBundle\Security\Authentication\Toke
 */
class UserAccessToken extends AbstractToken
{
    public $value;

    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }
}