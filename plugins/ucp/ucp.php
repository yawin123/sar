<?
	if($GLOBALS['USER_ID'] < 1)
	  {
	    echo '<script>location.href="./";</script>';
	    return;
	  }

	$GLOBALS['UCP_NAV']->draw();
?>
<div class="container-double-fluid">
	<div class="container">
		<div class="row">
			<div class="col-md-4 order-md-1">
				<div class="panel-group">
					<div class="panel panel-default" style="position:fixed">
						<div class="panel-heading">Tus datos</div>
						<div class="panel-body">
							<? $query = 'SELECT _id, _name, _email, _range, day_of_birth FROM _user WHERE _id = ' . $GLOBALS['USER_ID'];
							$user = $GLOBALS['BD']->select($query);
							$u = $user->fetch(PDO::FETCH_ASSOC);

							$query2 = 'SELECT _name FROM _ranges WHERE _id = '.$u['_range'];
			        $rango = $GLOBALS['BD']->select($query2);
							$rangename = $rango->fetch(PDO::FETCH_ASSOC)['_name'];?>
							<table class="table table-borderless">
								<tbody>
									<tr>
										<th class="text-right">Nombre</th>
										<td><? echo $u["_name"];?></td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<th class="text-right">Email</th>
										<td><? echo $u["_email"];?></td>
										<td><span title="Editar mail" class="boton pull-right glyphicon glyphicon-edit" OnClick="editMail('<? echo  $u['_email'];?>')"></span></td>
									</tr>
									<tr>
										<th class="text-right">Fecha de nacimiento</th>
										<td><? echo $u["day_of_birth"];?></td>
										<td><span title="Editar fecha de nacimiento" class="boton pull-right glyphicon glyphicon-edit" OnClick="editBirth('<? echo  $u['day_of_birth'];?>')"></span></td>
									</tr>
									<tr>
										<th class="text-right">Rango</th>
										<td><? echo $rangename;?></td>
										<td>&nbsp;</td>
									</tr>
								</tbody>
							</table>
							<span class="btn btn-default form-control" OnClick="editPwd()">Cambiar la contraseña</span>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-8 order-md-2">
				<?
				$query = 'SELECT f.id AS id_padre, f.nombre AS nombre, f.descripcion AS descripcion, fc.id AS id_post, fc.thread_id, fc.fecha AS fecha, fc.content AS contenido FROM forums_content AS fc INNER JOIN forums AS f ON fc.thread_id = f.id WHERE fc.user_id = ' . $GLOBALS['USER_ID'];
				$msjs = $GLOBALS['BD']->select($query);
				$cont = $msjs->rowCount();
				if($cont > 0)
				{
					foreach ($msjs as $msj)
						{?>
						 <div class="panel panel-default">
							<div class="panel-heading">
								<a title="Ir al post" class="button" href="?p=view&post=<? echo($msj['id_padre'])?>#<? echo($msj['id_post'])?>"><? echo $msj["nombre"];?></a>
							</div>

							<div class="panel-body"><? echo $msj["contenido"];?> </div>
						</div>
					<?}
				}?>
			</div>
		</div>
	</div>

	<!-- Diálogo que aparece al editar el mail-->
	<div class="modal fade" id="mailEditor" role="dialog" style="top: 100px;">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Editar dirección electrónica</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	          <div class="col-md-8">
	            <div class="form-group">
								<label for="_email_field">Nueva dirección electrónica:</label>
								<input autofocus class="form-control" type="text" id="_email_field" size="20" maxlength="20"/>
	            </div>
	          </div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" OnClick="saveMail();">Guardar</button>
	        <span class="pull-left"><label id="_mail_error" style="color: #DC143C"></label></span>
	      </div>
	    </div>
	  </div>
	</div>


	<!-- Diálogo que aparece al editar la contraseña-->
	<div class="modal fade" id="pwdEditor" role="dialog" style="top: 100px;">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Editar contraseña (Recuerda que ha de tener un número y letra)</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	          <div class="col-md-8">
	            <div class="form-group">
					<label for="_pwd_field1">Nueva contraseña:</label>
					<input autofocus class="form-control" type="password" id="_pwd_field1" size="20" maxlength="20"/>
					<label for="_pwd_field2">Confirmar contraseña:</label>
					<input class="form-control" type="password" id="_pwd_field2" size="20" maxlength="20"/>
	            </div>
	          </div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" OnClick="savePwd();">Guardar</button>
	        <span class="pull-left"><label id="_pwd_error" style="color: #DC143C"></label></span>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="birthEditor" role="dialog" style="top: 100px;">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Editar fecha de nacimiento</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	          <div class="col-md-2">
	            <div class="form-group">
								<label for="_year_field">Año:</label>
								<input autofocus class="form-control" type="text" id="_year_field" size="20" maxlength="20"/>
							</div>
	          </div>
	          <div class="col-md-2">
	            <div class="form-group">
	            	<label for="_month_field">Mes:</label>
								<input class="form-control" type="text" id="_month_field" size="20" maxlength="20"/>
	            </div>
	          </div>
	          <div class="col-md-2">
	            <div class="form-group">
								<label for="_day_field">Día:</label>
								<input class="form-control" type="text" id="_day_field" size="20" maxlength="20"/>
							</div>
	          </div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" OnClick="saveBirth();">Guardar</button>
	        <span class="pull-left"><label id="_birth_error" style="color: #DC143C"></label></span>
	      </div>
	    </div>
	  </div>
	</div>

	<script src="<?echo $GLOBALS["UCP_PLUGIN"];?>functions.js"></script>
</div>
