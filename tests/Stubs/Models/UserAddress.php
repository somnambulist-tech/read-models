<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

use Somnambulist\ReadModels\Model;

/**
 * Class UserAddress
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\UserAddress
 */
class UserAddress extends Model
{

    protected string $table = 'user_addresses';

    protected ?string $tableAlias = 'ua';

    protected array $casts = [
        'country'    => 'country',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'address'    => Address::class,
    ];

    protected array $exports = [
        'attributes'    => [
            'country', 'address_line_1', 'address_line_2',
            'address_town'     => 'town',
            'address_county'   => 'county',
            'address_postcode' => 'postcode',

        ],
        'relationships' => [],
    ];

    protected function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function fixed_user()
    {
        return $this->belongsTo(User::class, null, null, null, false);
    }
}
