<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\PHPUnit;

use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\BeforeTestHook;
use Somnambulist\ReadModels\ModelIdentityMap;

/**
 * Class PHPUnitListener
 *
 * Ensures that the identity map is cleared before and after every test case is run.
 * If this is not used, then the identity map will persist across tests, giving false
 * results.
 *
 * Enable it by adding the following to your phpunit.xml file:
 *
 * <code>
 *     <extensions>
 *         <extension class="Somnambulist\ReadModels\PHPUnit\PHPUnitListener" />
 *     </extensions>
 * </code>
 *
 * @package    Somnambulist\ReadModels\PHPUnit
 * @subpackage Somnambulist\ReadModels\PHPUnit\PHPUnitListener
 */
class PHPUnitListener implements BeforeTestHook, AfterTestHook
{

    public function executeAfterTest(string $test, float $time): void
    {
        ModelIdentityMap::instance()->clear();
    }

    public function executeBeforeTest(string $test): void
    {
        ModelIdentityMap::instance()->clear();
    }
}
