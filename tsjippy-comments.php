<?php

namespace TSJIPPY\COMMENTS;

/**
 * Plugin Name:          Tsjippy Comments
 * Description:          This plugin allows you to define the e-mails sent when someone adds a comment to the website.<br>You can also define on which post types comments are allowed<br>You can turn on or off comments also on a per page level.
 * Version:              11.0.0
 * Author:               Ewald Harmsen
 * AuthorURI:            harmseninnigeria.nl
 * Requires at least:    6.3
 * Requires PHP:         8.3
 * Tested up to:         7.0
 * Plugin URI:           https://github.com/Tsjippy/comments/
 * Tested:               7.0
 * TextDomain:           tsjippy
 * Requires Plugins:    
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @author Ewald Harmsen
 */
if (! defined('ABSPATH')) {
    exit;
}

// Load shared code
if(file_exists(__DIR__  . '/shared-functionality/loader.php')){
    require_once(__DIR__  . '/shared-functionality/loader.php');
}

// Define constants
define(__NAMESPACE__ . '\PLUGIN', plugin_basename(__FILE__));
define(__NAMESPACE__ . '\PLUGINPATH', __DIR__ . '/');
define(__NAMESPACE__ . '\PLUGINVERSION', get_plugin_data(__FILE__, false, false)['Version']);
define(__NAMESPACE__ . '\PLUGINSLUG', str_replace('tsjippy-', '', basename(__FILE__, '.php')));
define(__NAMESPACE__ . '\SETTINGS', get_option('tsjippy_comments_settings', []));

// run right before activation
register_activation_hook(__FILE__, function () {
    if(file_exists(__DIR__  . '/shared-functionality/loader.php')){
        require_once(__DIR__  . '/shared-functionality/loader.php');
    }

    if(function_exists('TSJIPPY\activate')){
        \TSJIPPY\activate();
    }
});