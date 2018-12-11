<div class="container-fluid">
  <?
 if(!isset($_GET['post']))
 {?>
   <script>location.href="./";</script>
   <? return;
 }

 $post_id = $_GET['post'];
 $query = "SELECT tipo, rango FROM forums WHERE id=".$post_id;
 $row = $GLOBALS['BD']->select($query);

 $res = $row->fetch(PDO::FETCH_ASSOC);

 if($GLOBALS['SESION']->getRangeLevel($res['rango']) > $GLOBALS['RANGE'])
 {?>
   <script>location.href="./";</script>
   <? return;
 }?>

 <a class="pull-right" title="Feed RSS" target="_blank" href="?sp=feed&rss=<? echo $_GET['post'];?>">
   <img src="https://www.w3schools.com/xml/pic_rss.gif" width="36" height="14">
 </a>

 <? switch($res['tipo'])
 {
   case 0:?>
     <script>location.href="./";</script>
     <? return;
     break;

   case 1:
    //FORO
    try_to_load($GLOBALS['MAIN_PAGE_PATH'], "view/viewForum.php");
    break;

   case 2:
    //POST
    try_to_load($GLOBALS['MAIN_PAGE_PATH'], "view/viewPost.php");
    break;
 }
?>
</div>
