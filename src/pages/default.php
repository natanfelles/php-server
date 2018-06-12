<?php if(! empty($relative_path)): ?>
	<?php
	$rdirs = explode('/', $relative_path);
	array_pop($rdirs);
	$rdirs = implode('/', $rdirs);
	?>
<p class="half"><a href="<?= ! empty($rdirs) ? $rdirs : '/' ?>">Parent dir</a></p>
<?php endif ?>
<?php if(! empty($paths)): ?>
<p class="half" style="float: right"><input type="text" id="search" placeholder="Search..."></p>
<?php endif ?>
<table>
	<thead>
		<tr>
			<?= $function_order_link('filename', 'Name') ?>
			<?= $function_order_link('size', 'Size') ?>
			<?= $function_order_link('owner', 'Owner') ?>
			<?= $function_order_link('group', 'Group') ?>
			<?= $function_order_link('perms', 'Permissions') ?>
			<?= $function_order_link('mTime', 'Modified') ?>
		</tr>
	</thead>
	<tbody>
<?php if(empty($paths)): ?>
		<tr>
			<td colspan="6">This directory is empty.</td>
		</tr>
<?php endif ?>
<?php
$posix = function_exists('posix_getpwuid') && function_exists('posix_getgrgid');
?>
<?php foreach($paths as $path): ?>
<?php
	$user = $group = '';
	if ($posix)
	{
		$user = posix_getpwuid($path['owner']);
		$user = $user['name'] . ($user['gecos'] ? ' - '. $user['gecos'] : '');

		$group = posix_getgrgid($path['group'])['name'];
	}
?>
		<tr>
			<td>
				<span><?= $path['type'] ?></span>
				<a href="<?= $path['href'] ?>"><?= $path['filename'] ?></a>
			</td>
			<td><?= $path['size'] ?></td>
			<td title="<?= $user ?>"><?= $path['owner'] ?></td>
			<td title="<?= $group ?>"><?= $path['group'] ?></td>
			<td><?= $path['perms'] ?></td>
			<td><?= $path['mTime'] ?></td>
		</tr>
<?php endforeach ?>
	</tbody>
</table>
