<?php
/**
 * Convert a number to computer size
 *
 * @param integer $size
 *
 * @return string
 */
$function_size_conversion = function ($size) {
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
	return number_format($size / pow(1024, $power), $power ? 2 : 0, '.', ',') . ' ' . $units[$power];
};

/**
 * Clean all server variables
 */
$function_clean_vars = function () {
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
};

/**
 * Get an order by link
 *
 * @param string $order_by
 * @param string $name
 *
 * @return string
 */
$function_order_link = function ($order_by, $name) {
	$href  = $GLOBALS['relative_path'] . '?order_by=' . $order_by;
	$title = 'Order by ' . $name;
	$class = '';
	$icon  = ' <span></span>';
	if (isset($_GET['order_by']) && $_GET['order_by'] === $order_by || ! isset($_GET['order_by']) && $order_by === 'filename')
	{
		$class = ' class="active asc"';
		$icon  = ' <span>&blacktriangle;</span>';
		if (! isset($_GET['order']))
		{
			$href  .= '&order=asc';
			$title .= ' asc';
			$icon   = ' <span>&blacktriangledown;</span>';
			$class  = ' class="active desc"';
		}
	}

	return "<th{$class} title=\"{$title}\"><a href=\"{$href}\">{$name}{$icon}</a></th>\n";
};

$function_path_href = function ($SplFileInfo) {
	$q = '';
	if ($SplFileInfo->isDir())
	{
		if (isset($_GET['order_by']) && in_array($_GET['order_by'], [
					  'type',
					  'filename',
					  'size',
					  'owner',
					  'group',
					  'perms',
					  'mTime',
				  ]))
		{
			$q .= '?order_by=' . $_GET['order_by'];
			if (isset($_GET['order']) && in_array($_GET['order'], ['asc', 'desc']))
			{
				$q .= '&order=' . $_GET['order'];
			}
		}
	}

	return $GLOBALS['relative_path'] . '/' . $SplFileInfo->getFilename() . $q;
};

$function_order_paths = function ($paths) {
	$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'filename';
	$types    = [];
	$keys     = [];
	$i        = 0;
	foreach ($paths as $path)
	{
		$keys[$path['type']][$path[$order_by]] = $path;
		sort($keys[$path['type']]);
	}

	foreach ($keys as $k => $v)
	{
		//$types[$k];
	}

	var_dump($keys);
	exit;
	return $paths;
};
