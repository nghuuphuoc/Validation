<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation;

use RecursiveIteratorIterator;
use ReflectionClass;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\RuleInterface;
use SplObjectStorage;

class Factory
{
    /**
     * @var array
     */
    protected $namespaces = ['Respect\\Validation'];

    /**
     * @var array
     */
    protected $contextProperties = [];

    /**
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * @param string $namespace
     */
    public function appendNamespace($namespace)
    {
        array_push($this->namespaces, $namespace);
    }

    /**
     * @param string $namespace
     */
    public function prependNamespace($namespace)
    {
        array_unshift($this->namespaces, $namespace);
    }

    /**
     * @param array $contextProperties
     */
    public function setDefaultContextProperties(array $contextProperties)
    {
        $this->contextProperties = $contextProperties;
    }

    /**
     * @param string $ruleName
     * @param array  $settings
     *
     * @throws ComponentException
     *
     * @return RuleInterface
     */
    public function createRule($ruleName, array $settings = [])
    {
        foreach ($this->getNamespaces() as $namespace) {
            $ruleClassName = $namespace.'\\Rules\\'.ucfirst($ruleName);
            if (!class_exists($ruleClassName)) {
                continue;
            }

            $reflection = new ReflectionClass($ruleClassName);
            if (!$reflection->isSubclassOf('Respect\\Validation\\Rules\\RuleInterface')) {
                throw new ComponentException(sprintf('"%s" is not a valid respect rule', $ruleClassName));
            }

            return $reflection->newInstanceArgs($settings);
        }

        throw new ComponentException(sprintf('"%s" is not a valid rule name', $ruleName));
    }

    /**
     * @param Context $context
     *
     * @throws ComponentException
     *
     * @return ValidationException
     */
    public function createException(Context $context)
    {
        $ruleName = get_class($context->getRule());
        $ruleShortName = substr(strrchr($ruleName, '\\'), 1);
        foreach ($this->getNamespaces() as $namespace) {
            $exceptionClassName = $namespace.'\\Exceptions\\'.$ruleShortName.'Exception';
            if (!class_exists($exceptionClassName)) {
                continue;
            }

            $reflection = new ReflectionClass($exceptionClassName);
            if (!$reflection->isSubclassOf('Respect\\Validation\\Exceptions\\ValidationException')) {
                throw new ComponentException(sprintf('"%s" is not a validation exception', $exceptionClassName));
            }

            return $reflection->newInstance($context);
        }

        throw new ValidationException($context);
    }

    /**
     * @param Context $context
     *
     * @throws ComponentException
     *
     * @return ValidationException
     */
    public function createFilteredException(Context $context)
    {
        $contextIterator = new RecursiveContextIterator($context);
        $iteratorIterator = new RecursiveIteratorIterator($contextIterator);
        foreach ($iteratorIterator as $childContext) {
            $context = $childContext;
            break;
        }

        return $this->createException($context);
    }

    /**
     * @return SplObjectStorage
     */
    public function createChildrenExceptions(Context $context)
    {
        $childrenExceptions = new SplObjectStorage();

        $contextIterator = new RecursiveContextIterator($context);
        $iteratorIterator = new RecursiveIteratorIterator($contextIterator, RecursiveIteratorIterator::SELF_FIRST);

        $lastDepth = 0;
        $lastDepthOriginal = 0;
        $knownDepths = [];
        foreach ($iteratorIterator as $childContext) {
            if ($childContext->isValid) {
                continue;
            }

            if ($childContext->hasChildren()
                && $childContext->getChildren()->count() < 2) {
                continue;
            }

            $currentDepth = $lastDepth;
            $currentDepthOriginal = $iteratorIterator->getDepth() + 1;

            if (isset($knownDepths[$currentDepthOriginal])) {
                $currentDepth = $knownDepths[$currentDepthOriginal];
            } elseif ($currentDepthOriginal > $lastDepthOriginal) {
                $currentDepth++;
            }

            if (!isset($knownDepths[$currentDepthOriginal])) {
                $knownDepths[$currentDepthOriginal] = $currentDepth;
            }

            $lastDepth = $currentDepth;
            $lastDepthOriginal = $currentDepthOriginal;

            $childrenExceptions->attach(
                $this->createException($childContext),
                [
                    'depth' => $currentDepth,
                    'depth_original' => $currentDepthOriginal,
                    'previous_depth' => $lastDepth,
                    'previous_depth_original' => $lastDepthOriginal,
                ]
            );
        }

        return $childrenExceptions;
    }

    /**
     * @return Context
     */
    public function createContext(RuleInterface $rule, array $properties)
    {
        $contextProperties = $properties + $this->contextProperties;

        return new Context($rule, $contextProperties, $this);
    }
}
