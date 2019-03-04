<?php
$contents = file_get_contents($absolute_path);
if (substr($absolute_path, -3) === 'php') {
	$contents = highlight_string($contents, true);
	$contents = strtr($contents, [PHP_EOL => '', '&nbsp;' => ' ']);
	$contents = str_replace(['<br>', '<br />'],"\n",$contents);
} else {
	$contents =  '<pre>' . htmlentities($contents) . '</pre>';
}

$function_parent_dir($relative_path);
?>
<div class="file-code">
	<div class="lines"><?php
		foreach (explode("\n", $contents) as $line => $c) {
			echo '<span>' . ($line + 1) . '</span>';
		}
	?></div>
	<div class="code"><?= $contents ?></div>
</div>
