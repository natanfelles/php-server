<?php if(! empty($relative_path)): ?>
	<?php
	$rdirs = explode('/', $relative_path);
	array_pop($rdirs);
	$rdirs = implode('/', $rdirs);
	?>
<p><a href="<?= ! empty($rdirs) ? $rdirs : '/' ?>">Parent dir</a></p>
<?php endif ?>
<table>
	<thead>
		<tr>
			<?= $function_order_link('type', 'Type') ?>
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
			<td colspan="7">This directory is empty.</td>
		</tr>
<?php endif ?>
<?php foreach($paths as $path): ?>
		<tr>
			<td><?= $path['type'] ?></td>
			<td><a href="<?= $path['href'] ?>"><?= $path['filename'] ?></a></td>
			<td><?= $path['size'] ?></td>
			<td><?= $path['owner'] ?></td>
			<td><?= $path['group'] ?></td>
			<td><?= $path['perms'] ?></td>
			<td><?= $path['mTime'] ?></td>
		</tr>
<?php endforeach ?>
	</tbody>
</table>
