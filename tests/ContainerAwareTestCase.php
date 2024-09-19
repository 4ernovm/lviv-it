<?php

namespace Chernoff\LvivItTestAssignment\Tests;

use Chernoff\LvivItTestAssignment\Controller\MainController;
use Chernoff\LvivItTestAssignment\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class ContainerAwareTestCase extends TestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = (new Container())->register(new ServiceProvider(__DIR__));
    }
}
