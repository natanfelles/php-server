<?php

if (is_file($custom_config_file = getcwd() . '/php-server.ini'))
{
	$custom_config = parse_ini_file($custom_config_file, true, INI_SCANNER_TYPED);
}

$default_config = [
   'php'             => PHP_BINARY,
   'host'            => 'localhost',
   'port'            => '8080',
   'root'            => getcwd(),
   'autoindex'       => true,
   'index'           => 'index.html index.php',
   'error_reporting' => E_ALL,
   'ini'             => [
	   'display_errors'         => 1,
	   'display_startup_errors' => 1,
	   'max_execution_time'     => 360,
   ],
   'server'          => [
	   'PHPSERVER_VERSION' => '2.11',
   ],
];

if (isset($custom_config['root']))
{
	$custom_config['root'] = realpath($custom_config['root']);
}

return isset($custom_config) ? array_replace_recursive($default_config, $custom_config) : $default_config;
