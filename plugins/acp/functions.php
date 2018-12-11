<?
  function print_forum($pt, $in = 'in')
  {?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" href="#<? echo $pt->id;?>"><? echo $pt->nombre;?></a>
          <? if($pt->id > -1)
          {?>
          <span class="pull-right">
            <span title="Añadir subforo" class="boton glyphicon glyphicon-plus" OnClick="newForum(<? echo $pt->id;?>);"></span>&nbsp;
            <span title="Editar" class="boton glyphicon glyphicon-edit" OnClick="loadForum(<? echo $pt->id;?>, <? echo ($pt->tipo == 1) ? "true" : "false";?>);"></span>
            <span title="Eliminar" class="boton glyphicon glyphicon-remove" OnClick="rmForum(<? echo $pt->id;?>);"></span>
          </span>
          <?}?>
        </h4>
      </div>
      <div id="<? echo $pt->id;?>" class="panel-collapse collapse <? echo $in;?>">
        <div class="panel-body">
          <p>
            <?if($pt->descripcion != '')
            {
              echo $pt->descripcion;
            }
            else if($pt->id > -1)
            {
              echo "No hay descripcion";
            }?>
          </p>
          <?
            foreach($pt->sons as $ts)
            {
              print_forum($ts);
            }
          ?>
        </div>
      </div>
    </div>
    <? return;
  }

  function get_father_list($pt, $exclude, $lvl = "")
  {
    if($pt->id != $exclude && $pt->id != -1)
    {?>
      <option value="<? echo $pt->id;?>"><? echo $lvl.' '.$pt->nombre;?></option>
    <?}

    foreach($pt->sons as $ts)
    {
      get_father_list($ts, $exclude, $lvl."-");
    }
  }

  switch($content)
  {
    case "saveConfig":
      $pf = fopen("config.php", 'w');
      fwrite($pf, $value);
      fclose($pf);
      break;

    case "saveTos":
      $pf = fopen("tos.txt", 'w');
      fwrite($pf, $value);
      fclose($pf);
      break;

    case "forum-get":
      if(isset($_POST['id']) && isset($_POST['campo']))
      {
        $query = 'SELECT '.$_POST['campo'].' FROM forums WHERE id='.$_POST['id'];
        $row = $GLOBALS['BD']->select($query);
        foreach($row as $r)
        {
          echo $r[0];
        }
      }
      break;

    case "forum-set":
      if(isset($_POST['id_forum']) && isset($_POST['id_father']) && isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['tipo']) && isset($_POST['rango']))
      {
        $query = 'SELECT COUNT(id) FROM forums WHERE id='.$_POST['id_forum'];
        $row = $GLOBALS['BD']->select($query);

        $cont = 0;
        foreach($row as $r)
        {
          $cont = $r[0];
          break;
        }

        if($cont < 1)
        {
          $query = 'INSERT INTO forums (id_padre, nombre, descripcion, tipo, rango) VALUES ('.$_POST['id_father'].', \''.$_POST['nombre'].'\', \''.$_POST['descripcion'].'\', '.$_POST['tipo'].', '.$_POST['rango'].')';
          $GLOBALS['BD']->insert($query);
        }
        else
        {
          $query = 'UPDATE forums SET id_padre = '.$_POST['id_father'].', nombre = \''.$_POST['nombre'].'\', descripcion = \''.$_POST['descripcion'].'\', tipo = '.$_POST['tipo'].', rango = '.$_POST['rango'].' WHERE id='.$_POST['id_forum'];
          $GLOBALS['BD']->update($query);

          $query = 'UPDATE forums SET rango = '.$_POST['rango'].' WHERE tipo=2 AND id_padre='.$_POST['id_forum'];
          $GLOBALS['BD']->update($query);
        }
      }
      break;

    case "print_forum":
      $forum_tree = get_forum_tree();

      foreach($forum_tree as $t)
      {
        print_forum($t, "in");
      }
      break;

    case "get-father-list":
      $forum_tree = get_forum_tree();

      $exclude = -1;
      if(isset($_POST['exclude']))
      {
        $exclude = $_POST['exclude'];
      }

      foreach($forum_tree as $t)
      {
        get_father_list($t, $exclude);
      }
      break;

    case "forum-del":
      if(!isset($_POST['id'])){return;}

      $query = 'UPDATE forums SET id_padre = -1 WHERE tipo = 1 AND id_padre='.$_POST['id'];
      $GLOBALS['BD']->update($query);

      $query = "SELECT id FROM forums WHERE tipo = 2 AND id_padre=".$_POST['id'];
      $row = $GLOBALS['BD']->select($query);
      $cont = $row->rowCount();

      if($cont > 0)
      {
        foreach($row as $r)
        {
          $query = "DELETE FROM forums_content WHERE thread_id =".$r['id'];
          $GLOBALS['BD']->update($query);
        }
      }

      $query = "DELETE FROM forums WHERE id_padre =".$_POST['id'];
      $GLOBALS['BD']->delete($query);

      $query = "DELETE FROM forums WHERE id=".$_POST['id'];
      $GLOBALS['BD']->delete($query);
      break;

    case "load-range":
      if(isset($_POST['id_range']) && isset($_POST['field']))
      {
        $query = "SELECT ".$_POST['field']." WHERE id =".$_POST['id_range'];
        $row = $GLOBALS['BD']->select($query);
        foreach($row as $r)
        {
          echo $r[0];
        }
      }
      break;

    case "newRange":
      if(!isset($_POST['name'])){return;}

      $select = "SELECT _range_level FROM _ranges WHERE _range_level < 101 ORDER BY _range_level DESC";
      $row = $GLOBALS['BD']->select($select);

      $rangelevel = 0;
      foreach($row as $r)
      {
        $rangelevel = $r['_range_level'] + 1;
        break;
      }

      $query = "INSERT INTO _ranges (_name, _range_level) VALUES ('".$_POST['name']."', ".$rangelevel.")";
      $row = $GLOBALS['BD']->insert($query);
      break;

    case "rmRange":
      if(!isset($_POST['id'])){return;}

      $select = "SELECT _range_level FROM _ranges WHERE _id = ".$_POST['id'];
      $row = $GLOBALS['BD']->select($select);
      $rid = 0;
      foreach($row as $r)
      {
        $select2 = "SELECT _id FROM _ranges WHERE _range_level > ".$r['_range_level']." ORDER BY _range_level DESC";
        $row2 = $GLOBALS['BD']->select($select2);

        foreach($row2 as $r2)
        {
          $rid = $r2['_id'];
          break;
        }
        break;
      }

      $query = "UPDATE _user SET _range = ".$rid." WHERE _range=".$_POST['id'];
      $GLOBALS['BD']->update($query);
      $query = "UPDATE forums SET rango = ".$rid." WHERE rango=".$_POST['id'];
      $GLOBALS['BD']->update($query);

      $query="DELETE FROM _ranges WHERE _id=".$_POST['id'];
      $GLOBALS['BD']->delete($query);
      break;

    case "upRange":
      if(!isset($_POST['id'])){return;}

      $select = "SELECT _range_level FROM _ranges WHERE _id = ".$_POST['id'];
      $row = $GLOBALS['BD']->select($select);
      $rlvl = 0;
      foreach($row as $r){$rlvl = $r['_range_level'];}

      $select = "SELECT _id, _range_level FROM _ranges WHERE _range_level < ".$rlvl." ORDER BY _range_level DESC";
      $row = $GLOBALS['BD']->select($select);
      $last_id = 0;
      $last_lvl = 0;
      foreach($row as $r)
      {
        $last_id = $r['_id'];
        $last_lvl = $r['_range_level'];
        break;
      }

      $query = "UPDATE _ranges SET _range_level =".$last_lvl." WHERE _id=".$_POST['id'];
      $row = $GLOBALS['BD']->update($query);
      $query = "UPDATE _ranges SET _range_level =".$rlvl." WHERE _id=".$last_id;
      $row = $GLOBALS['BD']->update($query);
      break;

    case "downRange":
      if(!isset($_POST['id'])){return;}

      $select = "SELECT _range_level FROM _ranges WHERE _id = ".$_POST['id'];
      $row = $GLOBALS['BD']->select($select);
      $rlvl = 0;
      foreach($row as $r){$rlvl = $r['_range_level'];}

      $select = "SELECT _id, _range_level FROM _ranges WHERE _range_level > ".$rlvl." ORDER BY _range_level";
      $row = $GLOBALS['BD']->select($select);
      $next_id = 0;
      $next_lvl = 0;
      foreach($row as $r)
      {
        $next_id = $r['_id'];
        $next_lvl = $r['_range_level'];
        break;
      }

      $query = "UPDATE _ranges SET _range_level =".$next_lvl." WHERE _id=".$_POST['id'];
      $row = $GLOBALS['BD']->update($query);
      $query = "UPDATE _ranges SET _range_level =".$rlvl." WHERE _id=".$next_id;
      $row = $GLOBALS['BD']->update($query);
      break;

    case "saveRange":
      if(!isset($_POST['id']) || !isset($_POST['name'])){return;}

      $query = "UPDATE _ranges SET _name ='".$_POST['name']."' WHERE _id = ".$_POST['id'];
      $row = $GLOBALS['BD']->update($query);
      break;

    case "saveUser":
      if(!isset($_POST['id']) || !isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['range']) || !isset($_POST['birth'])){return;}

      $query = 'SELECT COUNT(_id) FROM _user WHERE _id='.$_POST['id'];
      $row = $GLOBALS['BD']->select($query);
      $cont = 0;
      foreach($row as $r){$cont = $r[0]; break;}

      if($cont > 0)
      {
        //UPDATE
        $query = "UPDATE _user SET _name = '".$_POST['name']."', _email = '".$_POST['email']."', _range=".$_POST['range'].", day_of_birth='".$_POST['birth']."' WHERE _id =".$_POST['id'];
        $GLOBALS['BD']->update($query);
      }
      break;

    case "switchBan":
      if(!isset($_POST['id'])){return;}

      $query ="SELECT _ban FROM _user WHERE _id=".$_POST['id'];
      $row = $GLOBALS['BD']->select($query);
      $ban = 0;
      foreach($row as $r)
      {
        $ban = $r['_ban'];
        break;
      }

      $ban = ($ban == 0)? 1 : 0;

      $query = "UPDATE _user SET _ban=".$ban." WHERE _id =".$_POST['id'];
      $GLOBALS['BD']->update($query);
      break;
  }
?>
