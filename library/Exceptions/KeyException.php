<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Exceptions;

class KeyException extends ValidationException
{
    const MESSAGE_KEY = 1;

    /**
     * {@inheritDoc}
     */
    public function getTemplates()
    {
        return [
            self::MODE_AFFIRMATIVE => [
                self::MESSAGE_STANDARD => '{{key}} key must be valid',
                self::MESSAGE_KEY => '{{key}} key must be present',
            ],
            self::MODE_NEGATIVE => [
                self::MESSAGE_STANDARD => '{{key}} key must not be valid',
                self::MESSAGE_KEY => '{{key}} key must not be present',
            ],
        ];
    }
}
