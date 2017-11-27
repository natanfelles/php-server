<?php if(! empty(RDIR)): ?>
	<?php
	$rdirs = explode('/', RDIR);
	array_pop($rdirs);
	$rdirs = implode('/', $rdirs);
	?>
<p><a href="<?= ! empty($rdirs) ? $rdirs : '/' ?>">Parent dir</a></p>
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
