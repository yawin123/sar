<?
  if(isset($_POST['content']))
  {
    switch($_POST['content'])
    {
      case "delete":
        $GLOBALS['AVATAR_DELETE']($_POST['id']);
        break;

      case "select":
        $GLOBALS['AVATAR_SET']($_POST['id']);
    }
  }
?>
