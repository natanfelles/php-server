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
	return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
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
