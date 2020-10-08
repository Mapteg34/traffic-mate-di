<?php

namespace Mapt\TrafficMateDi;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use RuntimeException;

class Container implements ContainerInterface
{
    private function __construct()
    {

    }

    private static self $instance;

    private array $bindings = [];

    private array $instances = [];

    /** @return static */
    public static function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public function singleton(string $abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    private function resolveDependency(ReflectionParameter $dependency)
    {
        $type = $dependency->getType();

        if ($type->isBuiltin()) {
            return $dependency->getDefaultValue();
        }

        return $this->make($type->getName());
    }

    public function bind($abstract, $concrete = null, bool $shared = false): void
    {
        if (empty($concrete)) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = ['concrete' => $concrete, 'shared' => $shared];
    }

    private function build($abstract, array $parameters = [])
    {
        try {
            $reflector = new ReflectionClass($abstract);
        } catch (ReflectionException $e) {
            throw new RuntimeException(sprintf('Target class [%s] does not exist.', $abstract));
        }

        if (! $reflector->isInstantiable()) {
            throw new RuntimeException(sprintf('Target class [%s] is not instantiable.', $abstract));
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $abstract;
        }

        $dependencies = $constructor->getParameters();

        $args = [];

        foreach ($dependencies as $dependency) {
            if (isset($parameters[$dependency->name])) {
                $args[] = $parameters[$dependency->name];
                continue;
            } else {
                $args[] = $this->resolveDependency($dependency);
            }
        }

        return $reflector->newInstanceArgs($args);
    }

    public function make(string $abstract, array $parameters = [])
    {
        $shared = false;

        while(isset($this->bindings[$abstract])) {
            if ($this->bindings[$abstract]['shared']) {
                $shared = true;
            }
            if ($abstract === $this->bindings[$abstract]['concrete']) {
                break;
            }
            $abstract = $this->bindings[$abstract]['concrete'];
        }

        if (isset($this->instances[$abstract]) && empty($parameters)) {
            return $this->instances[$abstract];
        }

        $object = $this->build($abstract, $parameters);

        if ($shared) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    public function bound(string $abstract): bool
    {
        return isset($this->bindings[$abstract]) || isset($this->instances[$abstract]);
    }

    public function get($abstract)
    {
        if (! is_string($abstract)) {
            throw new InvalidArgumentException('abstract is not string');
        }
    }

    public function has($abstract): bool
    {
        if (! is_string($abstract)) {
            throw new InvalidArgumentException('abstract is not string');
        }

        return $this->bound($abstract);
    }
}