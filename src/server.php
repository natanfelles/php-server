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

$php    = $options['php'] ?? $config['php'];
$host   = $options['host'] ?? $config['host'];
$port   = $options['port'] ?? $config['port'];
$root   = $options['root'] ?? $config['root'] ;
$router = __DIR__ . '/server_router.php';

echo "PHP Server Version 2.1\n";
echo "PHP: {$php}\n";
echo "Web Address: http://{$host}:{$port}\n";
echo "Document Root: {$root}\n";

passthru("{$php}{$ini} -S {$host}:{$port} -t {$root} {$router}");
