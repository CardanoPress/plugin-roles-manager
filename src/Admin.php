<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace CardanoPress\RolesManager;

use CardanoPress\Foundation\AbstractAdmin;

class Admin extends AbstractAdmin
{
    public const OPTION_KEY = 'cp-roles-manager';

    protected function initialize(): void
    {
    }

    public function setupHooks(): void
    {
    }
}
