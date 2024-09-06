# WordPress Plugin Interface

A simple PHP interface designed to provide a consistent structure for WordPress plugins. It defines a set of methods that any implementing class must adhere to, ensuring uniformity and ease of use across different plugins.

## Installation

Simply include the `PluginInterface.php` file in your project, or install it via Composer:

```bash
composer require devuri/plugin-interface
```

## Usage

### Implementing the Plugin Interface

To create a plugin using the Plugin Interface, follow these steps:

1. **Implement the PluginInterface**: Create a class that implements the `PluginInterface`. This class will define the behavior of your plugin.

2. **Define Class Properties**: Define class properties to hold the plugin directory path and URL. These properties will be used as initialization parameters.

3. **Implement the init() Method**: Implement the `init()` method to initialize your plugin. Use the class properties to set the plugin directory path and URL.

4. **Implement the hooks() Method**: Implement the `hooks()` method to register any WordPress hooks (actions and filters) necessary for your plugin.

### Using the Plugin Interface

To use a plugin that implements the Plugin Interface, follow these steps:

1. **Initialize the Plugin**: Call the `init()` method of the plugin class, passing the plugin directory path and URL as parameters.

2. **Use the Plugin**: Once initialized, you can use the plugin as needed. Any hooks registered by the plugin will be automatically executed by WordPress.

## Example

Here's an example implementation of a plugin using the Plugin Interface:

```php
<?php

use Urisoft\PluginInterface;

class MyPlugin implements PluginInterface
{
    public static $plugin_dir_path;
    public static $plugin_dir_url;

    public static function init(string $plugin_dir_path = '', string $plugin_dir_url = ''): object
    {
        static $instance = [];

        $called_class = static::class;

        if (!isset($instance[$called_class])) {
            $instance[$called_class] = new $called_class();
        }

        self::$plugin_dir_path = $plugin_dir_path;
        self::$plugin_dir_url = $plugin_dir_url;

        return $instance[$called_class];
    }

    public function hooks(): void
    {
        // Register hooks here using WordPress's hook registration functions
        // For example:
        // add_action('init', [$this, 'my_init_function']);
        // add_filter('the_content', [$this, 'my_content_filter']);
    }
}
```

> Or you can also use the base abstract Implementation, which will include `init()` and the required properties `$plugin_dir_path` and `$plugin_dir_url`
```php
<?php

use Urisoft\AbstractPlugin;

class MyPlugin extends AbstractPlugin
{
    public function hooks(): void
    {
        // Register hooks here using WordPress's hook registration functions
        // For example:
        // add_action('init', [$this, 'my_init_function']);
        // add_filter('the_content', [$this, 'my_content_filter']);
    }
}
```

Below is an example of how you can instantiate the plugin class, set the plugin path and  url:

```php
<?php

// Get the plugin directory path
$plugin_dir_path = wp_plugin_dir_path(__FILE__);

// Define the plugin URL
$plugin_dir_url = plugin_dir_url(__FILE__);

// Initialize the plugin
$my_plugin = MyPlugin::init($plugin_dir_path, $plugin_dir_url);

// Optionally, call the hooks() method to register hooks
$my_plugin->hooks();
```

Explanation:

1. **Include Plugin Interface and Class**: The `PluginInterface` is included to ensure the class adheres to the interface.

2. **Get Plugin Directory Path**: The `wp_plugin_dir_path()` function is used to retrieve the directory path of the plugin file (`__FILE__`). This ensures that the plugin directory path is always accurate.

3. **Define Plugin URL**: The `plugin_dir_url()` function is used to construct the URL of the plugin directory. This URL can be used to enqueue scripts, styles, or create links within the plugin.

4. **Initialize the Plugin**: The `init()` method of the `MyPlugin` class is called, passing the plugin directory path and URL as parameters. This initializes an instance of the plugin is in the application.

5. **Call hooks() Method**: Optionally, the `hooks()` method of the plugin class can be called to register any WordPress hooks necessary for the plugin's functionality.

