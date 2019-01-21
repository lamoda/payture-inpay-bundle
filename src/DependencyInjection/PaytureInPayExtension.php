<?php

namespace Lamoda\Payture\InPayBundle\DependencyInjection;

use GuzzleHttp\Client;
use Lamoda\Payture\InPayBundle\Terminal\TerminalRegistry;
use Lamoda\Payture\InPayClient\GuzzleHttp\GuzzleHttpOptionsBag;
use Lamoda\Payture\InPayClient\GuzzleHttp\GuzzleHttpPaytureTransport;
use Lamoda\Payture\InPayClient\PaytureInPayTerminal;
use Lamoda\Payture\InPayClient\TerminalConfiguration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/** @codeCoverageIgnore */
final class PaytureInPayExtension extends Extension implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load('services.yml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $clientId = $config['guzzle_client'];
        if (empty($clientId)) {
            $clientId = 'lamoda_payture.guzzle_client';
            $container->register($clientId, Client::class);
        }

        $loggerId = $config['logger'];

        foreach ($config['terminals'] as $name => $terminal) {
            $optionsBag = new Definition(GuzzleHttpOptionsBag::class);
            $optionsBag->setArguments(
                [
                    [],
                    array_replace_recursive(
                        $config['default_options']['operations'] ?? [],
                        $terminal['operations'] ?? []
                    ),
                ]
            );

            $auth = new Definition(TerminalConfiguration::class);
            $auth->setArguments(
                [
                    $terminal['auth']['key'],
                    $terminal['auth']['password'],
                    $terminal['auth']['url'],
                ]
            );

            $transport = new Definition(GuzzleHttpPaytureTransport::class);
            $transport->setArguments(
                [
                    new Reference($clientId),
                    $auth,
                    $optionsBag,
                    $loggerId ? new Reference($loggerId) : null,
                ]
            );

            $terminal = new Definition(PaytureInPayTerminal::class);
            $terminal->setArguments(
                [
                    $auth,
                    $transport,
                ]
            );

            $terminal->addTag('payture.terminal', ['name' => $name]);
            $container->setDefinition('lamoda_payture.terminals.' . $name, $terminal);
        }
    }

    /** {@inheritdoc} */
    public function process(ContainerBuilder $container): void
    {
        $registry = $container->getDefinition(TerminalRegistry::class);

        foreach ($container->findTaggedServiceIds('payture.terminal') as $id => $tags) {
            foreach ($tags as $tag) {
                if (empty($tag['name'])) {
                    throw new \RuntimeException(
                        sprintf('Invalid "payture.terminal" tag configuration for "%s": no name provided', $id)
                    );
                }

                $registry->addMethodCall('add', [$tag['name'], new Reference($id)]);
            }
        }
    }

    /** {@inheritdoc} */
    public function getAlias(): string
    {
        return 'payture_inpay';
    }
}
