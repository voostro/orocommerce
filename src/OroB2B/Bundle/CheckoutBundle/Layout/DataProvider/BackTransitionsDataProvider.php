<?php

namespace OroB2B\Bundle\CheckoutBundle\Layout\DataProvider;

use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Model\Transition;
use Oro\Component\Layout\ContextInterface;

use OroB2B\Bundle\CheckoutBundle\Model\TransitionData;

class BackTransitionsDataProvider extends AbstractTransitionDataProvider
{
    /**
     * @var array
     */
    protected $transitions = [];

    /**
     * {@inheritdoc}
     */
    public function getData(ContextInterface $context)
    {
        $workflowItem = $context->data()->get('workflowItem');

        $cacheKey = $workflowItem->getId() . '_' . $workflowItem->getCurrentStep()->getId();
        if (!array_key_exists($cacheKey, $this->transitions)) {
            $this->transitions[$cacheKey] = $this->getBackTransitions($workflowItem);
        }

        return $this->transitions[$cacheKey];
    }

    /**
     * @param WorkflowItem $workflowItem
     * @return null|Transition
     */
    protected function getBackTransitions(WorkflowItem $workflowItem)
    {
        $transitions = $this->workflowRegistry->getWorkflow($workflowItem->getWorkflowName())
            ->getTransitionsByWorkflowItem($workflowItem);

        /** @var TransitionData[] $backTransitions */
        $backTransitions = [];
        foreach ($transitions as $transition) {
            $frontendOptions = $transition->getFrontendOptions();
            if (!empty($frontendOptions['is_checkout_back'])) {
                $stepOrder = $transition->getStepTo()->getOrder();
                if ($transitionData = $this->getTransitionData($transition, $workflowItem)) {
                    $backTransitions[$stepOrder] = $transitionData;
                }
            }
        }
        ksort($backTransitions);

        $transitions = [];
        foreach ($backTransitions as $transitionData) {
            $transitions[$transitionData->getTransition()->getStepTo()->getName()] = $transitionData;
        }

        return $transitions;
    }
}
