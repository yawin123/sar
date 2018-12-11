<?
  if(isset($_GET['sp']) && isset($GLOBALS['INDEX_CONTENT']->specialpages[$_GET['sp']]))
  {
    require_once($GLOBALS['INDEX_CONTENT']->specialpages[$_GET['sp']]);
    return;
  }
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <? if(isset($GLOBALS['INDEX_TITLE']))
    {?>
      <title><? echo $GLOBALS['INDEX_TITLE'];?></title>
    <?}
    if(isset($GLOBALS['INDEX_FAVICON']) && $GLOBALS['INDEX_FAVICON'] != "")
    {?>
      <link rel="shortcut icon" href="<? echo $GLOBALS['INDEX_FAVICON'];?>">
    <?}
    if(isset($GLOBALS['INDEX_FEED_RSS']) && $GLOBALS['INDEX_FEED_RSS'] != "")
    {?>
      <link rel="alternate" type="application/rss+xml" title="<? echo $GLOBALS['INDEX_TITLE'];?> Feed RSS"  href="<? echo $GLOBALS['INDEX_FEED_RSS'];?>">  
    <?}?>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./plugins/3rd_party/bootstrap-social-gh-pages/bootstrap-social.css">
    <?foreach($GLOBALS['INDEX_CONTENT']->stylesheets as $s)
    {?>
      <link rel="stylesheet" href="<? echo $s;?>">
    <?}?>

    <script src="./core/index/index.js"></script>
  </head>
  <body id="pagina" data-spy="scroll">
    <div id="debug-panel" class="panel panel-danger navbar-fixed-top <? if(isset($GLOBALS['SESION'])){?>hidden<?}?>">
      <div class="panel-heading">Debug <span title="Añadir subforo" class="debug-button glyphicon glyphicon-remove pull-right" OnClick="debug_hide();"></span></div>
      <div id="debug-box" class="panel-body">
        <? if(!isset($GLOBALS['SESION']))
        {
          echo '"Ooops, parece que ha habido algún problema. Vamos a poner a un equipo de monos adiestrados a trabajar en ello';
        }?>
      </div>
    </div>
    <?
      $GLOBALS['LAZY_LOAD']->load();
    ?>

    <? $GLOBALS['INDEX_CONTENT']->draw(); ?>

    <?if(isset($_GET['p']) && isset($GLOBALS['INDEX_CONTENT']->pages[$_GET['p']]))
    {
      require_once($GLOBALS['INDEX_CONTENT']->pages[$_GET['p']]);
    }
    else
    {
      require_once($GLOBALS['INDEX_PATH']."main.php");
    }

    if(isset($GLOBALS['INDEX_FOOTER']))
    {
      echo $GLOBALS['INDEX_FOOTER'];
    }

    report_load_error();?>
  </body>
</html>
