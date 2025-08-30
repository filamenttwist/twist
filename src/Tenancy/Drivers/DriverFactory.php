<?php

namespace Obelaw\Twist\Tenancy\Drivers;

use InvalidArgumentException;
use Obelaw\Twist\Tenancy\Contracts\IsolationDriver;

class DriverFactory
{
    /** @var array<string,class-string<IsolationDriver>> */
    protected array $map;

    protected ?string $default;

    /** @param array<string,class-string<IsolationDriver>> $map */
    public function __construct(array $map = [], ?string $default = null)
    {
        $this->map = $map;
        $this->default = $default;
    }

    public function setDefault(?string $name): void
    {
        $this->default = $name;
    }

    public function default(): ?string
    {
        return $this->default;
    }

    public function has(string $name): bool
    {
        return isset($this->map[$name]);
    }

    /** @return string[] */
    public function names(): array
    {
        return array_keys($this->map);
    }

    public function make(?string $name = null): IsolationDriver
    {
        $name = $name ?? $this->default;
        if ($name === null) {
            throw new InvalidArgumentException('No isolation driver specified.');
        }
        if (! isset($this->map[$name])) {
            throw new InvalidArgumentException("Isolation driver '{$name}' is not registered.");
        }
        $class = $this->map[$name];
        return new $class();
    }
}
