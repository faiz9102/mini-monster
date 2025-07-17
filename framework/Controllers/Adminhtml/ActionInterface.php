<?php
declare(strict_types=1);

namespace Framework\Controllers\Adminhtml;

use Framework\Controllers\ActionInterface as BaseActionInterface;
interface ActionInterface extends BaseActionInterface
{
    public const string REQUEST_ORIGIN = 'adminhtml';

    public function isAdminRequest() : bool;

    public function getRequestOrigin() : string;
}