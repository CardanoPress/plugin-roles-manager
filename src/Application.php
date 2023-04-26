<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace CardanoPress\RolesManager;

use CardanoPress\Foundation\AbstractApplication;
use CardanoPress\Traits\Configurable;
use CardanoPress\Traits\Instantiable;

class Application extends AbstractApplication
{
    use Configurable;
    use Instantiable;

    protected function initialize(): void
    {
        $this->setInstance($this);

        $this->admin = new Admin($this->logger('admin'));
    }

    public function setupHooks(): void
    {
        $this->admin->setupHooks();

        add_action('cardanopress_loaded', [$this, 'init']);
    }

    public function init(): void
    {
        load_plugin_textdomain($this->getData('TextDomain'));

        (new Actions())->setupHooks();
    }

    public function isReady(): bool
    {
        $function = function_exists('cardanoPress');
        $namespace = 'PBWebDev\\CardanoPress\\';
        $blockfrost = class_exists($namespace . 'Blockfrost');

        return $function && $blockfrost;
    }
}
