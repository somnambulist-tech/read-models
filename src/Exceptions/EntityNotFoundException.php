<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Exceptions;

use Exception;

/**
 * Class EntityNotFoundException
 *
 * @package    Somnambulist\ReadModels\Exceptions
 * @subpackage Somnambulist\ReadModels\Exceptions\EntityNotFoundException
 */
class EntityNotFoundException extends Exception
{

    /**
     * @param string $class
     * @param string $key
     * @param string $id
     *
     * @return EntityNotFoundException
     */
    public static function noMatchingRecordFor(string $class, string $key, string $id): self
    {
        return new self(sprintf('Could not find a record for %s with %s and %s', $class, $key, $id));
    }
}
