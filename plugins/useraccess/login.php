<div class="container-fluid">
  <div class="row">
    <div class="col-md-4">&nbsp;</div>
    <div class="col-md-4">
      <h1>Login</h1>
      <form>
        <div class="form-group">
          <label for="username">Nombre de usuario</label>
          <input required autofocus type="text" class="form-control" id="username" placeholder="Username"/>
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input required type="password" class="form-control" id="password" placeholder="Password"/>
        </div>
        <div class="form-group">
          <span class="btn btn-primary" OnClick="login()">Login</span>
          <span id="login_info" class="pull-right"></span>
        </div>
      </form>
    </div>
    <div class="col-md-4">&nbsp;</div>
  </div>
</div>
<script>
		function login()
		{
			username = document.getElementById("username").value;
			password = document.getElementById("password").value;

			$(document).ready(function()
			{
				$.post('./loaderproxy.php',{content:"login", login:username, pass:password},
				function(output)
				{
					switch(output)
          {
            case "1":
              location.href="./";
              break;

            case "-1":
              $("#login_info").addClass("alert alert-danger");
              $('#login_info').html('<span>Usuario o contraseña incorrectos</span>');
              break;

            case "-2":
              $("#login_info").addClass("alert alert-danger");
              $('#login_info').html('<span>Usuario baneado</span>');
              break;

            case "-3":
              $("#login_info").addClass("alert alert-danger");
              $('#login_info').html('<span>Introduce todos los datos</span>');
              break;
          }
				});
			});
		}

		function evento()
		{
			document.addEventListener("keypress", function(e)
			{
				var key = e.which || e.keyCode;
				if (key === 13)
				{
					login();
				}
			});
		}

    evento();
	</script>
