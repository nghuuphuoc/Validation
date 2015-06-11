<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Exceptions;

class NotEmptyException extends ValidationException
{
    /**
     * {@inheritDoc}
     */
    public function getTemplates()
    {
        return [
            self::MODE_AFFIRMATIVE => [
                self::MESSAGE_STANDARD => '{{placeholder}} must not be empty',
            ],
            self::MODE_NEGATIVE => [
                self::MESSAGE_STANDARD => '{{placeholder}} must be empty',
            ],
        ];
    }
}
