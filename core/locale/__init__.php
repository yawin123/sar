<? //Si existe un parámetro de idioma seleccionado
		if(isset($_SESSION['lang']))
		{
			//Se establece como idioma de la web
				$GLOBALS['LANG'] = $_SESSION['lang'];
		}
		else //En caso contrario
		{
			//Se establece como parámetro de idioma seleccionado
			//el idioma por defecto
				$_SESSION['lang'] = $GLOBALS['LANG'];
		}

		$GLOBALS['SET_LANG'] = function($lang)
		{
			$_SESSION['lang'] = $lang;
			$GLOBALS['LANG'] = $lang;
		}
?>
