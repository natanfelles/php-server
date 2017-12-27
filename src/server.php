#!/usr/bin/env php
<?php
/**
 * PHP Server
 *
 * Run the PHP Built-in web server
 *
 * @author Natan Felles <natanfelles@gmail.com>
 * @link   https://github.com/natanfelles/php-server
 */

// Create a new php-server config file
if (in_array('new', $argv))
{
	if (is_file($file = getcwd() . '/php-server.ini'))
	{
		echo "The php-server.ini file already exists.\n";
	}
	else
	{
		$handle  = fopen($file, 'w+');
		$ini     = [
			'post_max_size'       => ini_get('post_max_size'),
			'upload_max_filesize' => ini_get('upload_max_filesize'),
		];
		$content = <<<EOD
php = PHP_BINARY
host = localhost
port = 8080
root = ./
autoindex = true
index = index.html index.php
error_reporting = E_ALL

[ini]
display_errors = 1
display_startup_errors = 1
max_execution_time = 30
post_max_size = {$ini['post_max_size']}
upload_max_filesize = {$ini['upload_max_filesize']}

[server]
ENVIRONMENT = development

EOD;
		fwrite($handle, $content);
		fclose($handle);
		echo "The php-server.ini file was created.\n";
	}
	exit;
}

/**
 * @var array Server config
 */
$config = require_once 'server_config.php';

$ini = '';
foreach ($config['ini'] as $key => $value)
{
	$ini .= " -d {$key}={$value}";
}

$options = getopt(null, ['php:', 'host:', 'port:', 'root:']);

$php    = isset($options['php']) ? $options['php'] : $config['php'];
$host   = isset($options['host']) ? $options['host'] : $config['host'];
$port   = isset($options['port']) ? $options['port'] : $config['port'];
$root   = isset($options['root']) ? $options['root'] : $config['root'] ;
$router = __DIR__ . '/server_router.php';

echo "PHP Server Version 2.4\n";
echo "PHP: {$php}\n";
echo "Web Address: http://{$host}:{$port}\n";
echo "Document Root: {$root}\n";

passthru("{$php}{$ini} -S {$host}:{$port} -t {$root} {$router}");
