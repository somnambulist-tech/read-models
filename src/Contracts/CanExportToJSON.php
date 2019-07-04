<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Contracts;

/**
 * Interface CanExportToJSON
 *
 * @package    Somnambulist\ReadModels\Contracts
 * @subpackage Somnambulist\ReadModels\Contracts\CanExportToJSON
 */
interface CanExportToJSON
{

    public function toJson($options = 0): string;
}