Benefits:

- **Consistency**: By using `wp_plugin_dir_path()` and `plugin_dir_url()`, you ensure that the plugin directory path and URL are always accurate and consistent.

- **Security**: Using `wp_plugin_dir_path()` ensures that the directory path is properly sanitized and secure, reducing the risk of directory traversal attacks.

- **Flexibility**: The plugin directory path and URL can be easily retrieved and used throughout the plugin code, allowing for dynamic file and URL generation without hardcoding paths or URLs.

- **Compatibility**: The use of WordPress functions (`wp_plugin_dir_path()` and `plugin_dir_url()`) ensures compatibility with future WordPress updates and changes to the file structure.

> using `wp_plugin_dir_path()` and `plugin_dir_url()` to set the plugin path and URL provides a robust approach to plugin development.


## TraitInstalled

The `TraitInstalled` on `AbstractPlugin` provides utility methods to check if a WordPress plugin is installed and active. It helps manage plugin dependencies efficiently.

- **Check if a plugin is installed**
- **Check if a plugin is active**
- **Retrieve all installed plugins**

### 1. `MyPlugin::is_installed($plugin_file)`

Checks if a plugin is installed.

- **Parameter**:  
  `$plugin_file` (string) - Path to the plugin file, e.g., `'example-plugin/example-plugin.php'`.

- **Returns**:  
  `bool` - `true` if installed, `false` otherwise.

- **Example**:
    ```php
    if (MyPlugin::is_installed('example-plugin/example-plugin.php')) {
        echo 'Plugin is installed.';
    }
    ```

### 2. `MyPlugin::is_active($plugin_file)`

Checks if a plugin is active.

- **Parameter**:  
  `$plugin_file` (string) - Path to the plugin file, e.g., `'example-plugin/example-plugin.php'`.

- **Returns**:  
  `bool` - `true` if active, `false` otherwise.

- **Example**:
    ```php
    if (MyPlugin::is_active('example-plugin/example-plugin.php')) {
        echo 'Plugin is active.';
    }
    ```

### 3. `MyPlugin::get_installed_plugins()`

Retrieves all installed plugins.

- **Returns**:  
  `array` - An array of installed plugins.

- **Example**:
    ```php
    $installed_plugins = MyPlugin::get_installed_plugins();
    print_r($installed_plugins);
    ```

### Example Usage

Check if a plugin is both installed and active:

```php
function is_example_plugin_ready() {
    $plugin_file = 'example-plugin/example-plugin.php';
    return MyPlugin::is_installed($plugin_file) && Plugin::is_active($plugin_file);
}

if (!is_example_plugin_ready()) {
    // Show admin notice or handle plugin dependency
}
```

## Benefits of the Interface

- **Interface Segregation Principle (ISP)**: The interface follows the ISP by defining only the essential methods (`init()` and `hooks()`), ensuring that implementing classes aren't burdened with unnecessary dependencies.

- **Separation of Concerns**: The interface separates initialization (`init()`) from hook registration (`hooks()`), promoting clearer code organization and making the plugin's behavior easier to understand and maintain.

- **Clean Constructor**: By keeping the constructor clean, the interface adheres to the Single Responsibility Principle (SRP), ensuring that the class is only responsible for instantiation, which supports better code maintainability.

- **Support for Standalone Unit Tests**: The clean constructor enables easy standalone unit testing. With minimal initialization logic, the class can be instantiated in isolation for focused testing, improving testability.

- **Flexibility in Implementation**: The interface's minimal method requirements allow implementing classes the flexibility to achieve desired functionality, accommodating various plugin architectures and implementation strategies while ensuring consistent usage.

- **Promotes Dependency Injection**: The `init()` method acts as a form of dependency injection, enabling clients to provide external dependencies (such as plugin directory path and URL) to the implementing class. This fosters decoupling and enhances flexibility and reusability.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
