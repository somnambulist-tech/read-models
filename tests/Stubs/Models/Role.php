<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

use Somnambulist\Collection\MutableCollection;
use Somnambulist\ReadModels\Model;

/**
 * Class Role
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\Role
 *
 * @property-read MutableCollection permissions
 */
class Role extends Model
{

    protected $tableAlias = 'r';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
