<?php
$contents = file_get_contents($absolute_path);
$contents = substr($absolute_path, -3) === 'php'
	? highlight_string($contents, true)
	: htmlentities($contents);
$function_parent_dir($relative_path);
?>
<div class="file-code"><?= $contents ?></div>
