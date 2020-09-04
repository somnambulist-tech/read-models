<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Exceptions;

use Exception;
use function sprintf;

/**
 * Class ConnectionManagerException
 *
 * @package    Somnambulist\ReadModels\Exceptions
 * @subpackage Somnambulist\ReadModels\Exceptions\ConnectionManagerException
 */
class ConnectionManagerException extends Exception
{

    public static function missingConnectionFor(string $model): self
    {
        return new self(sprintf('No connection found for "%s" or "default"', $model));
    }
}
