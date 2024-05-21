<?php

/**
 * Plugin Name: CardanoPress - Roles Manager
 * Plugin URI:  https://github.com/CardanoPress/plugin-roles-manager
 * Author:      Gene Alyson Fortunado Torcende
 * Author URI:  https://cardanopress.io
 * Description: A CardanoPress extension for managing roles and capabilities.
 * Version:     0.1.0
 * License:     GPL-2.0-only
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: cardanopress-roles-manager
 *
 * Requires at least: 5.9
 * Requires PHP:      7.4
 *
 * Requires Plugins: cardanopress
 *
 * @package ThemePlate
 * @since   0.1.0
 */

// Accessed directly
if (! defined('ABSPATH')) {
    exit;
}

use CardanoPress\RolesManager\Application;
use CardanoPress\RolesManager\Installer;

/* ==================================================
Global constants
================================================== */

if (! defined('CP_ROLES_MANAGER_FILE')) {
    define('CP_ROLES_MANAGER_FILE', __FILE__);
}

// Load the main plugin class
require_once plugin_dir_path(CP_ROLES_MANAGER_FILE) . 'dependencies/vendor/autoload_packages.php';

// Instantiate the updater
EUM_Handler::run(CP_ROLES_MANAGER_FILE, 'https://raw.githubusercontent.com/CardanoPress/plugin-roles-manager/main/update-data.json');

function cpRolesManager(): Application
{
    static $application;

    if (null === $application) {
        $application = new Application(CP_ROLES_MANAGER_FILE);
    }

    return $application;
}

// Instantiate
cpRolesManager()->setupHooks();
(new Installer(cpRolesManager()))->setupHooks();
