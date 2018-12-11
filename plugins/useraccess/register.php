<div class="container-fluid">
  <div class="row">
    <div class="col-md-4">&nbsp;</div>
    <div class="col-md-4">
			<h1>Registro</h1>
			<form id="register-form">
				<div class="form-group">
					<label for="usuario">Usuario</label>
					<input autofocus class="form-control" type="text" id="usuario" style="width:280px; height:25px;" size="20" maxlength="20"/>
				</div>
				<div class="form-group">
					<label for="password">Contrase&nacute;a</label>
					<input class="form-control" type="password" size="20" style="width:280px; height:25px;" id="password" maxlength="20"/>
				</div>
				<div class="form-group">
						<label for="rpassword">Repite contrase&nacute;a</label>
						<input class="form-control" type="password" size="20" style="width:280px; height:25px;" id="rpassword" maxlength="20"/>
				</div>
				<div class="form-group">
						<label for="email">Email</label>
						<input class="form-control" type="text" id="email" style="width:280px; height:25px;" size="50" maxlength="50"/>
				</div>
				<div class="form-group">
					<input type="checkbox" id="tos"/>He le&iacute;do y acepto los <a style="color:black; cursor: pointer;" target="_blank" OnClick="tos();"><b><u>T&eacute;rminos y condiciones de uso</u></b></a>
				</div>
				<div class="form-group">
					<span class="btn btn-primary" onClick="register();">Enviar registro</span>
					<span id="_info" class="pull-right"></span>
				</div>
			</form>
    </div>
    <div class="col-md-4">&nbsp;</div>
  </div>
</div>
<div class="modal fade" id="tosModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Términos y condiciones de uso</h4>
        </div>
        <div id="tos_text" class="modal-body">&nbsp;</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<script>
  function tos()
  {
  	$(document).ready(function()
  	{
      $.post('./loaderproxy.php',{plugin:"useraccess", content:"tos"},
      function(output)
      {
        $("#tos_text").html(output);
        $("#tosModal").modal("show");
      });
    });
  }

	function register()
	{
		usuario = document.getElementById("usuario").value;
		password = document.getElementById("password").value;
		rpassword = document.getElementById("rpassword").value;
		email = document.getElementById("email").value;
		tos = document.getElementById("tos").checked;

		$(document).ready(function()
		{
			$.post('./loaderproxy.php',{content:"register", usuario:usuario, password:password, rpassword:rpassword, email:email, tos:tos},
			function(output)
			{
        switch(output)
				{
					case "1":
						location.href="./";
						break;

					case "-1":
						$("#_info").addClass("alert alert-danger");
						$('#_info').html('<span>Debes rellenar todos los campos</span>');
						break;

					case "-2":
						$("#_info").addClass("alert alert-danger");
						$('#_info').html('<span>Las contraseñas no coinciden</span>');
						break;

					case "-3":
						$("#_info").addClass("alert alert-danger");
						$('#_info').html('<span>El email introducido no es válido</span>');
						break;

					case "-4":
						$("#_info").addClass("alert alert-danger");
						$('#_info').html('<span>Debes aceptar los Términos y Condiciones de Uso</span>');
						break;

					case "-5":
						$("#_info").addClass("alert alert-danger");
						$('#_info').html('<span>Ya existe un usuario con ese nombre</span>');
						break;

					case "-6":
						$("#_info").addClass("alert alert-danger");
						$('#_info').html('<span>Ya existe un usuario con ese email</span>');
						break;

					case "-7":
						$("#_info").addClass("alert alert-danger");
						$('#_info').html('<span>Error desconocido</span>');
						break;
				}
			});
		});
	}
</script>
