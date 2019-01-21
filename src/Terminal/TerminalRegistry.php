<?php

namespace Lamoda\Payture\InPayBundle\Terminal;

use Lamoda\Payture\InPayClient\PaytureInPayTerminalInterface;

final class TerminalRegistry
{
    /** @var PaytureInPayTerminalInterface[] */
    private $terminals = [];

    public function add(string $name, PaytureInPayTerminalInterface $terminal): void
    {
        if (array_key_exists($name, $this->terminals)) {
            throw new \InvalidArgumentException(sprintf('Terminal "%s" already registered', $name));
        }

        $this->terminals[$name] = $terminal;
    }

    public function get(string $name): PaytureInPayTerminalInterface
    {
        if (!array_key_exists($name, $this->terminals)) {
            throw new \OutOfBoundsException(sprintf('Terminal "%s" is not registered', $name));
        }

        return $this->terminals[$name];
    }
}
