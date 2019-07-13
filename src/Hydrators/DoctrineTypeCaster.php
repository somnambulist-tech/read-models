<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Hydrators;

use Doctrine\DBAL\Types\Type;
use IlluminateAgnostic\Str\Support\Str;
use Somnambulist\ReadModels\Contracts\AttributeCaster;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\Utils\StrConverter;
use function is_null;
use function trim;

/**
 * Class DoctrineTypeCaster
 *
 * @package    Somnambulist\ReadModels\Hydrators
 * @subpackage Somnambulist\ReadModels\Hydrators\DoctrineTypeCaster
 */
class DoctrineTypeCaster implements AttributeCaster
{

    /**
     * Cast attributes to a configured Doctrine type through the model connection
     *
     * Casts is an array of attribute name to a valid Doctrine type. The type must be
     * registered to use it. Certain types require a resource and for these `resource:`
     * should be prefixed on the attribute name. This will trigger a conversion of the
     * stream to a resource stream. e.g. for Creof Postgres geo-spatial types.
     *
     * The modified attributes are returned to the caller.
     *
     * Note: Doctrine Types are case-sensitive.
     *
     * @param Model $model
     * @param array $attributes
     * @param array $casts
     *
     * @return array
     */
    public function cast(Model $model, array $attributes = [], array $casts = []): array
    {
        foreach ($attributes as $key => $value) {
            $key = $model->removeTableAliasFrom($key);

            if (null !== $type = $this->getCastType($casts, $key)) {
                if (Str::startsWith($type, 'resource:')) {
                    $value = is_null($value) ? null : StrConverter::toResource($value);
                    $type  = Str::replaceFirst('resource:', '', $type);
                }

                $value = Type::getType($type)->convertToPHPValue(
                    $value, $model::connection(static::class)->getDatabasePlatform()
                );
            }

            $attributes[$key] = $value;
        }

        return $attributes;
    }

    /**
     * @param array  $casts
     * @param string $key
     *
     * @return string|null
     */
    private function getCastType(array $casts, string $key): ?string
    {
        $cast = $casts[$key] ?? null;

        return $cast ? trim($cast) : $cast;
    }
}
