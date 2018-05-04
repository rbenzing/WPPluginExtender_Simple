WordPress Development Tutorial

## 0. One Rule to Rule them All
- One cardinal rule in WordPress development, it’s this: Don’t touch WordPress core

## 1. Two ways to create a plugin.
Object-Oriented Programming - a programming technique that uses a grouping (class) of related methods (functions) to define a computer program or part of a computer program.
Functional Programming - using the internal functions provided by WordPress to hook into their platform.

## 2. PHP Scripting Conventions (PHP Standard Recommendation) - PSR-1 & PSR-2 Compliance

Below is an example of parameters you can use to comment your code:
```
/**
 * Summary.
 *
 * Description.
 *
 * @since x.x.x
 * @depreciated x.x.x Use new_function_name()
 * @see Function/method/class relied on
 * @link URL
 * @global type $varname Description.
 * @global type $varname Description.
 *
 * @param type $var Description.
 * @param type $var Optional. Description. Default.
 * @return type Description.
 */
```

**The following is a list of what should be documented in WordPress files:**

Functions and class methods
Classes
Class members (including properties and constants)
Requires and includes
Hooks (actions and filters)
Constants

## 3. Plugin Header Requirements
The follow is a list of header comments:
```
/**
 * Plugin Name: (required) The name of your plugin, which will be displayed in the Plugins list in the WordPress Admin.
 * Plugin URI: The home page of the plugin, which should be a unique URL, preferably on your own website. This must be unique to your plugin. You cannot use a WordPress.org URL here.
 * Description: A short description of the plugin, as displayed in the Plugins section in the WordPress Admin. Keep this description to fewer than 140 characters.
 * Version: The current version number of the plugin, such as 1.0 or 1.0.3.
 * Author: The name of the plugin author. Multiple authors may be listed using commas.
 * Author URI: The author’s website or profile on another website, such as WordPress.org.
 * License: The short name (slug) of the plugin’s license (e.g. GPL2, Apache, MIT). More information about licensing can be found in the WordPress.org guidelines.
 * License URI: A link to the full text of the license (e.g. https://www.gnu.org/licenses/gpl-2.0.html).
 * Text Domain: The gettext text domain of the plugin. More information can be found in the Text Domain section of the How to Internationalize your Plugin page.
 * Domain Path: The domain path let WordPress know where to find the translations. More information can be found in the Domain Path section of the How to Internationalize your Plugin page.
 */
```
- Use php comment blocks /* Variable: Value */ not double slashes // Variable: Value
- Why use Licensing? it's required if you want your plugin in the WordPress plugin repository
- Place license in your plugin head after your header comments

## 4. Folder Structure / Plugin Name 

Executable plugin file is typically the folder name .php but can be named anything using OOP anything but index.php which contains "<?php // Silence is golden" so to prevent directory browsing.

## 5. Constants

**ABSPATH** - (WordPress Absolute Path) can be used to add to the top of every plugin file to check for proper instantiation. This prevents direct access to your plugin files from via outside scripts or crawlers.
```
if(!defined('ABSPATH')){ die; } or defined('ABSPATH') or die('Hack attempt');
```
Other cool constants builtin to WordPress:

**DOING_AJAX** - run some code only when a WordPress ajax request is running.
```
if(is_admin() && (!defined(‘DOING_AJAX’) || !DOING_AJAX)){ // do something }
```
**DOING_CRON** - run some code only when a WordPress cron job is running
```
if(defined(‘DOING_CRON) && DOING_CRON){ // do something }
```
## 6. class_exists or function_exists
A function for checking if your class or function is already loaded in WordPress. This is used to avoid code conflicts and prevent errors and broken code.
```
if(!function_exists('my_custom_function_name')){
  // run code
}
if(!class_exists('ClassName')){
  // run code
}
if(!method_exists($classvar, 'MethodName')) {
  // run code
}
```
- Use PHP Visibility Methods When Using OOP to help with overall stability and security of your plugin:
 Public - Can be accessed everywhere
 Protected - Can be accessed within the class or extensions of the class
 Private -  Can be accessed only within the class
 
- Class Visibility Methods:
  final - do not allow extension of the class to other classes

## 7. Unique Class Name or Function Names
Use a unique name as to not conflict with any other plugins.

Use casing: Pascal Case (ExampleClass), Camel Case (exampleClass), Underscore (example_class) something to follow.

- PHP recommends PSR-1 (PHP Standard Recommendation 1) and PSR-2

## 7. Activation / Deactivation / Uninstall Hooks
Activation and deactivation hooks provide ways to perform actions when plugins are activated or deactivated.

