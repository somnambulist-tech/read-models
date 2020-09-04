<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Exceptions;

use Exception;
use function sprintf;

/**
 * Class AttributeCasterException
 *
 * @package    Somnambulist\ReadModels\Exceptions
 * @subpackage Somnambulist\ReadModels\Exceptions\AttributeCasterException
 */
class AttributeCasterException extends Exception
{

    public static function missingTypeFor(string $type): self
    {
        return new self(sprintf('Missing type caster for "%s"', $type));
    }
}
