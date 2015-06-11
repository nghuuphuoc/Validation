<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules;

use Respect\Validation\Context;
use Respect\Validation\Helpers\OptionalHelper;

/**
 * Validates if the given input is not optional.
 */
final class NotOptional implements RuleRequiredInterface
{
    use OptionalHelper;

    /**
     * {@inheritDoc}
     */
    public function apply(Context $context)
    {
        $context->isValid = !$this->isOptional($context->input);
    }
}
