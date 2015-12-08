<?php

namespace Mrkrstphr\Peridot\Plugin\Automock;

use Peridot\Core\Scope;
use Prophecy\Prophet;
use ReflectionClass;
use ReflectionMethod;

class AutomockScope extends Scope
{
    /**
     * @var Prophet
     */
    protected $prophet;

    /**
     * @param string $class
     * @param array $defaults
     * @return object
     */
    public function autoMock($class, array $defaults = [])
    {
        $reflectionClass = new ReflectionClass($class);

        if ($reflectionClass->hasMethod('__construct')) {
            return $reflectionClass->newInstanceArgs(
                $this->collectArguments($reflectionClass->getMethod('__construct'), $defaults)
            );
        }

        return $reflectionClass->newInstanceWithoutConstructor();
    }

    /**
     * @param ReflectionMethod $method
     * @param array $defaults
     * @return array
     */
    private function collectArguments(ReflectionMethod $method, array $defaults = [])
    {
        $parameterList = $method->getParameters();
        $mocks = [];

        foreach ($parameterList as $param) {
            if (array_key_exists($param->getName(), $defaults)) {
                $mocks[] = $defaults[$param->getName()];
            } else {
                if (!$param->getClass()) {
                    throw new \RuntimeException(
                        'You can only auto mock classes with class type-hinted constructor arguments'
                    );
                }

                $mock = (new Prophet())->prophesize($param->getClass()->getName());
                $this->{$param->getName()} = $mock;

                $mocks[] = $mock->reveal();
            }
        }

        return $mocks;
    }
}
