<?php
/**
 * User: iyurin
 * Date: 14.11.16
 * Time: 0:35
 */

namespace VIS\Bundle\ApiBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use VIS\Bundle\ApiBundle\Components\Http\ViewList;

/**
 * Class AbstractController
 * @package VIS\Bundle\ApiBundle\Controller
 */
class AbstractController extends FOSRestController
{
    /**
     * Creates a custom groups view.
     *
     * @param array $groups
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     *
     * @return View
     */
    public function groupView(array $groups = [], $data = null, $statusCode = null, array $headers = array())
    {
        $view = View::create($data, $statusCode, $headers);
        $serializationContext = $view->getContext();
        $serializationContext->setGroups($groups);
        $view->setContext($serializationContext);

    }

    /**
     * Create a view list
     *
     * @param QueryBuilder $qb
     * @param array        $params
     * @param array        $groups
     *
     * @return View
     */
    protected function viewList(QueryBuilder $qb, array $params, array $groups)
    {
        $view = $this->view(null, null, array());

        $viewList = new ViewList($qb, $params, $groups, $view);

        return $viewList->getView();
    }

}