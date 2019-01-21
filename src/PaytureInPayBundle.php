<?php

namespace Lamoda\Payture\InPayBundle;

use Lamoda\Payture\InPayBundle\DependencyInjection\PaytureInPayExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @codeCoverageIgnore
 */
final class PaytureInPayBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new PaytureInPayExtension();
    }
}
