<?php
/**
 * User: iyurin
 * Date: 13.11.16
 * Time: 14:42
 */

namespace VIS\Bundle\ApiBundle\Security\Listner;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SensioLabs\Security\SecurityChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use VIS\Bundle\ApiBundle\Security\Authentication\Toke\UserAccessToken;

/**
 * Class TokenAuthListner
 * @package VIS\Bundle\ApiBundle\Security\Listner
 */
class TokenAuthListner implements ListenerInterface
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;
    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    public function __construct(TokenStorage $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($this->isValid($request))
        {
            preg_match('/Bearer ([^"]+)/', $request->headers->get('I-TokenAuth'), $matches);

            $token = new UserAccessToken();

            $token->value = $matches[1];

            try
            {
                $authToken = $this->authenticationManager->authenticate($token);
                $this->tokenStorage->setToken($authToken);

                return;
            }
            catch (AuthenticationException $failed)
            {
                $response = new Response();
                $response->setStatusCode(Response::HTTP_FORBIDDEN);
                $response->setContent(json_encode($failed->getMessage()));
                $event->setResponse($response);
            }
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isValid(Request $request)
    {
        if (! $request->headers->has('I-TokenAuth'))
            return false;

        preg_match('/Bearer ([^"]+)/', $request->headers->get('I-TokenAuth'), $matches);

        if (count($matches) == 0)
            return false;

        return true;
    }
}