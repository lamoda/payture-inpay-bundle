<?php

namespace Lamoda\Payture\InPayBundle\Tests\Fixtures;

use Lamoda\Payture\InPayBundle\PaytureInPayBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    /** @var string */
    private $config;

    public function __construct(string $config)
    {
        parent::__construct('test_' . substr(sha1($config), 0, 6), true);
        $this->config = $config;
    }

    /** {@inheritdoc} */
    public function registerBundles()
    {
        return [
            new PaytureInPayBundle(),
        ];
    }

    /** {@inheritdoc} */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/configs/' . $this->config);
    }

    /** {@inheritdoc} */
    public function getLogDir(): string
    {
        return __DIR__ . '/../../build/logs';
    }

    /** {@inheritdoc} */
    public function getProjectDir(): string
    {
        return __DIR__;
    }

    /** {@inheritdoc} */
    public function getCacheDir(): string
    {
        return __DIR__ . '/../../build/cache';
    }

    protected function getContainerBuilder(): ContainerBuilder
    {
        $builder = parent::getContainerBuilder();

        $builder->addResource(new FileResource(__DIR__ . '/configs/' . $this->config));

        return $builder;
    }
}
