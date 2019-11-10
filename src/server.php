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

require __DIR__ . '/functions.php';

echo file_get_contents(__DIR__ . '/banner.txt');

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
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
; php-server configuration file - https://github.com/natanfelles/php-server ;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
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
$config = require __DIR__ . '/server_config.php';

if (in_array('-h', $argv) || in_array('--help', $argv))
{
	echo "Fine tuning on the PHP Built-in web server\n";
	echo "Created by Natan Felles <natanfelles@gmail.com>\n";
	echo "Checking for the latest release...";

	$info = @file_get_contents(
		'https://api.github.com/repos/natanfelles/php-server/releases/latest',
		false,
		stream_context_create([
			'http' => [
				'user_agent' => 'php-server ' . $config['server']['PHPSERVER_VERSION'],
				'timeout'    => 15
			]
		])
	);

	echo "\r";

	if ($info && $info = json_decode($info))
	{
		$version = ltrim($info->tag_name, 'v');

		if ($version == $config['server']['PHPSERVER_VERSION'])
		{
			echo "The latest php-server version ({$version}) is running.\n";
		}
		elseif ($version < $config['server']['PHPSERVER_VERSION'])
		{
			echo "A new release is available. Version {$version}\n";
		}
	}
	else
	{
		echo "The current php-server version is {$config['server']['PHPSERVER_VERSION']}\n";
	}

	echo "Check the repository at https://github.com/natanfelles/php-server\n";
	exit;
}

$ini = '';
foreach ($config['ini'] as $key => $value)
{
	if(is_array($value))
	{
		foreach ($value as $val)
		{
			$ini .= " -d {$key}={$val}";
		}
	}
	else
	{
		$ini .= " -d {$key}={$value}";
	}
}

$options = getopt(null, ['php:', 'host:', 'port:', 'root:']);

$php    = isset($options['php']) ? $options['php'] : $config['php'];
$host   = isset($options['host']) ? $options['host'] : $config['host'];
$port   = isset($options['port']) ? $options['port'] : $config['port'];
$root   = isset($options['root']) ? $options['root'] : $config['root'] ;
$router = __DIR__ . '/server_router.php';

echo $function_color('Version:') . " {$config['server']['PHPSERVER_VERSION']}\n";
echo $function_color('PHP Binary:') . " {$php}\n";
echo $function_color('Document Root:') . " {$root}\n";
echo $function_color('Web Address:') . " http://{$host}:{$port}\n";
echo $function_color('Date:') . ' ' . date('r') . "\n\n";
$root = strtr($root,[' ' => '\ ']);
$router = strtr($router,[' ' => '\ ']);
passthru("{$php}{$ini} -S {$host}:{$port} -t {$root} {$router}");
