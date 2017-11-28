<?php
/**
 * PHP Server
 *
 * You probably will not have to change much here
 *
 * @author Natan Felles <natanfelles@gmail.com>
 * @link   https://github.com/natanfelles/php-server
 */

// Load our functions...
require_once 'functions.php';

/**
 * @var array Server config
 */
$config = require_once 'server_config.php';

error_reporting($config['error_reporting']);

foreach ($config['ini'] as $key => $value)
{
	/**
	 * @todo The ini_set() only works with PHP_INI_ALL
	 *       For example: upload_max_filesize can not be set here.
	 * @see http://php.net/manual/en/function.ini-set.php
	 * @see http://php.net/manual/en/ini.list.php
	 * @see http://php.net/manual/en/configuration.changes.modes.php
	 */
	ini_set($key, $value);
}

foreach ($config['server'] as $key => $value)
{
	$_SERVER[$key] = $value;
}

if (isset($_GET['php-server']) && $_GET['php-server'] === 'phpinfo')
{
	$function_clean_vars();
	phpinfo();

	return true;
}

/**
 * Relative and Absolute directory path
 */
$relative_path = preg_replace('/\/$/', '', urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
$absolute_path = $config['root'] . $relative_path;

// If is not a dir get the file content
if (! is_dir($absolute_path) && is_file($absolute_path))
{
	$function_clean_vars();

	return false;
}

// Run the index file
$indexes = explode(' ', trim($config['index']));

foreach ($indexes as $index)
{
	// Check if has an index inside the current dir
	if (is_file($absolute_path . '/' . $index))
	{
		$_SERVER['PHPSERVER_INDEX'] = $absolute_path . '/' . $index;
		$function_clean_vars();

		require $_SERVER['PHPSERVER_INDEX'];

		return true;
	}
}

// Rewrite - Check if has an index in the document root
foreach ($indexes as $index)
{
	if (is_file($config['root'] . '/' . $index))
	{
		$_SERVER['PHPSERVER_INDEX'] = $config['root'] . '/' . $index;
		$function_clean_vars();

		require $_SERVER['PHPSERVER_INDEX'];

		return true;
	}
}

// If has not any index and the called file or dir does not exist
// means that we need a Error 404
if (! file_exists($absolute_path))
{
	http_response_code(404);
	$title = 'Error 404';
	$page  = 'error-404';

	require_once __DIR__ . '/pages/_template.php';

	$function_clean_vars();

	return true;
}

// If autoindex is disabled the paths list will not be showed
if ((bool)$config['autoindex'] === false)
{
	$function_clean_vars();

	return true;
}

// File System iterator
$filesystem = iterator_to_array(new FilesystemIterator($absolute_path));

// All child paths
$paths = [];

foreach ($filesystem as $pathname => $SplFileInfo)
{
	if ($SplFileInfo->isDir())
	{
		$fi = new FilesystemIterator($SplFileInfo->getRealPath());
	}

	$paths[] = [
		'type'     => $SplFileInfo->getType(),
		'realPath' => $SplFileInfo->getRealPath(),
		'filename' => $SplFileInfo->getFilename(),
		'isDir'    => $SplFileInfo->isDir(),
		'size'     => $SplFileInfo->isDir() ? iterator_count($fi) . ' items' : $function_size_conversion($SplFileInfo->getSize()),
		'owner'    => $SplFileInfo->getOwner(),
		'group'    => $SplFileInfo->getGroup(),
		'perms'    => substr(sprintf('%o', $SplFileInfo->getPerms()), -4),
		'mTime'    => date('Y-m-d H:i:s', $SplFileInfo->getMTime()),
	];
}
sort($paths);

$title = 'Index of ' . (empty($relative_path) ? '/' : $relative_path);

$page = 'default';

require_once __DIR__ . '/pages/_template.php';

$function_clean_vars();
