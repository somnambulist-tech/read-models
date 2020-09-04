<?php declare(strict_types=1);

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

    public static function noMatchingRecordFor(string $class, string $key, $id): self
    {
        return new self(sprintf('Could not find a record for %s with %s and %s', $class, $key, $id));
    }
}
