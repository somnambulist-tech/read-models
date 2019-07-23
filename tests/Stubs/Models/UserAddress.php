<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

use Somnambulist\Domain\Entities\Types\Geography\Country;
use Somnambulist\ReadModels\Model;

/**
 * Class UserAddress
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\UserAddress
 */
class UserAddress extends Model
{

    protected $table = 'user_addresses';

    protected $tableAlias = 'ua';

    protected $casts = [
        'country'    => Country::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $embeds = [
        'address' => [
            Address::class, [
                '?address_line_1',
                '?address_line_2',
                '?address_town',
                '?address_county',
                '?address_postcode',
            ],
        ],
    ];

    protected $exports = [
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
}
