<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation;

use Countable;
use RecursiveIterator;
use SplObjectStorage;

class RecursiveContextIterator implements RecursiveIterator, Countable
{
    /**
     * @var SplObjectStorage
     */
    protected $contexts;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->contexts = $context->getChildren();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->contexts->count();
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        if (!$this->valid()) {
            return false;
        }

        return $this->current()->hasChildren();
    }

    /**
     * @return RecursiveContextIterator
     */
    public function getChildren()
    {
        return new static($this->current());
    }

    /**
     * @return Context
     */
    public function current()
    {
        return $this->contexts->current();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->contexts->key();
    }

    public function next()
    {
        $this->contexts->next();
    }

    public function rewind()
    {
        $this->contexts->rewind();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->contexts->valid();
    }
}
