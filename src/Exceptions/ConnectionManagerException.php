<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Exceptions;

use Exception;
use function sprintf;

class ConnectionManagerException extends Exception
{
    public static function missingConnectionFor(string $model): self
    {
        return new self(sprintf('No connection found for "%s" or "default"', $model));
    }
}
