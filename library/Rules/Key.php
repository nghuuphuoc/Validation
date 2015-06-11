<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules;

use Respect\Validation\Exceptions\KeyException;
use Respect\Validation\Context;

/**
 * Validates if the given input is not empty.
 */
final class Key implements RuleRequiredInterface
{
    private $key;
    private $rule;
    private $mandatory;

    /**
     * @param mixed         $key
     * @param RuleInterface $rule
     * @param bool          $mandatory
     */
    public function __construct($key, RuleInterface $rule, $mandatory)
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->mandatory = (bool) $mandatory;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Context $context)
    {
        $context->key = $this->key;

        if (!isset($context->input[$this->key])) {
            $context->isValid = !$this->mandatory;
            $context->template = KeyException::MESSAGE_KEY;

            return;
        }

        $childContext = $context->createChild($this->rule);
        $childContext->label = $this->key;
        $childContext->input = $context->input[$this->key];
        $childContext->applyRule();

        $context->isValid = $childContext->isValid;
    }
}
