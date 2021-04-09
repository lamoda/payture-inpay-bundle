<?php

namespace Lamoda\Payture\InPayBundle\Tests;

use Lamoda\Payture\InPayBundle\Tests\Fixtures\TestKernel;
use Lamoda\Payture\InPayClient\PaytureInPayTerminalInterface;
use PHPUnit\Framework\TestCase;

final class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider getValidConfigurations
     *
     * @param string[] $expectedTerminals
     */
    public function testValidConfigurations(string $config, array $expectedTerminals): void
    {
        $kernel = new TestKernel($config);
        $kernel->boot();

        $registry = $kernel->getContainer()->get('test_registry');

        foreach ($expectedTerminals as $terminal) {
            self::assertInstanceOf(PaytureInPayTerminalInterface::class, $registry->get($terminal));
        }
    }

    public function getValidConfigurations(): array
    {
        return [
            [
                'minimal.yaml',
                [
                    'TestTerminal',
                ],
            ],
            [
                'extended.yaml',
                [
                    'TestTerminal',
                    'ShouldBeFastTerminal',
                ],
            ],
        ];
    }
}
