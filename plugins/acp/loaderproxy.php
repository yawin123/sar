<?
  if($GLOBALS['RANGE'] != 101){return;}

  switch($content)
  {
    case 'saveConfig':
      if(!isset($_POST['value'])){return;}
      $value = $_POST['value'];
      require_once($plugin_path.'functions.php');

    case 'saveTos':
      if(!isset($_POST['value'])){return;}
      $value = $_POST['value'];
      require_once($plugin_path.'functions.php');

    default:
      require_once($plugin_path.'functions.php');
  }
?>
