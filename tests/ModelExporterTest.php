<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class ModelExporterTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\ModelExporterTest
 */
class ModelExporterTest extends TestCase
{


    public function testFind()
    {
        $timer = new Stopwatch();
        $timer->start('load');
//        $ent = User::with('addresses', 'contacts', 'roles.permissions', 'relatedTo')->limit(5)->fetch();


        $ent = User::with('addresses', 'contacts', 'roles.permissions', 'relatedTo')->find(23);


    }
}
