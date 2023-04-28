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
        add_action('plugins_loaded', function () {
            $this->settingsPage('CardanoPress - Roles Manager', [
                'parent' => Application::getInstance()->isReady() ? 'cardanopress' : '',
                'menu_title' => 'Roles Manager',
            ]);
        });

        add_action('init', function () {
            $this->perAttributeFields();
            $this->perQuantityFields();
            $this->stakeAddressFields();
        });
    }

    protected function perAttributeFields(): void
    {
        $this->optionFields(__('Per Attribute', 'cardanopress-roles-manager'), [
            'data_prefix' => 'token_attribute_',
            'description' => __('Assigning a user role based on token attribute.', 'cardanopress-roles-manager'),
            'fields' => [
                'access' => [
                    'type' => 'group',
                    'default' => [
                        [
                            'name' => '',
                            'role' => '',
                            'id' => '',
                            'location' => '',
                            'key' => '',
                            'value' => '',
                        ],
                    ],
                    'repeatable' => true,
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'description' => __('Name of the token.', 'cardanopress-roles-manager'),
                            'title' => __('Asset Name', 'cardanopress-roles-manager'),
                        ],
                        'role' => [
                            'type' => 'select',
                            'description' => __('Role to assign to user.', 'cardanopress-roles-manager'),
                            'title' => __('Additional Role', 'cardanopress-roles-manager'),
                            'options' => wp_roles()->role_names,
                        ],
                        'id' => [
                            'type' => 'text',
                            'description' => __('Policy ID of the token.', 'cardanopress-roles-manager'),
                            'title' => __('Policy ID', 'cardanopress-roles-manager'),
                        ],
                        'location' => [
                            'type' => 'radio',
                            'title' => __('Location', 'cardanopress-roles-manager'),
                            'options' => [
                                'onchain_metadata' => 'onchain_metadata',
                                'metadata' => 'metadata',
                            ],
                        ],
                        'key' => [
                            'type' => 'text',
                            'title' => __('Key', 'cardanopress-roles-manager'),
                        ],
                        'value' => [
                            'type' => 'text',
                            'title' => __('Value', 'cardanopress-roles-manager'),
                        ],
                    ],
                ],
            ],
        ]);
    }

    protected function perQuantityFields(): void
    {
        $this->optionFields(__('Per Quantity', 'cardanopress-roles-manager'), [
            'data_prefix' => 'token_quantity_',
            'description' => __('Assigning a user role based on token quantity.', 'cardanopress-roles-manager'),
            'fields' => [
                'access' => [
                    'type' => 'group',
                    'default' => [
                        [
                            'name' => '',
                            'role' => '',
                            'id' => '',
                            'count' => 1,
                        ],
                    ],
                    'repeatable' => true,
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'description' => __('Name of the token.', 'cardanopress-roles-manager'),
                            'title' => __('Asset Name', 'cardanopress-roles-manager'),
                        ],
                        'role' => [
                            'type' => 'select',
                            'description' => __('Role to assign to user.', 'cardanopress-roles-manager'),
                            'title' => __('Additional Role', 'cardanopress-roles-manager'),
                            'options' => wp_roles()->role_names,
                        ],
                        'id' => [
                            'type' => 'text',
                            'title' => __('Asset ID', 'cardanopress-roles-manager'),
                        ],
                        'count' => [
                            'type' => 'number',
                            'title' => __('Count', 'cardanopress-roles-manager'),
                            'default' => 1,
                        ],
                    ],
                ],
            ],
        ]);
    }

    protected function stakeAddressFields(): void
    {
        $this->optionFields(__('Stake Address', 'cardanopress-roles-manager'), [
            'data_prefix' => 'stake_address_',
            'description' => __('Assigning a user role on specific stake address.', 'cardanopress-roles-manager'),
            'fields' => [
                'access' => [
                    'type' => 'group',
                    'default' => [
                        [
                            'name' => '',
                            'role' => '',
                            'id' => '',
                        ],
                    ],
                    'repeatable' => true,
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'title' => __('Wallet Label', 'cardanopress-roles-manager'),
                        ],
                        'role' => [
                            'type' => 'select',
                            'description' => __('Role to assign to user.', 'cardanopress-roles-manager'),
                            'title' => __('Additional Role', 'cardanopress-roles-manager'),
                            'options' => wp_roles()->role_names,
                        ],
                        'id' => [
                            'type' => 'text',
                            'title' => __('Bech32 stake address', 'cardanopress-roles-manager'),
                        ],
                    ],
                ],
            ],
        ]);
    }
}
