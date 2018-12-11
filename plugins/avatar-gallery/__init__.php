<?
  $GLOBALS['LAZY_LOAD']->addContent($plugin_path);
  $GLOBALS['INDEX_CONTENT']->addStyleSheet($plugin_path."style.css");

  $GLOBALS['AVATAR_UPLOAD'] = "avatar_upload";
  function avatar_upload($file, $name)
  {
    $GLOBALS['FILEMANAGER']->SetPath("avatar-gallery");
    if($GLOBALS['FILEMANAGER']->CheckFolder())
    {
      if($GLOBALS['FILEMANAGER']->Upload($file, $name))
      {
        return true;
      }
    }

    return false;
  }

  $GLOBALS['AVATAR_DELETE'] = "avatar_delete";
  function avatar_delete($id)
  {
    $query = "SELECT path, owner FROM avatars WHERE id =".$id;
    $row = $GLOBALS['BD']->select($query);

    $path = "";
    $owner = "";
    foreach($row as $r)
    {
      $path = $r['path'];
      $owner = $r['owner'];
      break;
    }

    if($owner == $GLOBALS['USER_ID'])
    {
      $query = "SELECT avatar FROM _user WHERE _id=".$GLOBALS['USER_ID'];
      $row = $GLOBALS['BD']->select($query);
      foreach($row as $r)
      {
        if($r['avatar'] == $id)
        {
          $GLOBALS['AVATAR_SET'](0);
        }
      }

      $query = "DELETE FROM avatars WHERE id=".$id;
      $GLOBALS['BD']->delete($query);

      $GLOBALS['FILEMANAGER']->SetPath("avatar-gallery");
      $GLOBALS['FILEMANAGER']->Delete($path);
    }
  }

  $GLOBALS['AVATAR_SET'] = "avatar_set";
  function avatar_set($id)
  {
    $query = "SELECT owner FROM avatars WHERE id =".$id;
    $row = $GLOBALS['BD']->select($query);

    $owner = "";
    foreach($row as $r)
    {
      $owner = $r['owner'];
      break;
    }

    if($owner == $GLOBALS['USER_ID']  || $owner == 0)
    {
      $query = "UPDATE _user SET avatar =".$id." WHERE _id=".$GLOBALS['USER_ID'];
      $GLOBALS['BD']->update($query);
    }
  }

  $GLOBALS['AVATAR_GET'] = "avatar_get";
  function avatar_get($id)
  {
      $query = "SELECT path, owner FROM avatars WHERE id=".$id;
      $row = $GLOBALS['BD']->select($query);

      $path = '';
      $owner = '';
      foreach($row as $r)
      {
        $path = $r['path'];
        $owner = $r['owner'];
        break;
      }

      return './uploads/avatar-gallery/'.$path;
  }
?>
