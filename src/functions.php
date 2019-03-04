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
	array_multisort(array_keys($paths), SORT_NATURAL | SORT_FLAG_CASE, $paths);

	if (isset($_GET['order_by']) && in_array($_GET['order_by'], [
				  'filename',
				  'size',
				  'owner',
				  'group',
				  'perms',
				  'mTime',
			  ]))
	{
		usort($paths, function ($path1, $path2) {
			$order_by = $_GET['order_by'];

			if ($path1[$order_by] === $path2[$order_by])
			{
				return 0;
			}

			return $path1[$order_by] < $path2[$order_by] ? -1 : 1;
		});
	}

	if (isset($_GET['order']) && $_GET['order'] === 'asc')
	{
		$paths = array_reverse($paths);
	}

	$dirs  = [];
	$files = [];

	foreach ($paths as $path)
	{
		$path['mTime'] = date('Y-m-d H:i:s', $path['mTime']);
		$path['size']  = $path['isDir']
						 ? $path['size'] . ' item' . ($path['size'] < 2 ? '' : 's')
						 : $GLOBALS['function_size_conversion']($path['size']);

		if ($path['isDir'])
		{
			$dirs[] = $path;
		}
		else
		{
			$files[] = $path;
		}
	}

	return array_merge($dirs, $files);
};

$function_color = function ($text) {
	return "\033[0;32m$text\033[0m";
};

$function_parent_dir = function($relative_path) {
	if($relative_path):
		$query = isset($_GET['php-server']) ? '?php-server=' . $_GET['php-server'] : '';
		$relative_path = explode('/', $relative_path);
		array_pop($relative_path);
		$relative_path = implode('/', $relative_path);
		$relative_path .= $relative_path ? $query : '/' . $query;
	?>
<p class="half"><a href="<?= $relative_path ?>">Parent dir</a></p>
<?php endif;
};
