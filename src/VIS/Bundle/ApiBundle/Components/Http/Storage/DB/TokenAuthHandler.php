<?php
/**
 * User: iyurin
 * Date: 13.11.16
 * Time: 12:27
 */

namespace VIS\Bundle\ApiBundle\Components\Http\Storage\DB;


use Doctrine\Common\Persistence\ObjectManager;
use VIS\Bundle\ApiBundle\Components\Http\Storage\StorageInterface;
use VIS\Bundle\CoreBundle\Entity\UserToken;

/**
 * Class TokenAuthHandler
 * @package VIS\Bundle\ApiBundle\Components\Http\Storage\DB
 */
class TokenAuthHandler implements StorageInterface
{
    /**
     * @var ObjectManager
     */
    private $om;
    /**
     * @var string
     */
    private $entityClass;
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @param ObjectManager $om
     * @param $entityClass
     */
    public function __construct(ObjectManager $om, $entityClass)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
    }

    protected function createToken() {
        return new $this->entityClass();
    }

    public function write($tokenValue, $userId)
    {
        $token = $this->createToken();
        $token->setToken($tokenValue);
        $token->setUserId($userId);
        $token->setCreatedAt(new \DateTime("now"));

        $this->om->persist($token);
        $this->om->flush();
    }

    public function read($tokenValue)
    {
        return $this->repository->findOneBy(array('token' => $tokenValue));
    }

    public function remove($tokenValue)
    {
        $token = $this->repository->findOneBy(array('token' => $tokenValue));

        $this->om->remove($token);
        $this->om->flush();
    }

    public function removeByUserId($userId)
    {
        $token = $this->repository->findOneBy(array('user_id' => $userId));

        $this->om->remove($token);
        $this->om->flush();
    }

    public function getTokenList($userId = null) {
        if (!is_null($userId)) {
            return $this->repository->findBy(array('user_id' => $userId));
        } else {
            return $this->repository->findAll();
        }
    }
}