<?php
/**
 * PHP Server
 * 
 * Run the PHP Built-in web server
 *
 * @author Natan Felles <natanfelles@gmail.com>
 * @link https://github.com/natanfelles/php-server
 */

/**
 * @var array Server config
 */
$config = require_once 'server_config.php';

$options = getopt(null, ['php:', 'host:', 'port:', 'root:']);

$php  = $options['php'] ?? $config['php'];
$host = $options['host'] ?? $config['host'];
$port = $options['port'] ?? $config['port'];
$root = $options['root'] ?? $config['root'] ;

passthru("{$php} -S {$host}:{$port} -t {$root} server_router.php");
