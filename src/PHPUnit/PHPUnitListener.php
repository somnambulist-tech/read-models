<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\PHPUnit;

use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\BeforeTestHook;
use Somnambulist\ReadModels\Model;

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
class PHPUnitListener implements TestListener, BeforeTestHook, AfterTestHook
{

    public function executeAfterTest(string $test, float $time): void
    {
        Model::getIdentityMap()->clear();
    }

    public function executeBeforeTest(string $test): void
    {
        Model::getIdentityMap()->clear();
    }

    use TestListenerDefaultImplementation;
}