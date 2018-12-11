<?
function load_path($rut)
{
	$ruta = "./".$rut."/";
	if(is_dir($ruta))
	{
		if($dh = scandir($ruta))
		{
			foreach($dh as $file)
			{
				if(is_dir($ruta . $file) && $file!="." && $file!="..")
				{
					if(file_exists($ruta.$file."/__init__.php"))
					{
						$plugin_path = $ruta.$file."/";
						try_to_load($plugin_path, "__init__.php");
					}
				}
			}
		}
	}
	else
	{
		echo "Error: No existe el directorio de plugins";
	}
}

$GLOBALS['LOADER_ERROR'] = array();

function load_core()
{
	load_path("core");
}

function load_plugins()
{
	load_path("plugins");
}

function try_to_load($plugin_path, $file)
{
	try
	{
		require_once($plugin_path.$file);
	}
	catch(Exception $e)
	{
		return;
	}
}

//GESTIÓN DE ERRORES DURANTE LA CARGA
function handle_error($code, $message, $file, $line, $context)
{
	$code = $code & error_reporting();
	$errors = array(
		E_ERROR				=> 'E_ERROR',
		E_WARNING			=> 'E_WARNING',
		E_PARSE				=> 'E_PARSE',
		E_NOTICE			=> 'E_NOTICE',
		E_CORE_ERROR		=> 'E_CORE_ERROR',
		E_CORE_WARNING		=> 'E_CORE_WARNING',
		E_COMPILE_ERROR		=> 'E_COMPILE_ERROR',
		E_COMPILE_WARNING	=> 'E_COMPILE_WARNING',
		E_USER_ERROR		=> 'E_USER_ERROR',
		E_USER_WARNING		=> 'E_USER_WARNING',
		E_USER_NOTICE		=> 'E_USER_NOTICE',
		E_STRICT			=> 'E_STRICT',
		E_DEPRECATED		=> 'E_DEPRECATED',
	);
	if (array_key_exists($code, $errors)) {
		$errname = $errors[$code];
	}
	else {
		$errname = $code;
	}
	$error = "Error handler caught $errname with message \"" . $message . '" at ' . $file . ' line ' . $line;
	array_push($GLOBALS['LOADER_ERROR'], $error);
	return;
}
set_error_handler('handle_error');

function report_load_error()
{
	if(sizeof($GLOBALS['LOADER_ERROR']) == 0)
	{
		return;
	}

	$toprint = "<script>debug('<ul>";
	foreach($GLOBALS['LOADER_ERROR'] as $err)
	{
		$toprint = $toprint."<li>".$err."</li>";
	}
	$toprint = $toprint."</ul>');</script>";

	echo $toprint;
}
?>
