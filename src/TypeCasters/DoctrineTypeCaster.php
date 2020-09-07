<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\TypeCasters;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use IlluminateAgnostic\Str\Support\Str;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\ReadModels\Utils\StrConverter;
use function array_key_exists;
use function array_keys;
use function is_null;

/**
 * Class DoctrineTypeCaster
 *
 * @package    Somnambulist\Components\ReadModels\Hydrators
 * @subpackage Somnambulist\Components\ReadModels\TypeCasters\DoctrineTypeCaster
 */
final class DoctrineTypeCaster implements AttributeCasterInterface
{

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function types(): array
    {
        return array_keys(Type::getTypesMap());
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types()) || Str::startsWith($type, 'resource:');
    }

    public function cast(array &$attributes, $attribute, string $type): void
    {
        if (!array_key_exists($attribute, $attributes)) {
            return;
        }
        
        $value = $attributes[$attribute];

        if (Str::startsWith($type, 'resource:')) {
            $value = is_null($value) ? null : StrConverter::toResource($value);
            $type  = Str::replaceFirst('resource:', '', $type);
        }

        $attributes[$attribute] = Type::getType($type)->convertToPHPValue($value, $this->connection->getDatabasePlatform());
    }
}
