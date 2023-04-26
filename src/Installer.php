<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace CardanoPress\RolesManager;

use CardanoPress\Foundation\AbstractInstaller;

class Installer extends AbstractInstaller
{
    public const DATA_PREFIX = 'cp_roles-manager_';

    protected function initialize(): void
    {
    }

    public function setupHooks(): void
    {
        parent::setupHooks();

        add_action('admin_notices', [$this, 'noticeNeedingCorePlugin']);
        add_action(self::DATA_PREFIX . 'upgrading', [$this, 'doUpgrade'], 10, 2);
    }

    public function doUpgrade(string $currentVersion, string $appVersion): void
    {
    }
}
