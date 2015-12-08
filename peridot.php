<?php

use Evenement\EventEmitterInterface;
use Mrkrstphr\Peridot\Plugin\Automock\AutomockPlugin;
use Peridot\Console\Environment;
use Peridot\Plugin\Prophecy\ProphecyPlugin;

return function (EventEmitterInterface $emitter) {
    $emitter->on('peridot.start', function (Environment $environment) {
        $environment->getDefinition()->getArgument('path')->setDefault('specs');
    });

    $prophecy = new ProphecyPlugin($emitter);
    $automock = new AutomockPlugin($emitter);
};
