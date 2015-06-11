<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules;

use RecursiveIteratorIterator;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\RecursiveContextIterator;
use Respect\Validation\Context;

/**
 * Negates any rule.
 */
final class Not implements RuleRequiredInterface
{
    /**
     * @var RuleInterface
     */
    protected $rule;

    /**
     * @param RuleInterface $rule
     */
    public function __construct(RuleInterface $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @return RuleInterface
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Context $context)
    {
        $childContext = $context->createChild($this->getRule());
        $childContext->applyRule();

        $contextIterator = new RecursiveContextIterator($context);
        $iteratorIterator = new RecursiveIteratorIterator($contextIterator, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iteratorIterator as $grandChildContext) {
            if (!$grandChildContext->hasChildren()) {
                $grandChildContext->mode = $grandChildContext->mode == ValidationException::MODE_NEGATIVE
                                            ? ValidationException::MODE_AFFIRMATIVE
                                            : ValidationException::MODE_NEGATIVE;
            }

            $grandChildContext->isValid = !$grandChildContext->isValid;
        }

        $context->isValid = $childContext->isValid;
    }
}