On activation, plugins can run a routine to add rewrite rules, add custom database tables, or set default option values.
```
register_activation_hook( __FILE__, 'pluginprefix_callback_function_to_run' );
```
On deactivation, plugins can run a routine to remove temporary data such as cache and temp files and directories.
```
register_deactivation_hook( __FILE__, 'pluginprefix_callback_function_to_run' );
```
On uninstall, plugins can clean up themselves from the system such as any plugin options and/or settings specific to to the plugin, and/or other database entities such as tables 
```
register_uninstall_hook(__FILE__, 'pluginprefix_function_to_run');
```
 - Use Constants for plugin directory paths and save yourself extra coding time.

## 8. Security and Validation - Please keep in mind that your code may be running across hundreds, perhaps even millions, of websites, so security is of the utmost importance.
Make sure your plugin follows the following best practices:

- Check user capabilities - If your plugin allows users to submit data—be it on the Admin or the Public side—it should check for User Capabilities.
- Validate - Data validation is the process of analyzing the data against a predefined pattern (or patterns) with a definitive result: valid or invalid.
- Sanitize input - Securing input is the process of sanitizing (cleaning, filtering) input data.
- Sanitize output - Securing output is the process of escaping output data. Escaping means stripping out unwanted data, like malformed HTML or script tags.
- Nonces - Nonces are generated numbers used to verify origin and intent of requests for security purposes. Each nonce can only be used once. If your plugin allows users to submit data; be it on the Admin or the Public side; you have to make sure that the user is who they say they are.

## 9. Creating a custom post type gallery with custom content blocks and meta data

## 10. Publishing your plugin to the WordPress Plugin Directory. 

A plugins readme.txt file is the key file to making your plugin publish correctly. The readme.txt creates the description of your plugin and is what determines the version of your plugin that will be available to the public.

WordPress won’t accept plugins that do ‘nothing,’ are illegal, or encourage bad behavior. This includes black hat SEO spamming, content spinners, hate-plugins, and so on.

**SUBMISSION STEPS**
1. Sign up for an account on WordPress.org.
2. Submit your plugin for review.
3. After your plugin is manually reviewed, it will either be approved or you will be emailed and asked to provide more information and/or make corrections.
4. Once approved, you’ll be given access to a Subversion Repository where you’ll store your plugin.
5. Shortly after you upload your plugin (and a readme file!) to that repository, it will be automatically displayed in the plugins browser.

**THINGS TO LOOK OUT FOR:**
- Not including a readme.txt file when acting as a service
- Not testing the plugin with WP_DEBUG
- Including custom versions of packaged JavaScript libraries
- Calling external files unnecessarily
- “Powered By” links
- Phoning home
- Semantic Versioning

**SVN Access:**
Login is username (not email) and password

There are four directories created by default in all SVN repositories:

/assets/ - Use assets for screenshots, plugin headers, and plugin icons.
/branches/ - The /branches directory is a place that you can use to store branches of the plugin. Perhaps versions that are in development, or test code, etc. 
/tags/ - The /tags directory is where you can put versions of the plugin at some specific point in time. e.g. /tags/1.0
/trunk/ - The /trunk directory is where your plugin code should live.

**Console Steps:**

SVN Checkout & Add Code:
```
mkdir my-local-dir
svn co https://plugins.svn.wordpress.org/your-plugin-name my-local-dir
cd my-local-dir/
svn add trunk/*
svn ci -m "first commit" --username your_username --password your_password
```
SVN Update Existing Code:
```
cd my-local-dir/
svn up
svn stat (see whats changed)
svn diff (see whats changed in code)
svn ci -m "update msg" --username your_username --password your_password
```
--------

**Helpful Links:**

Gutenberg Migration Guide - https://github.com/danielbachhuber/gutenberg-migration-guide

Composer - https://getcomposer.org/download/

WordPress Code Reference - https://developer.wordpress.org/reference/

Dashicons for Custom Post Types - https://developer.wordpress.org/resource/dashicons/

PHP Standards Recommendations - https://www.php-fig.org/psr/

ReadMe.txt Validator - https://wordpress.org/plugins/developers/readme-validator/

How To Use SVN - https://developer.wordpress.org/plugins/wordpress-org/how-to-use-subversion/

WordPress Roles & Capabilities - https://codex.wordpress.org/Roles_and_Capabilities

WordPress Licensing & the GPL - https://developer.wordpress.org/themes/getting-started/wordpress-licensing-the-gpl/

Detailed Plugin Guidelines - https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/

Object Oriented Programming Tutorial - https://code.tutsplus.com/tutorials/two-ways-to-develop-wordpress-plugins-object-oriented-programming--wp-27716

WordPress Plugin Security - https://developer.wordpress.org/plugins/security/

WordPress Plugin Boilerplate - http://wppb.io/

Admin Page Class - https://github.com/bainternet/Admin-Page-Class/

WordPress Debug Bar - https://wpadverts.com/blog/wordpress-debug-bar-plugin-blackbox/

Generate WP - https://generatewp.com
