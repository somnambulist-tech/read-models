<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\PHPUnit;

use PHPUnit\Event\Test\Prepared;
use PHPUnit\Event\Test\PreparedSubscriber;
use Somnambulist\Components\ReadModels\Manager;

/**
 * Ensures that the identity map is cleared before and after every test case is run.
 * If this is not used, then the identity map will persist across tests, giving false
 * results.
 *
 * Enable it by adding the following to your phpunit.xml file:
 *
 * <code>
 *     <extensions>
 *         <bootstrap class="Somnambulist\Components\ReadModels\PHPUnit\ReadModelExtension" />
 *     </extensions>
 * </code>
 */
class ClearManagerWhenTestPrepared implements PreparedSubscriber
{
    public function notify(Prepared $event): void
    {
        Manager::clear();
    }
}
