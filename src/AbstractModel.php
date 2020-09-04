<?php declare(strict_types=1);

namespace Somnambulist\ReadModels;

use BadMethodCallException;
use DomainException;
use IlluminateAgnostic\Str\Support\Str;
use function array_key_exists;
use function get_class_methods;
use function is_null;
use function method_exists;
use function preg_match;
use function sprintf;

/**
 * Class AbstractModel
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\AbstractModel
 */
abstract class AbstractModel
{

    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __call($method, $parameters)
    {
        $mutator   = $this->getAttributeMutator($method);
        $attribute = Str::snake($method);

        if (array_key_exists($attribute, $this->attributes) || method_exists($this, $mutator)) {
            return $this->getAttribute($attribute);
        }

        throw new BadMethodCallException(sprintf('Method "%s" not found on "%s"', $method, static::class));
    }

    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    public function __set($name, $value)
    {
        throw new DomainException(sprintf('Models are read-only and cannot be changed once loaded'));
    }

    public function __unset($name)
    {
        throw new DomainException(sprintf('Models are read-only and cannot be changed once loaded'));
    }

    public function __isset($name)
    {
        return !is_null($this->getAttribute($name));
    }

    public function getAttributes(): array
    {
        $attributes = $this->attributes;
        $ignore     = get_class_methods(self::class);

        foreach (get_class_methods($this) as $method) {
            $matches = [];

            if (!in_array($method, $ignore) && preg_match('/^get(?<property>[\w\d]+)Attribute/', $method, $matches)) {
                $prop = Str::snake($matches['property']);

                $attributes[$prop] = $this->{$method}($this->attributes[$prop] ?? null);
            }
        }

        return $attributes;
    }

    public function getRawAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Get the requested attribute or relationship
     *
     * If a mutator is defined (getXxxxAttribute method), the attribute will be passed
     * through that first. If the attribute does not exist a virtual accessor will be
     * checked and return if there is one.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function getAttribute(string $name)
    {
        $mutator = $this->getAttributeMutator($name);

        // real attributes first
        if (array_key_exists($name, $this->attributes)) {
            if (method_exists($this, $mutator)) {
                return $this->{$mutator}($this->attributes[$name]);
            }

            return $this->attributes[$name];
        }

        // virtual attributes accessed via the mutator
        if (method_exists($this, $mutator)) {
            return $this->{$mutator}();
        }

        // ignore anything on the base Model class
        if (method_exists(self::class, $name)) {
            return null;
        }

        return null;
    }

    protected function getAttributeMutator(string $name): string
    {
        return sprintf('get%sAttribute', Str::studly($name));
    }
}
