<?
	if($GLOBALS['USER_ID'] > 0)
	{
		$GLOBALS['UCP_PLUGIN'] = $plugin_path;
	    $GLOBALS['INDEX_CONTENT']->addStyleSheet($plugin_path."style.css");
	    $GLOBALS['INDEX_CONTENT']->addPage("ucp", $plugin_path."ucp.php", "Usuario"); //La barra superior
	    $GLOBALS['UCP_NAV'] = new Menu("ucp", "");
	    $GLOBALS['UCP_NAV']->addPage("ucp", $plugin_path."ucp.php", "Perfil y Mensajes"); //La barra inferior
	}
?>
