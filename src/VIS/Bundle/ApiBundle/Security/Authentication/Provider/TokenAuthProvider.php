<?php
/**
 * User: iyurin
 * Date: 13.11.16
 * Time: 14:45
 */

namespace VIS\Bundle\ApiBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use VIS\Bundle\ApiBundle\Security\Authentication\Manage\TokenAuthManager;
use VIS\Bundle\ApiBundle\Security\Authentication\Toke\UserAccessToken;

/**
 * Class TokenAuthProvider
 * @package VIS\Bundle\ApiBundle\Security\Authentication\Provider
 */
class TokenAuthProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;
    /**
     * @var
     */
    private $cacheDir;
    /**
     * @var TokenAuthManager
     */
    private $tokenManager;

    /**
     * @param UserProviderInterface $userProvider
     * @param $cacheDir
     * @param TokenAuthManager $tokenManager
     */
    public function __construct(UserProviderInterface $userProvider, $cacheDir, TokenAuthManager $tokenManager)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param TokenInterface $token
     * @return UserAccessToken
     */
    public function authenticate(TokenInterface $token)
    {
        if ($this->tokenManager->isValid($token->value)) {
            if ($user = $this->tokenManager->getUserByToken($token->value)) {
                $authenticatedToken = new UserAccessToken($user->getRoles());
                $authenticatedToken->setUser($user);
                $authenticatedToken->value = $token->value;

                return $authenticatedToken;
            }
        }

        throw new AuthenticationException('The token authentication failed.');
    }

    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof UserAccessToken;
    }
}