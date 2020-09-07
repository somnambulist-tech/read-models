<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Utils;

use BadMethodCallException;
use Error;
use function get_class;
use function preg_match;
use function sprintf;

/**
 * Class ProxyTo
 *
 * Based on the Laravel trait ForwardsCalls
 *
 * @link https://github.com/laravel/framework/blob/5.8/src/Illuminate/Support/Traits/ForwardsCalls.php
 *
 * @package    Somnambulist\Components\ReadModels\Utils
 * @subpackage Somnambulist\Components\ReadModels\Utils\ProxyTo
 */
class ProxyTo
{

    /**
     * Proxy a call into the specified object, collecting error information if it fails
     *
     * @param object $object
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __invoke(object $object, string $method, array $parameters = [])
    {
        $class = ClassHelpers::getCallingClass();

        try {
            return $object->{$method}(...$parameters);
        } catch (Error | BadMethodCallException $e) {
            $pattern = '~^Call to undefined method (?P<class>[^:]+)::(?P<method>[^\(]+)\(\)$~';

            if (!preg_match($pattern, $e->getMessage(), $matches)) {
                throw $e;
            }

            if ($matches['class'] != get_class($object) ||
                $matches['method'] != $method) {
                throw $e;
            }

            throw new BadMethodCallException(sprintf('Call to undefined method %s::%s()', $class, $method));
        }
    }
}
