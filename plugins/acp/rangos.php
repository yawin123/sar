<div class="row">
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading"><h4>Rangos</h4></div>
      <div id="range_list" class="panel-body">
        <?
            $query = "SELECT COUNT(_id) FROM _ranges WHERE _id > 2";
            $row = $GLOBALS['BD']->select($query);
            $rangecont = 0;
            foreach($row as $r){ $rangecont = $r[0]; break;}

            $query = "SELECT _id FROM _ranges WHERE _range_level > 1 ORDER BY _range_level";
            $row = $GLOBALS['BD']->select($query);
            $primer_rango = 0;
            foreach($row as $r){ $primer_rango = $r[0]; break;}

            $query = "SELECT _id FROM _ranges WHERE _range_level < 101 ORDER BY _range_level DESC";
            $row = $GLOBALS['BD']->select($query);
            $ultimo_rango = 0;
            foreach($row as $r){ $ultimo_rango = $r[0]; break;}

            $query = 'SELECT _id, _name, _range_level FROM _ranges WHERE _id > 0 ORDER BY _range_level ASC';
            $row = $GLOBALS['BD']->select($query);
            foreach($row as $r)
            {
                $query2 = 'SELECT COUNT(_id) FROM _user WHERE _id > 0 AND _range='.$r['_id'];
                $row2 = $GLOBALS['BD']->select($query2);
                $cont = 0;
                foreach($row2 as $r2){ $cont = $r2[0]; break;}

                ?><div class="well well-sm">
                    <? echo $r['_name'];?> (<?echo $cont;?>)
                    <?if($r['_range_level'] != 101 && $r['_range_level'] > 1)
                    {?>
                      <span class="pull-right">
                        <?
                          if($rangecont > 1)
                          {
                            if($r['_id'] != $primer_rango)
                            {?>
                              <span title="Up" class="boton glyphicon glyphicon-chevron-up" OnClick="upRange(<? echo  $r['_id'];?>);"></span>
                            <?}
                            if($r['_id'] != $ultimo_rango)
                            {?>
                              <span title="Down" class="boton glyphicon glyphicon-chevron-down" OnClick="downRange(<? echo  $r['_id'];?>);"></span>
                          <?}
                          }
                        ?>
                        <span title="Editar" class="boton glyphicon glyphicon-edit" OnClick="editRange(<? echo  $r['_id'];?>,'<? echo  $r['_name'];?>');"></span>
                        <span title="Eliminar" class="boton glyphicon glyphicon-remove" OnClick="rmRange(<? echo  $r['_id'];?>);"></span>
                      </span>
                    <?}?>
                  </div><?
            }
        ?>
        <hr/>
        <div class="form-group">
          <label for="name">Nuevo rango</label>
    	    <input class="form-control" type="text" id="rangename" style="height:25px;" size="20" maxlength="24" value=""/>
        </div>
        <div class="form-group pull-right">
          <span class="btn btn-xs btn-info" OnClick="newRange();">Añadir</span>
       </div>
     </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading"><h4>Usuarios</h4></div>
      <div class="panel-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Fecha de nacimiento</th>
              <th>Rango</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <? $query = 'SELECT _id, _name, _email, _ban, _range, day_of_birth, avatar FROM _user WHERE _id > 0 ORDER BY _id ASC';
              $row = $GLOBALS['BD']->select($query);
              foreach($row as $r)
              {
                $avatar = $GLOBALS['AVATAR_GET']($r['avatar']);

                $query2 = 'SELECT _name FROM _ranges WHERE _id = '.$r['_range'];
                $row2 = $GLOBALS['BD']->select($query2);
                $rangename = '';
                foreach($row2 as $r2){ $rangename = $r2['_name']; break;}

                $gl = "glyphicon-remove-sign";
                if($r['_ban'] == 0){$gl ="glyphicon-ok-sign";}

                ?><tr>
                    <td><? echo $r['_id'];?></td>
                    <td>
                      <? if($avatar != '')
                      {?>
                          <img class="avatar-icon" src="<? echo $avatar;?>"/>
                      <?}?>

                      <? echo $r['_name'];?>
                    </td>
                    <td><? echo $r['_email'];?></td>
                    <td><? echo $r['day_of_birth'];?></td>
                    <td><? echo $rangename;?></td>
                    <td>
                      <span class="pull-right">
                        <span title="Editar" class="boton glyphicon glyphicon-edit" OnClick="editUser(<? echo  $r['_id'];?>,'<? echo  $r['_name'];?>','<? echo  $r['_email'];?>', '<? echo  $r['day_of_birth'];?>', <? echo $r['_range']?>, '<? echo $avatar;?>');"></span>
                        <span title="Banear" class="boton glyphicon <? echo $gl;?>" OnClick="switchBan(<? echo  $r['_id'];?>);"></span>
                      </span>
                    </td>
                  </tr><?
              }
            ?>
          </tbody>
        </table>
     </div>
    </div>
  </div>
</div>
<div class="modal fade" id="rangeEditor" role="dialog" style="top: 100px;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editar rango</h4>
      </div>
      <div class="modal-body">
        <input class="hidden" id="rangeid" value=""/>
        <label for="rangeedit">Cambiar nombre</label>
  	     <input class="form-control" type="text" id="rangeedit" style="height:25px;" size="20" maxlength="24" value="" placeholder="Introduce el nuevo nombre"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" OnClick="saverange();">Guardar</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="userEditor" role="dialog" style="top: 100px;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editar usuario</h4>
        <input id="_id_field" class="hidden"/>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-5">
            <div class="input-group">
    					<label for="_birth_field">Fecha de nacimiento</label>
              <input id="_birth_field" type="text" class="form-control">
            </div>
            <div class="input-group">
      				<label>Avatar</label><br/>
              <img id="avatar" class="avatar-small"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="_name_field">Usuario</label>
              <input class="form-control" type="text" id="_name_field" size="20" maxlength="20"/>
            </div>
            <div class="form-group">
    					<label for="_email_field">Email</label>
    					<input class="form-control" type="text" id="_email_field" size="20" maxlength="20"/>
    				</div>
            <div class="form-group">
              <select id="_range_field">
                <?
                  $query = "SELECT _id, _name FROM _ranges WHERE _id > 0 ORDER BY _range_level ASC";
                  $row = $GLOBALS['BD']->select($query);
                  foreach($row as $r)
                  {?>
                    <option value="<? echo $r['_id'];?>"><? echo $r['_name'];?></option>
                  <?}
                ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" OnClick="saveUser();">Guardar</button>
      </div>
    </div>
  </div>
</div>
<script src="<?echo $GLOBALS["ACP_PLUGIN"];?>js/rangefunctions.js"></script>
<script src="<?echo $GLOBALS["ACP_PLUGIN"];?>js/userfunctions.js"></script>
