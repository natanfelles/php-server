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
		p {
			margin: 10px 0;
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
			padding: 2px ;
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
		td span {
			color: gray;
			min-width: 30px;
			display: inline-block;
			text-indent: 0;
		}
		pre, code,
		.file-code .lines span  {
			margin: 0;
			font-family: "Fira Mono", "Source Code Pro", monospace;
			font-weight: 400;
			font-size: 1rem;
			line-height: 1.5rem;
			-moz-tab-size: 4;
			-o-tab-size: 4;
			tab-size: 4;
		}
		.file-code {
			border: 1px gray solid;
			padding: 0;
			background: #fff;
			overflow-x: auto;
			width: 100%;
			margin: 0 auto;
		}
		.file-code > * {
			float: left;
		}
		.file-code .lines {
			background: #e6e6e6;
			border-right: 1px gray solid;
			padding: 0 5px;
			min-width: 20px;
		}
		.file-code .lines span {
			display: block;
			line-height: 1.595rem;
		}
		.file-code .code {
			width: 200px;
			padding-left: 5px;
		}
		.file-code .code span {
			white-space: pre;
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
		#search {
			float: right;
			background: #fff;
			border: 1px solid #ccc;
			padding: .125rem .5rem;
			box-shadow: inset 0 1px 2px rgba(0, 0, 0, .2);
			color: #333;
		}
		#search:focus {
			border-color: #369;
		}
		.half {
			width: 50%;
			float: left;
		}
	</style>
</head>
<body>
<?php
if (strpos($title, 'Index of ') === 0 || strpos($title, 'File ') === 0)
{
	$dirs = explode('/', $title);
	$pre = $dirs[0];
	unset($dirs[0]);

	$query = isset($_GET['php-server']) ? '?php-server=' . $_GET['php-server'] : '';

	$dirs        = array_values($dirs);
	$breadcrumbs = '';
	for ($i = 0; $i < count($dirs); $i++)
	{
		$dir = '';
		for ($s = 0; $s <= $i; $s++)
		{
			$dir .= '/' . $dirs[$s];
		}
		$dir .= $query;
		$breadcrumbs .= "<a href=\"{$dir}\" class=\"uri\" data-id=\"{$i}\">"
					 . ($i === 0 ? '' : '/')
					 . "{$dirs[$i]}</a>";
	}

	$title = $pre . ' <a href="/" class="uri" data-id="-1">/</a>' . $breadcrumbs;
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
		var uris = document.getElementsByClassName('uri');

		for (var i = 0; i < uris.length; i++)
		{
			uris[i].addEventListener('mouseover', function () {
				for (var i = 0; i < uris.length; i++)
				{
					if (uris[i].getAttribute('data-id') <= this.getAttribute('data-id'))
					{
						uris[i].classList.add('active');
					}
				}
			}, true);

			uris[i].addEventListener('mouseout', function () {
				for (var i = 0; i < uris.length; i++)
				{
					uris[i].classList.remove('active');
				}
			}, true);
		}

		var search = document.getElementById('search');

		if (search) {
			search.value = '';
			search.focus();
			search.onkeyup = function () {
				var filter = this.value.toUpperCase();
				var table = document.getElementsByTagName('table')[0];
				var tr = table.getElementsByTagName('tr');

				for (var i = 1; i < tr.length; i++) {
					if (tr[i].innerText.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = '';
					} else {
						tr[i].style.display = 'none';
					}
				}
			};

			window.addEventListener('keydown', function (e) {
				if (e.keyCode === 114 || (e.ctrlKey && e.keyCode === 70)) {
					e.preventDefault();
					search.focus();
				}
				return false;
			});
		}
	</script>
</body>
</html>
