<?
if(isset($_POST['orden']))
{
  $orden = $_POST['orden'];
}

if($content == "login")
{
  if(isset($_POST['login']))
  {
    $orden = "login";
	  require_once($plugin_path.'functions.php');
  }
  else
  {
    require_once($plugin_path.'login.php');
  }
}
else if($content == "logout")
{
  $orden = "logout";
  require_once($plugin_path.'functions.php');
}
else if($content == "register")
{
  $orden = "register";
  require_once($plugin_path.'functions.php');
}
?>
