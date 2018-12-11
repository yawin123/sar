<?
  if($GLOBALS['USER_ID'] > 0)
  {
    $GLOBALS['UCP_NAV']->addPage("avatar_gallery", '', "Avatares"); //Introducimos el enlace en el menú de usuarios
    $GLOBALS['INDEX_CONTENT']->addPage("avatar_gallery", $plugin_path."gallery.php"); //Damos de alta la galería en el core
  }
?>
