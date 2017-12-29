<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?= $title ?></title>
	<link rel="shortcut icon" href="data:image/png;base64,<?= base64_encode(file_get_contents(__DIR__ . '/favicon.png')) ?>">
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
		a {
			color: #369;
		}
		a:hover {
			color: #ae508d;
			border-color: #ae508d;
			outline: 0;
		}
		.version {
			text-align: right;
		}
		.version a {
			text-decoration: none;
			color: #333;
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
			text-indent: .5rem;
		}
		th {
			border-color: #c4c9df;
			background: #c4c9df;
			border-right: 1px dotted #ccc;
		}
		th a {
			color: #333;
			text-decoration: none;
			padding: .25rem 0;
			width: 100%;
			display: block;
		}
		.version a:hover,
		th a:hover {
			color: #793862;
		}
		th a span {
			float: right;
			min-width: 20px;
			display: block;
		}
		.date {
			float: right;
			font-size: .875rem;
		}
		.error {
			background: #f4dfdf;
			border: 1px solid #eabfbf;
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

	<p class="version">
		<a href="https://github.com/natanfelles/php-server/releases/tag/v<?= $_SERVER['PHPSERVER_VERSION'] ?>" target="_blank"><?= 'php-server v' . $_SERVER['PHPSERVER_VERSION'] ?></a>
	</p>

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
