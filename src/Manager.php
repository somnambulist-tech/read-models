<?php declare(strict_types=1);

namespace Somnambulist\ReadModels;

use RuntimeException;
use function sprintf;

/**
 * Class ModelRegistry
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\ModelRegistry
 */
final class Manager
{

    private static ?Manager $instance = null;
    private ConnectionManager $connections;
    private AttributeCaster $caster;
    private ModelIdentityMap $map;

    public function __construct(array $connections = [], iterable $casters = [])
    {
        $this->connections = new ConnectionManager($connections);
        $this->caster      = new AttributeCaster($casters);
        $this->map         = new ModelIdentityMap();

        self::$instance = $this;
    }

    public static function instance(): Manager
    {
        if (!self::$instance instanceof Manager) {
            throw new RuntimeException(
                sprintf(
                    '%s has not been instantiated; you must first create a new instance before accessing the registry statically',
                    static::class
                )
            );
        }

        return self::$instance;
    }

    public static function clear(): void
    {
        if (self::$instance instanceof Manager) {
            self::$instance->map()->clear();
        }
    }

    public function connection(): ConnectionManager
    {
        return $this->connections;
    }

    public function caster(): AttributeCaster
    {
        return $this->caster;
    }

    public function map(): ModelIdentityMap
    {
        return $this->map;
    }
}
