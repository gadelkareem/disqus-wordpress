<?php
/**
 * Helper script for setting up the WP command line environment
 */
error_reporting(E_ALL | E_STRICT);

function print_line() {
    print(call_user_func_array('sprintf', func_get_args())."\n");
}

define('DOING_AJAX', true);
define('WP_USE_THEMES', false);
if (isset($_ENV['WORDPRESS_PATH'])) {
    define('WORDPRESS_PATH', $_ENV['WORDPRESS_PATH']);
} else {
    if (substr($_SERVER['SCRIPT_FILENAME'], 0, 1) != '/') {
        $script_path = $_SERVER['PWD'] . $_SERVER['SCRIPT_FILENAME'];
    } else {
        $script_path = $_SERVER['SCRIPT_FILENAME'];
    }
    $tree = '';
    $paths = array();
    $chunks = explode('/', dirname($script_path));
    foreach ($chunks as $chunk) {
        if (!$chunk) continue;
        $tree = $tree.'/'.$chunk;
        array_push($paths, $tree);
    }
    $paths = array_reverse($paths);

    foreach ($paths as $path) {
        if (is_file($path.'/wp-config.php')) {
            define('WORDPRESS_PATH', $path);
            break;
        }
    }
}

if (!defined('WORDPRESS_PATH')) {
    print_line("Unable to determine wordpress path. Please set it using WORDPRESS_PATH.");
    die();
}

$_SERVER = array(
    "HTTP_HOST" => "disqus.com",
    "SCRIPT_NAME" => "",
    "PHP_SELF" => __FILE__,
    "SERVER_NAME" => "localhost",
    "REQUEST_URI" => "/",
    "REQUEST_METHOD" => "GET"
);
require_once(WORDPRESS_PATH . '/wp-config.php');
?>