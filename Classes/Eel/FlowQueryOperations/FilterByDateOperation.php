<?php
namespace Ttree\ContentRepositoryUtility\Eel\FlowQueryOperations;

/*
 * This file is part of the Ttree.ContentRepositoryUtility package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Eel\FlowQuery\Operations\AbstractOperation;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;

/**
 * EEL opertation to filter nodes by date property
 */
class FilterByDateOperation extends AbstractOperation
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    static protected $shortName = 'filterByDate';

    /**
     * {@inheritdoc}
     *
     * @var integer
     */
    static protected $priority = 100;

    /**
     * {@inheritdoc}
     *
     * We can only handle TYPO3CR Nodes.
     *
     * @param mixed $context
     * @return boolean
     */
    public function canEvaluate($context)
    {
        return (isset($context[0]) && ($context[0] instanceof NodeInterface));
    }

    /**
     * {@inheritdoc}
     *
     * @param FlowQuery $flowQuery the FlowQuery object
     * @param array $arguments the arguments for this operation.
     * First argument is property to filter by, must be DateTime.
     * Second is Date operand, must be DateTime object.
     * And third is a compare operator: '<' or '>', '>' by default
     * @return mixed
     */
    public function evaluate(FlowQuery $flowQuery, array $arguments)
    {
        if (!isset($arguments[0]) || empty($arguments[0])) {
            throw new \Neos\Eel\FlowQuery\FlowQueryException('filterByDate() needs property name by which nodes should be filtered', 1332492263);
        } else if (!isset($arguments[1]) || empty($arguments[1])) {
            throw new \Neos\Eel\FlowQuery\FlowQueryException('filterByDate() needs date value by which nodes should be filtered', 1332493263);
        } else {
            $nodes = $flowQuery->getContext();
            $filterByPropertyPath = $arguments[0];
            $date = $arguments[1];
            $compareOperator = '>';
            if (isset($arguments[2]) && !empty($arguments[2]) && in_array($arguments[2], array('<', '>'))) {
                $compareOperator = $arguments[2];
            }
            $filteredNodes = array();
            /** @var Node $node */
            foreach ($nodes as $node) {
                $propertyValue = $node->getProperty($filterByPropertyPath);
                if ($compareOperator == '>') {
                    if ($propertyValue > $date) {
                        $filteredNodes[] = $node;
                    }
                }
                if ($compareOperator == '<') {
                    if ($propertyValue < $date) {
                        $filteredNodes[] = $node;
                    }
                }
            }
            $flowQuery->setContext($filteredNodes);
        }
    }
}
