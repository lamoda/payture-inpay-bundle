<?php

namespace Lamoda\Payture\InPayBundle\DependencyInjection;

use Lamoda\Payture\InPayClient\PaytureOperation;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/** @codeCoverageIgnore */
final class Configuration implements ConfigurationInterface
{
    /** {@inheritdoc} */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder();
        $root = $builder->root('payture_inpay');

        $this->configureTerminalDefinition($root);
        $this->configureTerminalDefaults($root);

        $root->children()->scalarNode('guzzle_client')->defaultNull()
            ->info('Guzzle client service ID. New one will be created if none provided');

        $root->children()->scalarNode('logger')->defaultNull()
            ->info('Logger service ID. No logger by default');

        return $builder;
    }

    private function configureTerminalDefinition(ArrayNodeDefinition $parent): void
    {
        $terminal = $parent->children()->arrayNode('terminals');
        $terminal->useAttributeAsKey('name', true);

        $terminalPrototype = $terminal->prototype('array');

        $terminalPrototype->children()->scalarNode('name');

        $auth = $terminalPrototype->children()->arrayNode('auth')->isRequired()->info('Terminal authentication data');

        $auth->children()->scalarNode('url')->info('Terminal operation URL')
            ->example('https://sandbox.payture.com/')->isRequired();
        $auth->children()->scalarNode('key')->info('Terminal identification Key')
            ->example('Merchant')->isRequired();
        $auth->children()->scalarNode('password')->info('Terminal identification Password')
            ->example('Secret')->isRequired();

        $this->configureOperations($terminalPrototype);
    }

    private function configureOperations(ArrayNodeDefinition $parent, bool $required = false): void
    {
        $operations = $parent->children()->arrayNode('operations');
        $this->configureOperation($operations, PaytureOperation::INIT, $required);
        $this->configureOperation($operations, PaytureOperation::CHARGE, $required);
        $this->configureOperation($operations, PaytureOperation::UNBLOCK, $required);
        $this->configureOperation($operations, PaytureOperation::REFUND, $required);
        $this->configureOperation($operations, PaytureOperation::PAY_STATUS, $required);
        $this->configureOperation($operations, PaytureOperation::GET_STATE, $required);
    }

    private function configureOperation(ArrayNodeDefinition $operations, string $name, bool $required = false): void
    {
        $operation = $operations->children()->arrayNode($name);

        $timeout = $operation->children()->floatNode('timeout')
            ->defaultValue($required ? 30 : null)->info('Operation timeout, seconds');

        if ($required) {
            $timeout->isRequired();
        }

        $connectionTimeout = $operation->children()->floatNode('connect_timeout')
            ->defaultValue($required ? 5 : null)
            ->info('Connection timeout for operation, seconds');

        if ($required) {
            $connectionTimeout->isRequired();
        }
    }

    private function configureTerminalDefaults(ArrayNodeDefinition $parent): void
    {
        $terminal = $parent->children()->arrayNode('default_options');

        $this->configureOperations($terminal, true);
    }
}
