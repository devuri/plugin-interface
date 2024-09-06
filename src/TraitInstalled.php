<?php
/**
 * Plugin Interface.
 *
 * This file defines the PluginInterface, which provides a contract for classes representing plugins.
 *
 * @package Urisoft\PluginInterface
 *
 * @copyright Copyright (c) 2024 Uriel Wilson
 * @license MIT License
 */

namespace Urisoft;

trait TraitInstalled
{
    /**
     * Checks if a plugin is installed.
     *
     * @param string $plugin_file The file path of the plugin relative to the plugins directory.
     *
     * @return bool True if the plugin is installed, false otherwise.
     */
    public static function is_installed( $plugin_file ): bool
    {
        $installed_plugins = self::get_installed_plugins();

        return isset( $installed_plugins[ $plugin_file ] );
    }

    /**
     * Checks if a plugin is active.
     *
     * @param string $plugin_file The file path of the plugin relative to the plugins directory.
     *
     * @return bool True if the plugin is active, false otherwise.
     */
    public static function is_active( $plugin_file )
    {
        if ( ! \function_exists( 'is_plugin_active' ) ) {
            // @phpstan-ignore-next-line
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active( $plugin_file );
    }

    /**
     * Retrieves all installed plugins.
     *
     * @return array An array of installed plugins.
     */
    public static function get_installed_plugins(): array
    {
        return get_plugins();
    }
}
