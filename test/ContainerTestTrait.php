<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest;

use Interop\Container\ContainerInterface;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Trait ContainerTestTrait
 * @package AppTest
 * @deprecated
 */
trait ContainerTestTrait
{
    /** @var ObjectProphecy */
    protected $container;

    /**
     * @return ObjectProphecy
     */
    public function getContainer(): ObjectProphecy
    {
        if (null === $this->container) {
            $this->container = $this->prophesize(ContainerInterface::class);
        }

        return $this->container;
    }
}