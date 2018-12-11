<? if($orden)
{
  switch ($orden)
	{
    case "login":
      $user = $_POST['login'];
  	  $pass = $_POST['pass'];

  	  if(trim($user) != "" && trim($pass) != "")
  	  {
  		    $usuar = md5(htmlentities($user, ENT_QUOTES));
  		    $password = $pass;

  		    $resp = $GLOBALS['SESION']->get_login($usuar, $password);

  		    switch($resp)
  		    {
  			    case 1:
              echo 1;
               break;

  			    case -1:
              echo -1;
              break;

  			    case -2:
              echo -2;
              break;
  		    }
  	  }
  	  else
  	  {
        echo -3;
      }
      break;

    case "logout":
      $GLOBALS['SESION']->doLogout();
      break;

    case "register":
      if(isset($_POST['usuario']))
      {
        if(trim($_POST["usuario"]) != "" && trim($_POST["password"]) != "" && trim($_POST["rpassword"]) != "" && trim($_POST["email"]) != "")
        {
          $username=htmlentities($_POST["usuario"], ENT_QUOTES);
          $pass=$_POST["password"];
          $rpass=$_POST["rpassword"];
          $email=$_POST["email"];

          if($pass==$rpass)
          {
            if (filter_var($email, FILTER_VALIDATE_EMAIL))
            {
              if(isset($_POST['tos']) && $_POST['tos'] == "true")
              {
                $bd = $GLOBALS['BD'];

                $query = 'SELECT count(_id) FROM _user WHERE _name=\''.$username.'\'';
                foreach($bd->select($query) as $ros)
                {
                  if($ros[0]==0)
                  {
                    $query = 'SELECT count(_id) FROM _user WHERE _email=\''.$email.'\'';
                    foreach($bd->select($query) as $ros2)
                    {
                      if($ros2[0]<1)
                      {
                        $cont = rand(9,20);
                        $semilla = "";
                        for($i=0;$i<$cont;$i++)
                        {
                          $semi=rand(0,9);
                          $semilla=$semilla.$semi;
                        }
                        require_once('./cript.php');
                        $contras = cript($pass,$semilla);

                        $userid = $GLOBALS['SESION']->set_login($username, $contras, $semilla);
                        if($userid == -1)
                        {
                          echo -7;
                          return;
                        }

                        $query = 'INSERT INTO _user (_id, _name, _email) VALUES ('.$userid.',\''.$username.'\',\''.$email.'\')';
                        $bd->insert($query);

                        echo 1;

                        /* SISTEMA DE GENERACIÓN DE TOKEN PARA VALIDACIÓN POR EMAIL
                          (No se ha terminado de desarrollar)
                          $cont=rand(9,20);
                          $semilla="";
                          for($i=0;$i<$cont;$i++)
                          {
                            $semi=rand(0,9);
                            $semilla=$semilla.$semi;
                          }

                          $tok=jarl($semilla,$semilla);
                          $query = 'INSERT INTO user_tokens (userid,token) VALUES ('.$row['id'].',\''.$tok.'\')';
                          $bd->select($query);

                          $asunto="Verifica tu cuenta";
                          $mensaje="�Gracias por registrarte en Pintherol! Para completar el registro necesitamos que verifiques que este email es tuyo. Para ello pulsa en el siguiente enlace: <a href=\"http://pintherol.dnns.net?token=".$tok."\">http://pintherol.dnns.net?token=".$tok."</a>";

                          $mensaje = str_replace('\"', '"', $mensaje);

                          enviamail($mail, $asunto, $mensaje);*/
                      }
                      else
                      {
                        echo -6;
                      }
                    }
                  }
                  else
                  {
                    echo -5;
                  }
                }
              }
              else
              {
                echo -4;
              }
            }
            else
            {
              echo -3;
            }
          }
          else
          {
            echo -2;
          }
        }
        else
        {
          echo -1;
        }
      }
      break;
  }
}
?>
