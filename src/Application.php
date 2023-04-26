<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace CardanoPress\RolesManager;

use CardanoPress\Foundation\AbstractApplication;
use CardanoPress\Traits\Instantiable;

class Application extends AbstractApplication
{
    use Instantiable;

    protected function initialize(): void
    {
        $this->setInstance($this);
    }

    public function setupHooks(): void
    {
        add_action('cardanopress_loaded', [$this, 'init']);
    }

    public function init(): void
    {
        load_plugin_textdomain($this->getData('TextDomain'));
    }

    public function isReady(): bool
    {
        $function = function_exists('cardanoPress');
        $namespace = 'PBWebDev\\CardanoPress\\';
        $blockfrost = class_exists($namespace . 'Blockfrost');

        return $function && $blockfrost;
    }
}
