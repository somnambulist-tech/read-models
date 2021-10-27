<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Exceptions;

use Exception;
use function sprintf;

/**
 * Class EntityNotFoundException
 *
 * @package    Somnambulist\Components\ReadModels\Exceptions
 * @subpackage Somnambulist\Components\ReadModels\Exceptions\EntityNotFoundException
 */
class EntityNotFoundException extends Exception
{
    public static function noMatchingRecordFor(string $class, string $key, $id): self
    {
        return new self(sprintf('Could not find a record for %s with %s and %s', $class, $key, $id), 404);
    }
}
