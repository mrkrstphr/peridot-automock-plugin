<?php

namespace Mrkrstphr\Peridot\Plugin\Automock;

use Evenement\EventEmitterInterface;

class AutomockPlugin
{
    /**
     * @var EventEmitterInterface
     */
    protected $emitter;

    /**
     * @var ProphecyScope
     */
    protected $scope;

    /**
     * @var boolean
     */
    protected $enableEagerMocking;

    /**
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
     * Attach the relevent Peridot events
     */
    public function listen()
    {
    }
}
