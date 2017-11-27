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
		h1, h1 a {
			line-height: 3rem;
			margin: 0 0 1.5rem;
			overflow: hidden;
			text-rendering: optimizeLegibility;
			color: #793862;
			text-decoration: none;
		}
		h1 a.active {
			color: #369;
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
		.error {
			background: #F4DFDF;
			border: 1px solid #EABFBF;
			padding: .75rem;
			margin: 1.5rem 0;
			overflow: hidden;
		}
	</style>
</head>
<body>
<?php
if (strpos($title, 'Index of ') === 0)
{
	$dirs = explode('/', $title);
	unset($dirs[0]);

	$dirs        = array_values($dirs);
	$breadcrumbs = '';
	for ($i = 0; $i < count($dirs); $i++)
	{
		$dir = '';
		for ($s = 0; $s <= $i; $s++)
		{
			$dir .= '/' . $dirs[$s];
		}
		$breadcrumbs .= "<a href=\"{$dir}\" class=\"uri\" data-id=\"{$i}\">"
					 . ($i === 0 ? '' : '/')
					 . "{$dirs[$i]}</a>";
	}

	$title = 'Index of <a href="/" class="uri" data-id="-1">/</a>' . $breadcrumbs;
}
?>
	<h1><?= $title ?></h1>
	<p>
		PHP <?= phpversion() ?> Built-in web server -
		<a href="/?php-server=phpinfo">info</a> <span class="date"><?= date('r') ?></span>
	</p>
	<?php require_once __DIR__ . "/{$page}.php"; ?>

	<script type="text/javascript">
		function addClass(el, className)
		{
			if (el.classList)
			{
				el.classList.add(className);
			}
			else
			{
				el.className += ' ' + className;
			}
		}

		function removeClass(el, className)
		{
			if (el.classList)
			{
				el.classList.remove(className);
			}
			else
			{
				el.className = el.className.replace(new RegExp('(^|\\b)' +
							   className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
			}
		}

		var uris = document.getElementsByClassName('uri');

		for (var i = 0; i < uris.length; i++)
		{
			uris[i].addEventListener('mouseover', function (argument) {
				for (var i = 0; i < uris.length; i++)
				{
					if (uris[i].getAttribute('data-id') <= this.getAttribute('data-id'))
					{
						addClass(uris[i], 'active');
					}
				}
			}, true);

			uris[i].addEventListener('mouseout', function (argument) {
				for (var i = 0; i < uris.length; i++)
				{
					removeClass(uris[i], 'active');
				}
			}, true);
		}
	</script>
</body>
</html>
