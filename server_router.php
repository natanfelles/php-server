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

if (isset($_GET['phpinfo']) && $_GET['phpinfo'] === 'show')
{
	phpinfo();
	return true;
}

/**
 * Absolute directory path
 */
define('DIR', preg_replace('/\/$/', '', "{$config['root']}{$_SERVER['REQUEST_URI']}"));

// If is not a dir get the file content
if (! is_dir(DIR))
{
	return false;
}

/**
 * Relative directory path
 */
define('RDIR', preg_replace('/\/$/', '', $_SERVER['REQUEST_URI']));

// Run the index file
if ((! is_file(DIR) && ! is_dir(DIR) || RDIR === '') && file_exists($index = "{$config['root']}/{$config['index']}"))
{
	require_once $index;

	return true;
}

// If autoindex is disabled the filepaths list will not be showed
if ($config['autoindex'] === false)
{
	return true;
}

// All child filepaths
$filepaths = [];

foreach (glob(DIR . '/*') as $filename)
{
	$file        = new SplFileInfo($filename);
	$filepaths[] = [
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
sort($filepaths);

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
	<p>PHP <?= phpversion() ?> Built-in web server - <a href="/?phpinfo=show">info</a> <span class="date"><?= date('r') ?></span></p>
<?php if(! empty(RDIR)): ?>
	<p><a href="..">Parent dir</a></p>
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
<?php if(empty($filepaths)): ?>
			<tr>
				<td colspan="7">This directory is empty.</td>
			</tr>
<?php endif ?>
<?php foreach($filepaths as $file): ?>
			<tr>
				<td><?= $file['type'] ?></td>
				<td><a href="<?= RDIR . str_replace(DIR, '', $file['realPath'])  ?>"><?= $file['filename'] ?></a></td>
				<td><?= $file['isDir'] ? count(glob($file['realPath'] . '/*')) . ' itens' : size_conversion($file['size']) ?></td>
				<td><?= $file['owner'] ?></td>
				<td><?= $file['group'] ?></td>
				<td><?= substr(sprintf('%o', $file['perms']), -4) ?></td>
				<td><?= date('Y-m-d H:i:s', $file['mTime']) ?></td>
			</tr>
<?php endforeach ?>
		</tbody>
	</table>	
</body>
</html>
	