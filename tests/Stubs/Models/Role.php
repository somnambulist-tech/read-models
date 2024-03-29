<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Stubs\Models;

use Somnambulist\Components\Collection\MutableCollection;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\Relationships\BelongsToMany;

/**
 * @property-read MutableCollection permissions
 */
class Role extends Model
{

    protected string $table = 'roles';

    protected ?string $tableAlias = 'r';

    protected array $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function permissions2(): BelongsToMany
    {
        $rel = $this->belongsToMany(Permission::class, 'role_permissions');
        $rel->orderBy('id', 'DESC');

        return $rel;
    }
}
