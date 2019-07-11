<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use Closure;
use IlluminateAgnostic\Str\Support\Str;
use Somnambulist\Collection\MutableCollection as Collection;
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
    private $attributes = [];

    /**
     * @var array
     */
    private $relationships = [];

    /**
     * Constructor.
     *
     * @param Model $model
     * @param array $attributes
     * @param array $relationships
     */
    public function __construct(Model $model, array $attributes = [], array $relationships = [])
    {
        $this->model         = $model;
        $this->attributes    = $attributes;
        $this->relationships = $relationships;
    }

    /**
     * Export only the specified attributes; if empty will export all attributes
     *
     * {@see Model::$exports} for example.
     *
     * @param string ...$attributes
     *
     * @return ModelExporter
     */
    public function attributes(...$attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Export with the specified relationships; if empty will NOT export any relationships
     *
     * @param string ...$relationship
     *
     * @return ModelExporter
     * @todo allow field config using <relationship>:field,field2,field3 like the builder?
     */
    public function with(...$relationship): self
    {
        $this->relationships = $relationship;

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
    public function toArray(): array
    {
        $array = $this->extractAttributes($this->model->getAttributes());

        foreach ($this->relationships as $relationship) {
            $nested = [];

            if (Str::contains($relationship, '.')) {
                [$relationship, $nested] = explode('.', $relationship, 1);
            }

            $arr   = [];
            $items = $this->model->{$relationship};

            if ($items instanceof Collection) {
                $arr = $items->map(function (Model $model) use ($nested) {
                    return $model->export()->with(...(array)$nested)->toArray();
                })->toArray();
            } elseif ($items instanceof Model) {
                $arr = $items->export()->with(...(array)$nested)->toArray();
            }

            $array[$relationship] = $arr;
        }

        return $array;
    }

    private function extractAttributes(array $attributes): array
    {
        $attrs = [];

        foreach ($attributes as $key => $value) {
            if ($this->shouldExtractAttribute($key)) {
                $key = Str::snake($this->getAttributeExtractionKey($key), '_');

                if (is_object($value)) {
                    $attrs[$key] = $this->extractPropertiesFrom($value);
                } else {
                    $attrs[$key] = $value;
                }
            }
        }

        return $attrs;
    }

    private function shouldExtractAttribute(string $key): bool
    {
        return
            empty($this->attributes)
            ||
            in_array($key, $this->attributes)
            ||
            array_key_exists($key, $this->attributes)
        ;
    }

    private function getAttributeExtractionKey(string $key): string
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return $key;
    }

    /**
     * Extracts any object properties, or converts to a string if possible
     *
     * @param object $object
     *
     * @return array|string
     */
    private function extractPropertiesFrom(object $object)
    {
        if (method_exists($object, '__toString' )) {
            return (string)$object;
        }

        return $this->extractAttributes(Closure::bind(function () {
            return get_object_vars($this);
        }, $object, $object)());
    }
}
