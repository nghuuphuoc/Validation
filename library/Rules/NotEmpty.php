<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules;

use Respect\Validation\Context;

/**
 * Validates if the given input is not empty.
 */
final class NotEmpty implements RuleRequiredInterface
{
    /**
     * {@inheritDoc}
     */
    public function apply(Context $context)
    {
        $input = $context->input;

        $context->isValid = !empty($input); //We need needs a variable to use empty()
    }
}
