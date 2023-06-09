<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace CardanoPress\RolesManager;

use CardanoPress\Helpers\WalletHelper;
use CardanoPress\Interfaces\HookInterface;
use PBWebDev\CardanoPress\Blockfrost;
use PBWebDev\CardanoPress\Profile;
use WP_User;

class Actions implements HookInterface
{
    protected Application $application;

    public function __construct()
    {
        $this->application = Application::getInstance();
    }

    public function setupHooks(): void
    {
        add_action('cardanopress_wallet_status_checks', [$this, 'checkWallet']);
        add_action('cardanopress_associated_assets', [$this, 'checkAssets'], 10, 2);
    }

    public function checkWallet(WP_User $user): void
    {
        $userProfile = new Profile($user);
        $conditions = array_filter($this->application->option('stake_address_access'), function ($access) {
            return (
                ! empty($access['role']) &&
                ! empty($access['id'])
            );
        });
        $accessIds = array_column($conditions, 'id');

        foreach (array_keys($accessIds, $userProfile->connectedStake(), true) as $index) {
            if ($userProfile->hasRole($conditions[$index]['role'])) {
                continue;
            }

            $userProfile->addRole($conditions[$index]['role']);
        }
    }

    protected function checkTokenQuantityAccess(string $id, int $quantity): void
    {
        $userProfile = new Profile(wp_get_current_user());
        $conditions = array_filter($this->application->option('token_quantity_access'), function ($access) {
            return (
                ! empty($access['role']) &&
                ! empty($access['id']) &&
                ! empty($access['count'])
            );
        });
        $accessIds = array_column($conditions, 'id');

        foreach (array_keys($accessIds, $id, true) as $index) {
            if (
                $userProfile->hasRole($conditions[$index]['role']) ||
                $quantity < $conditions[$index]['count']
            ) {
                continue;
            }

            $userProfile->addRole($conditions[$index]['role']);
        }
    }

    protected function checkTokenAttributeAccess(array $data): void
    {
        $userProfile = new Profile(wp_get_current_user());
        $conditions = array_filter($this->application->option('token_attribute_access'), function ($access) {
            return (
                ! empty($access['role']) &&
                ! empty($access['id']) &&
                ! empty($access['location']) &&
                ! empty($access['key']) &&
                ! empty($access['value'])
            );
        });

        foreach ($conditions as $condition) {
            if ($userProfile->hasRole($condition['role']) || ! $this->tokenConditionMet($data, $condition)) {
                continue;
            }

            $userProfile->addRole($condition['role']);
        }
    }

    protected function tokenConditionMet(array $asset, array $condition): bool
    {
        if ($asset['policy_id'] !== $condition['id']) {
            return false;
        }

        $data = $asset[$condition['location']];

        if (empty($data[$condition['key']])) {
            return false;
        }

        return $data[$condition['key']] === $condition['value'];
    }

    public function checkAssets(string $stakeAddress, array $response): void
    {
        $queryNetwork = WalletHelper::getNetworkFromStake($stakeAddress);
        $blockfrost = new Blockfrost($queryNetwork);

        foreach ($response as $asset) {
            $this->checkTokenQuantityAccess($asset['unit'], $asset['quantity']);

            $data = $blockfrost->specificAsset($asset['unit']);

            if (! empty($data)) {
                $this->checkTokenAttributeAccess($data);
            }
        }
    }
}
