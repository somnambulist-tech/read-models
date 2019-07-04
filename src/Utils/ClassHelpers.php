<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Utils;

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
}
