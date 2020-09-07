<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Exceptions;

use Exception;
use function sprintf;

/**
 * Class AttributeCasterException
 *
 * @package    Somnambulist\Components\ReadModels\Exceptions
 * @subpackage Somnambulist\Components\ReadModels\Exceptions\AttributeCasterException
 */
class AttributeCasterException extends Exception
{

    public static function missingTypeFor(string $type): self
    {
        return new self(sprintf('Missing type caster for "%s"', $type));
    }
}
