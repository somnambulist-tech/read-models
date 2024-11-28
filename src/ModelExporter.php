<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels;

use Closure;
use Somnambulist\Components\Collection\Contracts\Jsonable;
use Somnambulist\Components\Collection\MutableCollection as Collection;
use Somnambulist\Components\ReadModels\Exceptions\JsonEncodingException;
use function count;
use function explode;
use function Symfony\Component\String\u;

final class ModelExporter implements Jsonable
{
    private Model $model;
    private array $attributes;
    private array $relationships;

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
     * @param string ...$attributes Attributes individually or an array as the first arg
     *
     * @return ModelExporter
     */
    public function attributes(string ...$attributes): self
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
     */
    public function include(string ...$relationship): self
    {
        $this->relationships = $relationship;

        return $this;
    }

    public function toJson(int $options = 0): string
    {
        $json = json_encode($this->toArray(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::failedToConvertModel($this->model, json_last_error_msg());
        }

        return $json;
    }

    public function toArray(): array
    {
        $array = $this->extractAttributes($this->model->getAttributes());

        foreach ($this->relationships as $relationship) {
            $nested = $attributes = [];
            $test = u($relationship);

            if ($test->containsAny('.')) {
                [$relationship, $nested] = explode('.', $relationship, 2);
                $test = u($relationship);
            }
            if ($test->containsAny(':')) {
                [$relationship, $attributes] = explode(':', $relationship, 2);
                $attributes = explode(',', $attributes);
            }

            $arr   = [];
            $items = $this->model->{$relationship};

            if ($items instanceof Collection) {
                $arr = $items->map(function (Model $model) use ($nested, $attributes) {
                    $export = $model->export()->include(...(array)$nested);

                    if (count($attributes) > 0) {
                        $export->attributes(...$attributes);
                    }

                    return $export->toArray();
                })->toArray();
            } elseif ($items instanceof Model) {
                $export = $items->export()->include(...(array)$nested);

                if (count($attributes) > 0) {
                    $export->attributes(...$attributes);
                }

                $arr = $export->toArray();
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
                $key = $this->getAttributeExtractionKey($key);

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
        return $this->attributes[$key] ?? $key;
    }

    /**
     * @param object $object
     *
     * @return array|string
     */
    private function extractPropertiesFrom(object $object)
    {
        if (method_exists($object, '__toString' )) {
            return (string)$object;
        }

        return $this->extractAttributes(Closure::bind(fn () => get_object_vars($this), $object, $object)());
    }
}
