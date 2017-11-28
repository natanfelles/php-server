<?php
/**
 * PHP Server
 *
 * You probably will not have to change much here
 *
 * @author Natan Felles <natanfelles@gmail.com>
 * @link   https://github.com/natanfelles/php-server
 */

/**
 * @var array Server config
 */
$config = require_once 'server_config.php';

error_reporting($config['error_reporting']);

foreach ($config['ini'] as $key => $value)
{
	ini_set($key, $value);
}

foreach ($config['server'] as $key => $value)
{
	$_SERVER[$key] = $value;
}

if (isset($_GET['php-server']) && $_GET['php-server'] === 'phpinfo')
{
	clean_vars();
	phpinfo();

	return true;
}

/**
 * Relative directory path
 */
define('RDIR', preg_replace('/\/$/', '', urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))));

/**
 * Absolute directory path
 */
define('DIR', $config['root'] . RDIR);

// If is not a dir get the file content
if (! is_dir(DIR) && is_file(DIR))
{
	clean_vars();

	return false;
}

// Run the index file
$indexes = explode(' ', trim($config['index']));

foreach ($indexes as $index)
{
	// Check if has an index inside the current dir
	if (is_file($index = DIR . '/' . $index))
	{
		clean_vars();

		require_once $index;

		return true;
	}
}

// Rewrite - Check if has an index in the document root
foreach ($indexes as $index)
{
	if (is_file($index = $config['root'] . '/' . $index))
	{
		clean_vars();

		require_once $index;

		return true;
	}
}

// If has not any index and the called file or dir does not exist
// means that we need a Error 404
if (! file_exists(DIR))
{
	http_response_code(404);
	$title = 'Error 404';
	$page  = 'error-404';

	require_once __DIR__ . '/pages/_template.php';

	clean_vars();

	return true;
}

// If autoindex is disabled the paths list will not be showed
if ((bool)$config['autoindex'] === false)
{
	clean_vars();

	return true;
}

// File System iterator
$filesystem = iterator_to_array(new FilesystemIterator(DIR));

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
		'size'     => $SplFileInfo->isDir() ? iterator_count($fi) . ' items' : size_conversion($SplFileInfo->getSize()),
		'owner'    => $SplFileInfo->getOwner(),
		'group'    => $SplFileInfo->getGroup(),
		'perms'    => substr(sprintf('%o', $SplFileInfo->getPerms()), -4),
		'mTime'    => date('Y-m-d H:i:s', $SplFileInfo->getMTime()),
	];
}
sort($paths);

/**
 * Convert a number to computer size
 *
 * @param integer $size
 *
 * @return string
 */
function size_conversion($size)
{
	$units = [
		'B',
		'KB',
		'MB',
		'GB',
		'TB',
		'PB',
		'EB',
		'ZB',
		'YB',
	];
	$power = $size > 0 ? floor(log($size, 1024)) : 0;
	return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

function clean_vars()
{
	foreach (array_keys($GLOBALS) as $var)
	{
		if (in_array($var, [
						   '_GET',
						   '_POST',
						   '_COOKIE',
						   '_FILES',
						   '_ENV',
						   '_REQUEST',
						   '_SERVER',
						   'GLOBALS',
					   ]))
		{
			continue;
		}
		unset($GLOBALS[$var]);
	}
}

$title = 'Index of ' . (empty(RDIR) ? '/' : RDIR);

$page = 'default';

require_once __DIR__ . '/pages/_template.php';

clean_vars();
