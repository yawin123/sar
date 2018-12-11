<?
  if($GLOBALS['RANGE'] != 101)
  {
    echo '<script>location.href="./";</script>';
    return;
  }

  $GLOBALS['ACP_NAV']->draw();
?>
<div class="container-double-fluid">
  <?
    $sect = 'inicio';
    if(isset($_GET['section']) && file_exists($GLOBALS['ACP_PLUGIN'].$_GET['section'].".php"))
    {
      $sect = $_GET['section'];
    }

    require_once($GLOBALS['ACP_PLUGIN'].$sect.".php");
  ?>
</div>
