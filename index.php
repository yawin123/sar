<?
	session_start();
	require_once('config.php');

	error_reporting($GLOBALS['ERROR_REPORTING_LEVEL']);
	ini_set('display_errors', '1');

	require_once('pluginloader.php');
	load_core();
	load_plugins();

	if(isset($GLOBALS['MAIN']))
	{
		require_once($GLOBALS['MAIN']);
	}
	else
	{
		echo "MAIN required";
	}
?>
