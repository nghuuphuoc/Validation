<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Helpers\OptionalHelper;
use Respect\Validation\Rules\RuleInterface;
use Respect\Validation\Rules\RuleRequiredInterface;
use SplObjectStorage;

class Context
{
    use OptionalHelper;

    /**
     * @var SplObjectStorage
     */
    private $children;

    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var array
     */
    protected $properties = [
        'mode' => ValidationException::MODE_AFFIRMATIVE,
        'isValid' => true,
        'input' => null,
    ];

    /**
     * @param RuleInterface $rule
     * @param array         $properties
     * @param Factory       $factory
     */
    public function __construct(RuleInterface $rule, array $properties, Factory $factory)
    {
        $this->rule = $rule;
        $this->properties = $properties + $this->properties;
        $this->factory = $factory;
        $this->children = new SplObjectStorage();
    }

    /**
     * @return RuleInterface
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * Apply rule to the context.
     */
    public function applyRule()
    {
        $rule = $this->getRule();

        if (!$rule instanceof RuleRequiredInterface
            && $this->isOptional($this->input)) {
            $this->isValid = true;

            return;
        }

        $rule->apply($this);
    }

    /**
     * @param RuleInterface $rule
     *
     * @return Context
     */
    public function createChild(RuleInterface $rule)
    {
        $childContext = $this->getFactory()->createContext($rule, $this->getProperties());
        $childContext->appendTo($this);

        return $childContext;
    }

    /**
     * @param Context $parentContext
     */
    public function appendTo(Context $parentContext)
    {
        $parentContext->appendChild($this);
    }

    /**
     * @param Context $childChild
     */
    public function appendChild(Context $childChild)
    {
        $this->children->attach($childChild);
    }

    /**
     * @return SplObjectStorage
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return ($this->children->count() > 0);
    }
}
