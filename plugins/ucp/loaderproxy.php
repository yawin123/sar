<?
  if($GLOBALS['USER_ID'] < 1)
    return;

  switch($content)
  {
    /* Son case de guardar cosas, por eso el post
    case 'saveConfig':
      if(!isset($_POST['value'])){return;}
      $value = $_POST['value'];
      require_once($plugin_path.'functions.php');

    case 'saveTos':
      if(!isset($_POST['value'])){return;}
      $value = $_POST['value'];
      require_once($plugin_path.'functions.php');
    */
    default:
      require_once($plugin_path.'functions.php');
  }
?>
