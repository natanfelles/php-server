<?php
/**
 * PHP Server
 *
 * You probably will not have to change much here
 *
 * @author Natan Felles <natanfelles@gmail.com>
 * @link https://github.com/natanfelles/php-server
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
define('RDIR', preg_replace('/\/$/', '', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)));

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

foreach ($indexes as $index) {
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
foreach ($indexes as $index) {
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
	echo 'Error 404';

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
	$file        = new SplFileInfo($pathname);
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?= $title ?></title>
	<style type="text/css">
		body {
			margin: 20px;
			font-family: "Fira Sans", "Source Sans Pro", Helvetica, Arial, sans-serif;
			font-weight: 400;
			font-size: 1rem;
			line-height: 1.5rem;
			color: #333;
			background: #f2f2f2;
		}
		h1 {
			line-height: 3rem;
			margin: 0 0 1.5rem;
			overflow: hidden;
			text-rendering: optimizeLegibility;
			color: #793862;
		}
		h1::after{
			display: table;
			width: 100%;
			content: " ";
			margin-top: -1px;
			border-bottom: 1px dotted;
		}
		table {
			border: 1px solid #ccc;
			border-collapse: collapse;
			text-align: left;
			width: 100%;
		}
		tr {
			border-bottom: 1px solid #ccc;
		}
		tr:nth-child(even) {
			background: #e6e6e6
		}
		tr:nth-child(odd) {
			background: #fff
		}
		tr:hover {
			background: #f5f5f5;
		}
		td, th {
			border-bottom: 1px solid #ccc;
			padding: .25rem .5rem;
		}
		th {
			border-color: #C4C9DF;
			background: #C4C9DF;
		}
		a {
			color: #369;
		}
		a:hover {
			color: #AE508D;
			border-color: #AE508D;
			outline: 0;
		}
		.date {
			float: right;
			font-size: .875rem;
		}
	</style>
</head>
<body>
	<h1><?= $title ?></h1>
	<p>PHP <?= phpversion() ?> Built-in web server - <a href="/?php-server=phpinfo">info</a> <span class="date"><?= date('r') ?></span></p>
<?php if(! empty(RDIR)): ?>
	<p><a href="<?= substr(RDIR, 0, strrpos(RDIR, DIRECTORY_SEPARATOR)) ?>">Parent dir</a></p>
<?php endif ?>
	<table>
		<thead>
			<tr>
				<th>Type</th>
				<th>Name</th>
				<th>Size</th>
				<th>Owner</th>
				<th>Group</th>
				<th>Permissions</th>
				<th>Modified</th>
			</tr>
		</thead>
		<tbody>
<?php if(empty($paths)): ?>
			<tr>
				<td colspan="7">This directory is empty.</td>
			</tr>
<?php endif ?>
<?php foreach($paths as $path): ?>
			<tr>
				<td><?= $path['type'] ?></td>
				<td><a href="<?= RDIR . '/' . $path['filename']  ?>"><?= $path['filename'] ?></a></td>
				<td><?= $path['isDir'] ? count(glob($path['realPath'] . '/*')) . ' itens' : size_conversion($path['size']) ?></td>
				<td><?= $path['owner'] ?></td>
				<td><?= $path['group'] ?></td>
				<td><?= substr(sprintf('%o', $path['perms']), -4) ?></td>
				<td><?= date('Y-m-d H:i:s', $path['mTime']) ?></td>
			</tr>
<?php endforeach ?>
		</tbody>
	</table>
</body>
</html>
