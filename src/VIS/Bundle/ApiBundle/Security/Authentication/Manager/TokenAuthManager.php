<?php
/**
 * User: iyurin
 * Date: 13.11.16
 * Time: 14:45
 */

namespace VIS\Bundle\ApiBundle\Security\Authentication\Manage;


use Doctrine\ORM\EntityManager;
use VIS\Bundle\ApiBundle\Components\Http\Storage\StorageInterface;
use VIS\Bundle\CoreBundle\Entity\User;

/**
 * Class TokenAuthManager
 * @package VIS\Bundle\ApiBundle\Security\Authentication\Manage
 */
class TokenAuthManager
{
    /**
     * @var StorageInterface
     */
    private $storageHandler;
    /**
     * @var
     */
    private $entityManager;
    /**
     * @var EntityManager
     */
    private $expiredTime;

    /**
     * @param StorageInterface $storageHandler
     * @param EntityManager $entityManager
     * @param $expiredTime
     */
    public function __construct(StorageInterface $storageHandler, EntityManager $entityManager, $expiredTime)
    {
        $this->storageHandler = $storageHandler;
        $this->entityManager  = $entityManager;
        $this->expiredTime    = $expiredTime;
    }

    public function getUserByToken($token)
    {
        $tokenData = $this->storageHandler->read($token);

        if ($tokenData && $tokenData->getUserId())
        {
            return $this->entityManager->getRepository('VISCoreBundle:User')->find($tokenData->getUserId());
        }

        return false;
    }

    public function setAccessToken(User $user)
    {
        $accessToken = $this->generateAccessToken();
        $this->storageHandler->write($accessToken, $user->getId());

        return $accessToken;
    }

    /**
     * Remove document by $token
     */
    public function logout($token)
    {
        $this->storageHandler->remove($token);
    }

    /**
     * Remove document by $token
     */
    public function logoutAll(User $user)
    {
        $this->storageHandler->removeByUserId($user->getId());
    }

    public function cleanExpiredToken()
    {
        $data = $this->storageHandler->getTokenList();

        while ($data->hasNext())
        {
            $tokenObj = $data->getNext();

            if ( ! $this->isValid($tokenObj['token']))
            {
                $this->storageHandler->remove($tokenObj['token']);
            }
        }
    }

    public function isValid($accessToken)
    {
        $expiryTime = \DateInterval::createFromDateString($this->expiredTime);
        $expirySeconds = $expiryTime->d*86400 + $expiryTime->h*3600+ $expiryTime->i*60 + $expiryTime->s;

        if ( ! $tokenData = $this->storageHandler->read($accessToken))
        {
            return false;
        }

        $createdDate =  $tokenData->getCreatedAt()->getTimestamp();
        $expiredDate = $expirySeconds + $createdDate;
        $now = new \DateTime("now");

        return $now->getTimestamp() < $expiredDate;
    }

    private function generateAccessToken()
    {
        return uniqid('', true);
    }
}