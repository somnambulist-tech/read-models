<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Exceptions;

use RuntimeException;
use Somnambulist\Components\ReadModels\Model;
use function get_class;
use function sprintf;

class JsonEncodingException extends RuntimeException
{
    public static function failedToConvertModel(Model $model, string $error): JsonEncodingException
    {
        return new self(
            sprintf(
                'Model "%s:%s" could be not be converted to JSON: %s',
                get_class($model), $model->getPrimaryKey(), $error
            )
        );
    }
}
