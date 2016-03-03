<?php
// checks class
class Checks
{
    public static function phpCheck()
    {
    	$version = explode(".", phpversion());
        return $version[0] >= 5 && $version[1] >= 2;
    }
    public static function mcryptCheck()
    {
        return function_exists("mcrypt_encrypt");
    }
    public static function curlCheck()
    {
        return function_exists("curl_init");
    }
	public static function mssqlCheck()
	{
		return function_exists("mssql_connect");
	}
    public static function sqlsrvCheck()
    {
        return function_exists("sqlsrv_connect");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Web Chaos Machine - MuOnline - Testing Facility</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<style type="text/css">
		body {
			padding-top: 70px;
			margin-bottom: 60px;
		}
		.container {
			padding: 0 15px;
		}
		.container .text-muted {
			margin: 20px 0;
		}
		.footer {
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 60px;
			background-color: #f5f5f5;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php $_SERVER["PHP_SELF"]; ?>">Web Chaos Machine - MuOnline - Testing Facility</a>
			</div>
		</div>
	</nav>

	<div class="container">
		<table class="table table-bordered">
			<tr>
				<th class="col-md-4">Test</th>
				<th class="col-md-8">Result test</th>
			</tr>
			<tr class="<?php echo Checks::phpCheck() ? "success" : "danger"; ?>">
				<td>PHP Version</td>
				<td><?php echo Checks::phpCheck() ? "ok" : "required php 5.2+"; ?></td>
			</tr>
			<tr class="<?php echo Checks::mcryptCheck() ? "success" : "danger"; ?>">
				<td>MCrypt Extension</td>
				<td><?php echo Checks::mcryptCheck() ? "ok" : "extension not installed on php"; ?></td>
			</tr>
			<tr class="<?php echo Checks::curlCheck() ? "success" : "danger"; ?>">
				<td>CURL Extension</td>
				<td><?php echo Checks::curlCheck() ? "ok" : "extension not installed on php"; ?></td>
			</tr>
			<tr class="<?php echo Checks::mssqlCheck() || Checks::sqlsrvCheck() ? "success" : "danger"; ?>">
				<td>MsSQL || SqlSrv Extension</td>
				<td>
					MsSQL: <?php echo Checks::mssqlCheck() ? "ok" : "extension not installed on php"; ?><br>
					SqlSrv: <?php echo Checks::sqlsrvCheck() ? "ok" : "extension not installed on php"; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="text-right"><a class="btn btn-default" href="<?php $_SERVER["PHP_SELF"]; ?>" role="button">Refresh test</a></td>
			</tr>
		</table>
	</div>

	<footer class="footer">
		<div class="container">
			<p class="text-muted">Web Chaos Machine - MuOnline | <a href="http://webcm.daldegam.com.br" target="_blank">http://webcm.daldegam.com.br</a></p>
		</div>
	</footer>
</body>
</html>