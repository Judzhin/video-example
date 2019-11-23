<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest;

use App\ConfigProvider;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigProviderTest
 * @package AppTest
 */
class ConfigProviderTest extends TestCase
{
    /**
     *
     */
    public function testDependenciesMethod()
    {
        /** @var ConfigProvider $configProvider */
        $configProvider = new ConfigProvider;

        // $this->assertIsArray($configProvider->getDependencies());
        $this->assertThat($configProvider->getDependencies(), new IsType('array'));
    }

    /**
     *
     */
    public function testTemplatesMethod()
    {
        /** @var ConfigProvider $configProvider */
        $configProvider = new ConfigProvider;

        // $this->assertIsArray($configProvider->getTemplates());
        $this->assertThat($configProvider->getTemplates(), new IsType('array'));
    }

    /**
     *
     */
    public function testInvokeMethod()
    {
        /** @var ConfigProvider $configProvider */
        $configProvider = new ConfigProvider;

        // $this->assertIsArray($configProvider->__invoke());
        $this->assertThat($configProvider->__invoke(), new IsType('array'));
    }
}
