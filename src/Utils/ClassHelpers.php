<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Utils;

use Closure;
use ReflectionObject;

/**
 * Class Helpers
 *
 * Assorted helpers, scoped to prevent global functions.
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\Utils\Helpers
 */
class ClassHelpers
{
    private function __construct() {}

    /**
     * @param object $object
     *
     * @return string
     */
    public static function getObjectShortClassName(object $object): string
    {
        return (new ReflectionObject($object))->getShortName();
    }

    /**
     * Returns the class that made a call to the current class
     *
     * @link https://gist.github.com/kylefarris/5188645
     *
     * @return string
     */
    public static function getCallingClass(): string
    {
        //get the trace
        $trace = debug_backtrace();

        // Get the class that is asking for who awoke it
        $class = (isset($trace[1]['class']) ? $trace[1]['class'] : null);
        // +1 to i cos we have to account for calling this function
        for ($i = 1; $i < count($trace); $i++) {
            if (isset($trace[$i]) && isset($trace[$i]['class'])) // is it set?
            {
                if ($class != $trace[$i]['class']) // is it a different class
                {
                    return $trace[$i]['class'];
                }
            }
        }
    }

    /**
     * Returns the function (method name) that called the function this is used in
     *
     * @link https://github.com/laravel/framework/blob/5.8/src/Illuminate/Database/Eloquent/Concerns/HasRelationships.php#L310
     *
     * @return string
     */
    public static function getCallingMethod(): string
    {
        [$one, $two, $caller] = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        return $caller['function'];
    }

    /**
     * Set the property in object to value
     *
     * If scope is set to a parent class, private properties can be updated.
     *
     * @param object             $object
     * @param string             $property
     * @param mixed              $value
     * @param null|string|object $scope
     *
     * @return object
     */
    public static function set(object $object, string $property, $value, $scope = null)
    {
        Closure::bind(function () use ($property, $value) {
            $this->{$property} = $value;
        }, $object, !is_null($scope) ? $scope : 'static')();

        return $object;
    }

    /**
     * Set an array key in the object property to value
     *
     * If scope is set to a parent class, private properties can be updated.
     *
     * @param object             $object
     * @param string             $property
     * @param string             $key
     * @param mixed              $value
     * @param null|string|object $scope
     *
     * @return object
     */
    public static function setPropertyArrayKey(object $object, string $property, string $key, $value, $scope = null)
    {
        Closure::bind(function () use ($property, $key, $value) {
            $this->{$property}[$key] = $value;
        }, $object, !is_null($scope) ? $scope : 'static')();

        return $object;
    }
}
