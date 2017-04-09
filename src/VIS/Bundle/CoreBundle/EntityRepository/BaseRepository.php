<?php
/**
 * User: iyurin
 * Date: 09.04.17
 * Time: 15:35
 */

namespace VIS\Bundle\CoreBundle\EntityRepository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use VIS\Bundle\CoreBundle\Component\LikeQueryHelpers;
use VIS\Bundle\CoreBundle\Component\Normalizer;

/**
 * Class BaseRepository
 * @package VIS\Bundle\CoreBundle\EntityRepository
 */
class BaseRepository extends EntityRepository
{
    use LikeQueryHelpers;
    /**
     * Get QB
     * @param string $alias
     * @return QueryBuilder object
     */
    protected function _getQB($alias)
    {
        $qb = $this->createQueryBuilder($alias);

        return $qb;
    }

    /**
     * Add filter pagination conditions
     *
     * @param QueryBuilder $qb
     * @param $alias
     * @param array $filters
     * @return QueryBuilder
     */
    protected function _addFilterQB(QueryBuilder $qb, $alias, array $filters)
    {
        foreach ($filters as $name => $value) {
            switch ($name) {
                case 'type':
                    $qb
                        ->andWhere($qb->expr()->eq($alias . '.' . Normalizer::toCamelCase($name), "'" . $value . "'"));
                    break;
                case 'status':
                    $qb
                        ->andWhere($qb->expr()->eq($alias . '.' . Normalizer::toCamelCase($name), $value));
                    break;
                case 'name':
                    $qb
                        ->andWhere($qb->expr()->andX(
                            $qb->expr()->like(
                                $alias . '.name',
                                $qb->expr()->literal('%' . $this->makeLikeParam($value) . '%')
                            )
                        )
                        );
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

        return $qb;
    }

    /**
     * Add sorting pagination conditions
     *
     * @param QueryBuilder $qb
     * @param $alias
     * @param null $sorting
     * @return QueryBuilder
     */
    protected function _addSortingQB(QueryBuilder $qb, $alias, $sorting = null)
    {
        if ($sorting) {
            $qb
                ->addOrderBy($alias . '.' . Normalizer::toCamelCase($sorting), 'ASC');
        }

        return $qb;
    }
}