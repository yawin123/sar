<?
  $query = "SELECT nombre, id_padre FROM forums WHERE id=".$_GET['post'];
  $row = $GLOBALS['BD']->select($query);

  $res = $row->fetch(PDO::FETCH_ASSOC);

?>

<div class="row">
  <h3><? echo $res['nombre'];?> <small><a href="?p=view&post=<?echo $res['id_padre'];?>">(Volver atrás)</a></small></h3>
</div>
<? $query = "SELECT forums_content.id as id, forums_content.user_id as u_id, forums_content.fecha as fecha, forums_content.content as content, _user._name as u_name, _user.avatar as avatar, _user._range as rango FROM forums_content INNER JOIN _user ON forums_content.user_id = _user._id  WHERE forums_content.thread_id=".$_GET['post'].' ORDER BY forums_content.id ASC';
  $row = $GLOBALS['BD']->select($query);
  $cont = $row->rowCount();

  $first_post = $row->fetch(PDO::FETCH_ASSOC);?>

  <div class="row">
    <div id="<? echo $first_post['id'];?>" class="panel panel-default">
      <div class="panel-heading"><? echo $res['nombre'];?></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-1 text-center">
            <div class="panel panel-default">
              <div class="panel-body">
                <img src="<? echo $GLOBALS['AVATAR_GET']($first_post['avatar']);?>" class="avatar-fill avatar-small"/>
              </div>
              <div class="panel-footer">
                <? echo $first_post['u_name']; ?>
              </div>
            </div>
          </div>
          <div class="col-md-11">
            <div class="panel panel-default">
              <div class="panel-heading">
                <small><?echo date("d/m/y H:i", strtotime($first_post['fecha']));?></small>
                <span class="pull-right">

                  <a href="?p=view&post=<? echo $_GET['post'];?>&jp=<? echo $first_post['id'];?>">#0</a>&nbsp;

                  <? if($first_post['u_id'] == $GLOBALS['USER_ID'])
                  {?>
                    <span title="Editar" id="btn_cancel" class="btn btn-default btn-xs" OnClick="edit_form(<? echo $first_post['id'];?>, '<? echo htmlspecialchars($first_post['content']);?>');">Editar</span>
                  <?}

                  if($GLOBALS['USER_ID'] > 0)
                  {?>
                    <span title="Citar" class="btn btn-warning btn-xs" OnClick="quotation('<? echo htmlspecialchars($first_post['content'])?>','<? echo htmlspecialchars($first_post['u_name'])?>');">Citar</span>
                  <?}?>

                </span>
              </div>
            </div>
            <? echo $first_post['content']?>
          </div>
        </div>
      </div>
      <!--<div class="panel-footer">
      </div>-->
    </div>
  </div>

  <? if($cont > 1)
  {
    $i = 0;
    foreach($row as $r)
    {?>
      <div class="row">
        <div id="<? echo $r['id'];?>" class="panel panel-default">
          <div class="panel-body">
            <div class="col-md-1 text-center">
              <div class="panel panel-default">
                <div class="panel-body">
                  <img src="<? echo $GLOBALS['AVATAR_GET']($r['avatar']);?>" class="avatar-fill avatar-small"/>
                </div>
                <div class="panel-footer">
                  <? echo $r['u_name']; ?>
                </div>
              </div>
            </div>
            <div class="col-md-11">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <small><?echo date("d/m/y H:i", strtotime($r['fecha']));?></small>
                  <span class="pull-right">

                    <a href="?p=view&post=<? echo $_GET['post'];?>&jp=<? echo $r['id'];?>">#<?echo ++$i;?></a>&nbsp;

                    <? if($r['u_id'] == $GLOBALS['USER_ID'])
                    {?>
                      <span title="Editar" id="btn_cancel" class="btn btn-default btn-xs" OnClick="edit_form(<? echo $r['id'];?>, '<? echo htmlspecialchars($r['content']);?>');">Editar</span>
                    <?}

                    if($GLOBALS['USER_ID'] > 0)
                    {?>
                      <span title="Citar" class="btn btn-warning btn-xs" OnClick="quotation('<? echo htmlspecialchars($r['content'])?>','<? echo htmlspecialchars($r['u_name'])?>');">Citar</span>
                    <?}?>

                  </span>
                </div>
              </div>
              <? echo $r['content']?>
            </div>
          </div>
        </div>
      </div>
    <?}
  }

if($GLOBALS['USER_ID'] > 0)
{
  $query = 'SELECT _name, avatar FROM _user WHERE _id='.$GLOBALS['USER_ID'].' ORDER BY _id ASC';
  $row = $GLOBALS['BD']->select($query);
  $r = $row->fetch(PDO::FETCH_ASSOC);
  ?>
  <div id="preview_post_row" class="row">
    <div class="panel panel-default">
      <div class="panel-heading">Previsualización del mensaje</div>
      <div class="panel-body">
        <div class="col-md-1 text-center">
          <img src="<? echo $GLOBALS['AVATAR_GET']($r['avatar']);?>" class="avatar-fill"/><br/>
          <? echo $r['_name']; ?>
        </div>
        <div class="col-md-11">
          <p id="preview_post_content"></p>
          <hr/>
          <small id="preview_post_date"></small>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <p>
      <textarea class="form-control" id="response-content" OnInput="preview();" OnKeyUp="key_up(event);" placeholder="Escriba su respuesta..."></textarea>
    </p>
  </div>
  <div class="row pull-right">
      <button type="button" id="btn_cancel" class="btn btn-default disabled" OnClick="push_cancel();">Cancelar</button>&nbsp;
      <button type="button" id="btn_response" class="btn btn-primary disabled" OnClick="push_response();">Responder</button>
  </div>
  <div class="modal fade" id="editThreadModal" role="dialog" style="top: 100px;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div id="preview_edit_row" class="row">
            <div class="panel panel-default">
              <div class="panel-heading">Previsualización del mensaje</div>
              <div class="panel-body">
                <div class="col-md-11">
                  <p id="preview_edit_content"></p>
                  <hr/>
                  <small id="preview_edit_date"></small>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" id="modal_body">
              <input type="text" id="thread_id" class="hidden"/>
              <label class="control-label hidden" for="thread_title" id="thread_content_label">Debes introducir un comentario</label>
              <textarea class="form-control" id="edit-content" OnInput="preview_edit();" placeholder="Contenido del tema..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="col-md-12">
              <span id="send_button" class="btn btn-primary pull-right" OnClick="edit_thread();">Enviar</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<? echo $GLOBALS['MAIN_PAGE_PATH']?>js/viewPost.js"></script>
  <script>
    function push_response()
    {
      if($("#response-content").val().length > 0)
      {

        content = $("#preview_post_content").html();

        id = <? echo $_GET['post'];?>;
        $.post("./loaderproxy.php", {plugin:"MainPage", content:"newResponse", id:id, response_content:content},
        function(output)
        {
            window.location.replace("?p=view&post=<? echo $_GET['post'];?>&jp="+output);
        });
      }
    }

    function edit_thread()
    {
      id = $("#thread_id").val();
      msg = $("#preview_edit_content").html();

      if(msg != "")
      {
        $.post("./loaderproxy.php", {plugin:"MainPage", content:"editThread", id:id, msg:msg},
        function(output)
        {
          if(output != "")
          {
            debug(output);
          }
          else
          {
            window.location.replace("?p=view&post=<? echo $_GET['post'];?>&jp="+id);
          }
        });
      }
    }

    var jp = getParameterByName("jp");
    if(jp != "")
    {
      window.location.replace("?p=view&post=<? echo $_GET['post'];?>#"+jp);
    }
  </script>
<?}
