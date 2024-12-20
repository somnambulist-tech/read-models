<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\TypeCasters;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\ReadModels\Utils\StrConverter;
use function array_key_exists;
use function array_keys;
use function is_null;
use function Symfony\Component\String\u;

final class DoctrineTypeCaster implements AttributeCasterInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function types(): array
    {
        return array_keys(Type::getTypesMap());
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types()) || u($type)->startsWith('resource:');
    }

    public function cast(array &$attributes, $attribute, string $type): void
    {
        if (!array_key_exists($attribute, $attributes)) {
            return;
        }
        
        $value = $attributes[$attribute];

        if (u($type)->startsWith('resource:')) {
            $value = is_null($value) ? null : StrConverter::toResource($value);
            $type  = u($type)->replace('resource:', '')->toString();
        }

        $attributes[$attribute] = Type::getType($type)->convertToPHPValue($value, $this->connection->getDatabasePlatform());
    }
}
