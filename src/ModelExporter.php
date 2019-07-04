<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use Somnambulist\ReadModels\Contracts\CanExportToJSON;

/**
 * Class ModelExporter
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\ModelExporter
 */
class ModelExporter implements CanExportToJSON
{

    /**
     * @var Model
     */
    private $model;

    /**
     * @var array
     */
    private $include = [];

    /**
     * Constructor.
     *
     * @param Model $model
     * @param array $include
     */
    public function __construct(Model $model, array $include = [])
    {
        $this->model   = $model;
        $this->include = $include;
    }

    public function with(...$relationship): self
    {
        $this->include = array_merge($this->include, $relationship);

        return $this;
    }

    public function only(...$relationship): self
    {
        $this->include = $relationship;

        return $this;
    }

    /**
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0): string
    {
        $json = json_encode($this->toArray(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::failedToConvertModel($this->model, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Create an array from the model data; including any specified relationships
     *
     * @return array
     */
    public function toArray()
    {
        $array = array_merge($this->model->getAttributes());

        $this->include = [];

        return $array;
    }
}
