<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\PHPUnit;

use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\BeforeTestHook;
use Somnambulist\Components\ReadModels\Manager;

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
 *         <extension class="Somnambulist\Components\ReadModels\PHPUnit\PHPUnitListener" />
 *     </extensions>
 * </code>
 *
 * @package    Somnambulist\Components\ReadModels\PHPUnit
 * @subpackage Somnambulist\Components\ReadModels\PHPUnit\PHPUnitListener
 */
class PHPUnitListener implements BeforeTestHook, AfterTestHook
{
    public function executeAfterTest(string $test, float $time): void
    {
        Manager::clear();
    }

    public function executeBeforeTest(string $test): void
    {
        Manager::clear();
    }
}
