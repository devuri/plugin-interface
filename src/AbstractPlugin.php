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

abstract class AbstractPlugin implements PluginInterface
{
    use TraitInstalled;

    public static $plugin_dir_path;
    public static $plugin_dir_url;

    public static function init( string $plugin_dir_path = '', string $plugin_dir_url = '' ): PluginInterface
    {
        static $instance = [];

        $called_class = static::class;

        if ( ! isset( $instance[ $called_class ] ) ) {
            $instance[ $called_class ] = new $called_class();
        }

        self::$plugin_dir_path = $plugin_dir_path;
        self::$plugin_dir_url  = $plugin_dir_url;

        return $instance[ $called_class ];
    }

    /**
     * Hooks method.
     *
     * This method should be implemented by the plugin class to define
     * any WordPress hooks (actions and filters) that the plugin needs
     * to register with WordPress.
     *
     * Implementing classes should use WordPress's hook registration
     * functions (e.g., add_action(), add_filter()) within this method
     * to register the necessary hooks.
     */
    abstract public function hooks(): void;
}
