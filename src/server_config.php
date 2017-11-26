<?php
return [
   'php'             => PHP_BINARY,
   'host'            => 'localhost',
   'port'            => '8080',
   'root'            => __DIR__ . '/public',
   'autoindex'       => true,
   'index'           => 'index.php',
   'error_reporting' => E_ALL,
   'ini'             => [
	   'display_errors'         => 1,
	   'display_startup_errors' => 1,
	   'max_execution_time'     => 360,
   ],
];
