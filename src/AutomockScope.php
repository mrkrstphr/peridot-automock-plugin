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
     * @return Prophet
     */
    public function autoMock($class)
    {
        $reflectionClass = new ReflectionClass($class);

        if ($reflectionClass->hasMethod('__construct')) {
            return $reflectionClass->newInstanceArgs(
                $this->collectArguments($reflectionClass->getMethod('__construct'))
            );
        }

        return $reflectionClass->newInstanceWithoutConstructor();
    }

    private function collectArguments(ReflectionMethod $method)
    {
        $parameterList = $method->getParameters();
        $mocks = [];

        foreach ($parameterList as $param) {
            if (!$param->getClass()) {
                throw new \RuntimeException(
                    'You can only auto mock classes with class type-hinted constructor arguments'
                );
            }

            $mock = (new Prophet())->prophesize($param->getClass()->getName());
            $this->{$param->getName()} = $mock;

            $mocks[] = $mock->reveal();
        }

        return $mocks;
    }
}
