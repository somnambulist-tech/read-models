<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use Somnambulist\ReadModels\Contracts\AttributeCaster;
use Somnambulist\ReadModels\Contracts\EmbeddableFactory;

/**
 * Class ModelConfigurator
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\ModelConfigurator
 */
final class ModelConfigurator
{

    /**
     * Configures the base Model settings
     *
     * Connections is an array of Model name (or default) and the DBAL Connection instance
     * $caster and $factory are optional, alternative hydrator instances to set to the Model.
     *
     * Note: while the caster and factor are protected static instances, they should be shared
     * across all models that use the same database.
     *
     * @param array                  $connections
     * @param AttributeCaster|null   $caster
     * @param EmbeddableFactory|null $factory
     */
    public static function configure(array $connections, ?AttributeCaster $caster = null, ?EmbeddableFactory $factory = null): void
    {
        foreach ($connections as $model => $connection) {
            Model::bindConnection($connection, $model);
        }

        if (null !== $caster) {
            Model::bindAttributeCaster($caster);
        }
        if (null !== $factory) {
            Model::bindEmbeddableFactory($factory);
        }
    }
}
