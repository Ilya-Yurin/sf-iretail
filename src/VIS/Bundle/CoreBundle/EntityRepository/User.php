<?php
/**
 * User: iyurin
 * Date: 09.04.17
 * Time: 15:36
 */

namespace VIS\Bundle\CoreBundle\EntityRepository;

use Doctrine\ORM\QueryBuilder;
use VIS\Bundle\CoreBundle\Component\Normalizer;

class User extends BaseRepository
{
    /**
     * @param array $filters
     * @param null $sorting
     * @return QueryBuilder
     */
    public function getListQB(array $filters = array(), $sorting = null)
    {
        $qb = $this->_getQB('u');
        $qb->addSelect('u');

        foreach ($filters as $name => $value) {
            switch ($name) {
                case 'user_type':
                case 'status':
                    $qb
                        ->andWhere($qb->expr()->eq('u.' . Normalizer::toCamelCase($name), $value));
                    break;
                case 'full_name':
                    $qb
                        ->andWhere($qb->expr()->andX(
                            $qb->expr()->orX(
                                $qb->expr()->like(
                                    $qb->expr()->concat('u.firstName', $qb->expr()->concat($qb->expr()->literal(' '), 'u.lastName')),
                                    $qb->expr()->literal('%' . $this->makeLikeParam($value) . '%')
                                ),
                                $qb->expr()->like(
                                    $qb->expr()->concat('u.lastName', $qb->expr()->concat($qb->expr()->literal(' '), 'u.firstName')),
                                    $qb->expr()->literal('%' . $this->makeLikeParam($value) . '%')
                                )
                            )
                        )
                        );
                    break;
            }
        }

        switch ($sorting) {
            case 'user_type':
            case 'status':
                $qb
                    ->addOrderBy('u.' . Normalizer::toCamelCase($sorting), 'ASC');
                break;
            case 'full_name':
                $qb
                    ->addOrderBy('u.firstName', 'ASC')
                    ->addOrderBy('u.lastName', 'ASC');
                break;
        }

        $qb->addOrderBy('u.createdAt', 'DESC');

        return $qb;
    }


    /**
     * Find User with same email
     *
     * @param $email
     * @param $id
     * @return mixed
     */
    public function _findUniqueBy($email, $id)
    {
        $qb = $this->_getQB('u');
        $qb->addSelect('u');

        $qb
            ->andWhere('u.emailAddress = :emailAddress')
            ->setParameter('emailAddress', $email);

        if (!is_null($id)) {
            $qb
                ->andWhere('u.id <> :id')
                ->setParameter('id', $id);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * _findOneById for get single user
     * @param $id
     * @return mixed
     */
    public function _findOneById($id)
    {
        $qb = $this->_getQB('u');
        $qb->addSelect('u');

        $qb
            ->andWhere('u.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $email
     * @return User|NULL
     */
    public function _findByEmail($email)
    {
        $qb = $this->_getQB('u');
        $qb->addSelect('u')
            ->andWhere('u.emailAddress = :email')
            ->setParameter('email', $email);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Update users status
     *
     * @param $status
     * @param array $users
     */
    public function updateUsersStatus($status, $users = [])
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->update()
            ->set('u.status', $status);
        if (!empty($users)) {
            $qb
                ->andWhere('u.id IN (:users)')
                ->setParameter('users', $users);
        }

        $qb
            ->getQuery()->execute();
    }
}