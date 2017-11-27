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

if (isset($_GET['php-server']) && $_GET['php-server'] === 'phpinfo')
{
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
	return false;
}

// All the child pathnames
$pathnames = glob(DIR . '/*');

// Run the index file
$indexes = explode(' ', trim($config['index']));

foreach ($indexes as $index)
{
	// Check if has an index inside the current dir
	if (in_array($index = DIR . '/' . $index, $pathnames))
	{
		if (is_file($index))
		{
			require_once $index;

			return true;
		}
	}
}

// Rewrite - Check if has an index in the document root
foreach ($indexes as $index)
{
	if (is_file($index = $config['root'] . '/' . $index))
	{
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

	return true;
}

// If autoindex is disabled the paths list will not be showed
if ((bool)$config['autoindex'] === false)
{
	return true;
}

// All child paths
$paths = [];

foreach ($pathnames as $pathname)
{
	$file    = new SplFileInfo($pathname);
	$paths[] = [
		'type'     => $file->getType(),
		'realPath' => $file->getRealPath(),
		'filename' => $file->getFilename(),
		'isDir'    => $file->isDir(),
		'size'     => $file->getSize(),
		'owner'    => $file->getOwner(),
		'group'    => $file->getGroup(),
		'perms'    => $file->getPerms(),
		'mTime'    => $file->getMTime(),
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

$title = 'Index of ' . (empty(RDIR) ? '/' : RDIR);

$page = 'default';

require_once __DIR__ . '/pages/_template.php';
