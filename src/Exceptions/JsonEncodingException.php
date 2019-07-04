<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use RuntimeException;

/**
 * Class JsonEncodingException
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\JsonEncodingException
 */
class JsonEncodingException extends RuntimeException
{

    /**
     * @param Model  $model
     * @param string $error
     *
     * @return JsonEncodingException
     */
    public static function failedToConvertModel(Model $model, string $error): JsonEncodingException
    {
        return new self(sprintf('Model "%s:%s" could be not be converted to JSON: %s', get_class($model), $model->getId(), $error));
    }
}
