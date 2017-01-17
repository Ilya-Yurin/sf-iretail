<?php
/**
 * User: iyurin
 * Date: 13.11.16
 * Time: 12:38
 */

namespace VIS\Bundle\ApiBundle\Components\Http;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use FOS\RestBundle\View\View;

/**
 * Class ViewList
 * @package VIS\Bundle\ApiBundle\Components\Http
 */
class ViewList
{
    const HEADER_TOTAL_ITEMS = 'I-Total-Items';
    /**
     * @var array
     */
    private $params;
    /**
     * @var QueryBuilder
     */
    private $qb;
    /**
     * @var array
     */
    private $groups;
    /**
     * @var View
     */
    private $view;

    /**
     * @param QueryBuilder $qb
     * @param array        $params
     * @param array        $groups
     * @param View         $view
     */
    public function __construct(QueryBuilder $qb, array $params, array $groups, View $view)
    {
        $this->setParams($params);
        $this->qb = $qb;
        $this->groups = $groups;
        $this->view = $view;
        $this->pagination();
    }

    /**
     * Handle request parameters.
     *
     * @param array $params
     *
     * @return ViewList
     */
    public function setParams(array $params)
    {
        $this->params = array(
            'page'  => (int) $params['page'],
            'limit' => (int) $params['limit'],
        );

        return $this;
    }

    /**
     * Pagination of list trend and build View.
     */
    protected function pagination()
    {
        $paginator = new Paginator($this->paginateQB(), true);

        // Set custom header
        $this->view->setHeader(self::HEADER_TOTAL_ITEMS, count($paginator));
        // Get result
        $this->view->setData($this->qb->getQuery()->getResult());
    }

    /**
     * Add pagination to query builder
     * @return QueryBuilder $qb
     */
    protected function paginateQB()
    {
        $this->qb->setFirstResult($this->getOffset());
        // Maybe all elements require
        if($this->getLimit() >= 0){
            $this->qb->setMaxResults($this->getLimit());
        }

        return $this->qb;
    }

    protected function getPage()
    {
        return $this->params['page'];
    }

    protected function getLimit()
    {
        return $this->params['limit'];
    }

    protected function getOffset()
    {
        return ($this->getPage() - 1) * $this->getLimit();
    }

    /**
     * @return View
     */
    public function getView()
    {
        $serializationContext = $this->view->getContext();
        $serializationContext->setGroups($this->groups);
        $this->view->setContext($serializationContext);

        return $this->view;
    }
}