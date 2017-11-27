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
		h1 {
			line-height: 3rem;
			margin: 0 0 1.5rem;
			overflow: hidden;
			text-rendering: optimizeLegibility;
			color: #793862;
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
	<h1><?= $title ?></h1>
	<p>PHP <?= phpversion() ?> Built-in web server - <a href="/?php-server=phpinfo">info</a> <span class="date"><?= date('r') ?></span></p>
	<?php require_once __DIR__ . "/{$page}.php"; ?>
</body>
</html>
