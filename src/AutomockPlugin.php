<?php

namespace Mrkrstphr\Peridot\Plugin\Automock;

use Evenement\EventEmitterInterface;
use Peridot\Core\Suite;

/**
 * Class AutomockPlugin
 * @package Mrkrstphr\Peridot\Plugin\Automock
 */
class AutomockPlugin
{
    /**
     * @var EventEmitterInterface
     */
    protected $emitter;

    /**
     * @var AutomockScope
     */
    protected $scope;

    /**
     * @var boolean
     */
    protected $enableEagerMocking;

    /**
     * AutomockPlugin constructor.
     * @param EventEmitterInterface $emitter
     * @param boolean $enableEagerMocking
     */
    public function __construct(EventEmitterInterface $emitter, $enableEagerMocking = false)
    {
        $this->emitter = $emitter;
        $this->enableEagerMocking = $enableEagerMocking;

        $this->listen();
    }

    /**
     * Attach a new scope and, if enabled, auto instantiate the described class.
     * @param Suite $suite
     */
    public function onSuiteStart(Suite $suite)
    {
        $this->scope = new AutomockScope();
        $suite->getScope()->peridotAddChildScope($this->scope);
    }

    /**
     * Attach the necessary events for this plugin to work.
     */
    public function listen()
    {
        $this->emitter->on('suite.start', [$this, 'onSuiteStart']);
    }
}
