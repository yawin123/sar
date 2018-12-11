<?
	session_start();
	require_once("./config.php");

	error_reporting($GLOBALS['ERROR_REPORTING_LEVEL']);
	ini_set('display_errors', '1');

	if(isset($_POST['content']))
	{
		$content = $_POST['content'];
		$plugin = "index";
		$plugin_path = "core/".$plugin."/";

		if(isset($_POST['plugin']))
		{
			$plugin = $_POST['plugin'];
			$plugin_path = "plugins/".$plugin."/";
		}

		if(file_exists($plugin_path."loaderproxy.php"))
		{
			require_once("pluginloader.php");
			load_core();
			load_plugins();

			require_once($plugin_path."loaderproxy.php");
		}
	}
?>
