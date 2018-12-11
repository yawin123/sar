<?
  if(isset($_FILES['img_field']))
  {
    if($_FILES['img_field'] != NULL)
    {
      $name = $GLOBALS['USER_ID']."_".$_FILES["img_field"]["name"];
      if($GLOBALS['AVATAR_UPLOAD']($_FILES['img_field'], $name))
      {
        $query = "INSERT INTO avatars (path, owner) VALUES ('".$name."', ".$GLOBALS['USER_ID'].")";
        $GLOBALS['BD']->insert($query);
      }
    }
  }

  $GLOBALS['UCP_NAV']->draw();

  $query = 'SELECT avatar FROM _user WHERE _id='.$GLOBALS['USER_ID'].' ORDER BY _id ASC';
  $row = $GLOBALS['BD']->select($query);
  $avatar='';
  foreach($row as $r)
  {
    $avatar = $r['avatar'];
  }
?>
<div class="container-double-fluid">
  <div class="col-md-2">
    <div class="panel panel-default">
      <div class="panel-heading"><h4>Avatar en uso</h4></div>
      <div id="range_list" class="panel-body text-center">
        <img class="avatar-fill avatar-big" src="<? echo $GLOBALS['AVATAR_GET']($avatar);?>"/>
      </div>
      <? if($avatar != 0)
      {?>
        <div class="panel-footer">
          <span class="btn btn-warning form-control" onClick="select(0);">Dejar de usar</span>
        </div>
      <?}?>
    </div>
  </div>
  <div class="col-md-10">
    <div class="panel panel-default">
      <div class="panel-heading"><h4>Galería <span style="margin-left: 15px;" class="btn btn-xs btn-info" onClick="upload_form();">Subir avatar</span></h4></div>
      <div id="range_list" class="panel-body">
        <? $query = 'SELECT COUNT(id) FROM avatars WHERE owner='.$GLOBALS['USER_ID'].' ORDER BY id ASC';
        $row = $GLOBALS['BD']->select($query);
        $cont='';
        foreach($row as $r)
        {
          $cont = $r[0];
        }

        if($cont > 0)
        {
          $query = 'SELECT id FROM avatars WHERE owner='.$GLOBALS['USER_ID'].' ORDER BY id ASC';
          $row = $GLOBALS['BD']->select($query);
          foreach($row as $r)
          {?>
            <div class="col-sm-2 text-center">
              <div class="panel panel-default">
                <div class="panel-body">
                  <img class="avatar-fill avatar-big img-rounded" src="<? echo $GLOBALS['AVATAR_GET']($r['id']);?>"/>
                </div>
                <div class="panel-footer">
                  <? if($avatar != $r['id'])
                  {?>
                    <span class="btn btn-xs btn-success" OnClick="select(<? echo $r['id'];?>)">Seleccionar</span>
                  <?}?>
                  <span class="btn btn-xs btn-danger" OnClick="elimina(<? echo $r['id'];?>);">Eliminar</span>
                </div>
              </div>
            </div>
          <?}
        }
        else
        {?>
          No has subido avatares
        <?}?>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="upload_form" role="dialog" style="top: 100px;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Subir avatar</h4>
      </div>
      <form action="?p=avatar_gallery" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <label for="img_field">Elige una imagen</label>
    	    <input type="file" name="img_field" id="img_field"/>
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-default" value="Subir">
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  function upload_form()
  {
    $("#upload_form").modal("show");
  }

  function select(id)
  {
    $.post("./loaderproxy.php", {plugin:"avatar-gallery", content:"select", id:id},
    function(output)
    {
      location.href = location.href;
    })
  }

  function elimina(id)
  {
    $.post("./loaderproxy.php", {plugin:"avatar-gallery", content:"delete", id:id},
    function(output)
    {
      location.href = location.href;
    })
  }
</script>
