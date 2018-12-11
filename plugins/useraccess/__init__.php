<?
  if($GLOBALS['USER_ID'] < 1)
  {
    $GLOBALS['INDEX_CONTENT']->addStyleSheet($plugin_path."style.css");
    $GLOBALS['INDEX_CONTENT']->addPage("loginform", $plugin_path."login.php", "Login");
    $GLOBALS['INDEX_CONTENT']->addPage("registerform", $plugin_path."register.php", "Registro");
}
else
{
                  $onclick_script = "$.post('loaderproxy.php',{content:'logout'},";
  $onclick_script = $onclick_script."	 function(output)";
  $onclick_script = $onclick_script."  {";
  $onclick_script = $onclick_script."    location.reload();";
  $onclick_script = $onclick_script."  });";

  $GLOBALS['INDEX_CONTENT']->addContent(
    "",
    "Logout",
    "",
    $onclick_script,
    '');
}
?>
