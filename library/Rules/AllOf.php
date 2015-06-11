<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules;

use Respect\Validation\Context;
use SplObjectStorage;

/**
 * Will validate if all inner validators validates.
 */
class AllOf implements RuleRequiredInterface
{
    /**
     * @var SplObjectStorage
     */
    private $rules;

    public function __construct()
    {
        $this->addRules(func_get_args());
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getRules()->count();
    }

    /**
     * @param RuleInterface $rule
     *
     * @return self
     */
    public function addRule(RuleInterface $rule)
    {
        $this->getRules()->attach($rule);

        return $this;
    }

    public function addRules(array $rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * @return SplObjectStorage
     */
    public function getRules()
    {
        if (!$this->rules instanceof SplObjectStorage) {
            $this->rules = new SplObjectStorage();
        }

        return $this->rules;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Context $context)
    {
        foreach ($this->getRules() as $childRule) {
            $childContext = $context->createChild($childRule);
            $childContext->applyRule();

            $context->isValid = ($context->isValid && $childContext->isValid);
        }
    }
}
