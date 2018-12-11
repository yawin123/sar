<?
  $query = "SELECT nombre, id_padre FROM forums WHERE id=".$_GET['post'];
  $row = $GLOBALS['BD']->select($query);
  $res = $row->fetch(PDO::FETCH_ASSOC);
?>

<div class="row">
  <h3><? echo $res['nombre'];?> <small><a href="?p=view&post=<?echo $res['id_padre'];?>">(Volver atrás)</a></small></h3>
</div>
<? $query = "SELECT forums.id as id, forums.nombre as nombre, forums.ult_mod as ult_mod, forums.descripcion as descripcion FROM forums INNER JOIN _ranges ON forums.rango = _ranges._id WHERE forums.id_padre=".$_GET['post']." AND forums.tipo=1 AND _ranges._range_level <=".$GLOBALS['RANGE'];
  $row = $GLOBALS['BD']->select($query);
  $cont = $row->rowCount();

  if($cont > 0)
  {?>
    <div class="row">
      <div class="panel panel-default">
        <div class="panel-heading"><h4>Subforos</h4></div>
        <div class="panel-body">
          <?
            $i = 0;
            foreach($row as $r)
            {
              if($i++ != 0)
              {?>
                <hr/>
              <?}?>
              <div class="row">
                <div class="col-md-8">
                  <h4><a href="?p=view&post=<? echo $r['id'];?>"><? echo $r['nombre'];?></a></h4>
                  <? echo $r['descripcion'];?>
                </div>
                <div class="col-md-4">
                  <? if($r['ult_mod'] == "")
                  {
                    echo "No hay temas";
                  }
                  else
                  {?>
                    Última modificación: <?echo date("d/m/y H:i", strtotime($r['ult_mod']));
                  }?>
                </div>
              </div>
            <?}
          ?>
        </div>
      </div>
    </div>
  <?}
?>
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <? if($GLOBALS['USER_ID'] > 0)
      {?>
        <span class="btn btn-primary pull-right" OnClick="newThread();">Nuevo tema</span>
      <?}?>
      <h4>Temas</h4>
    </div>
    <div class="panel-body">
      <? $query = "SELECT forums.id as id, forums.nombre as nombre, forums.ult_mod as ult_mod FROM forums INNER JOIN _ranges ON forums.rango = _ranges._id WHERE forums.id_padre=".$_GET['post']." AND forums.tipo=2 ORDER BY forums.ult_mod DESC";// AND _ranges._range_level <=".$GLOBALS['RANGE'];
        $row = $GLOBALS['BD']->select($query);
        $cont = $row->rowCount();

        if($cont > 0)
        {
          $i = 0;
          foreach($row as $r)
          {
            if($i++ != 0)
            {?>
              <hr/>
            <?}

            $query = "SELECT forums_content.id as id, forums_content.user_id as user_id, forums_content.fecha as fecha, _user._name as name FROM forums_content INNER JOIN _user ON forums_content.user_id = _user._id WHERE forums_content.thread_id = ".$r['id']." ORDER BY id ASC";
            $row2 = $GLOBALS['BD']->select($query);
            $first_post = null;
            $last_post = null;
            $contador = 0;
            $tam_row = $row2->rowCount();

            foreach($row2 as $r2)
            {
              if($contador == 0)
              {
                $first_post = $r2;
              }

              if($contador == $tam_row-1)
              {
                $last_post = $r2;
              }

              $contador++;
            }
            ?>
            <div class="row">
              <div class="col-md-8">
                <h4><a href="?p=view&post=<? echo $r['id'];?>"><? echo $r['nombre'];?></a></h4>
                <small>Iniciado por: <? echo $first_post['name'];?></small>
              </div>
              <div class="col-md-4">
                  <a title="Ver respuesta" href="?p=view&post=<? echo $r['id'];?>&jp=<? echo $last_post['id'];?>">Última respuesta</a> por: <? echo $last_post['name'];?><br/>
                  <small><? echo $r['ult_mod'];?></small>
              </div>
            </div>
          <?}
        }
        else {
          echo "No hay temas";
        }
      ?>
    </div>
  </div>
</div>
<? if($GLOBALS['USER_ID'] > 0)
{?>
  <div class="modal fade" id="newThreadModal" role="dialog" style="top: 100px;">
    <?
      $query = 'SELECT _name, avatar FROM _user WHERE _id='.$GLOBALS['USER_ID'].' ORDER BY _id ASC';
      $row = $GLOBALS['BD']->select($query);
      $r = $row->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" id="modal_header">
          <label class="control-label hidden" for="thread_title" id="thread_title_label">Debes introducir un título</label>
          <input OnInput="title_check();" type="text" class="form-control" id="thread_title" placeholder="Título del tema..."/>
        </div>
        <div class="modal-body">
          <div id="preview_post_row" class="row">
            <div class="panel panel-default">
              <div class="panel-heading">Previsualización del mensaje</div>
              <div class="panel-body">
                <div class="col-md-11">
                  <span id="preview_post_content"></span>
                  <hr/>
                  <small id="preview_post_date"></small>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" id="modal_body">
              <label class="control-label hidden" for="thread_title" id="thread_content_label">Debes introducir un comentario</label>
              <textarea class="form-control" id="response-content" OnInput="preview();" placeholder="Contenido del tema..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="col-md-12">
              <span class="btn btn-primary pull-right" OnClick="push_thread();">Enviar</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<? echo $GLOBALS['MAIN_PAGE_PATH']?>js/viewForum.js"></script>
  <script>
    function push_thread()
    {
        title = title_check();
        msg = content_check();

        if(title == "" || msg == "")
        {
          return;
        }

        $.post("./loaderproxy.php", {plugin:"MainPage", content:"newThread", id:<? echo $_GET['post'];?>, title:title, msg:msg},
          function(output)
          {
            if(output!= "")
            {
              debug(output);
            }
            else
            {
              location.href = location.href;
            }
          });
    }
  </script>
<?}?>
