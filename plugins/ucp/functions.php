<?
	switch ($content)
	{
		case "saveMail":
			if(!isset($_POST['email']))
				return;

			$query = "UPDATE _user SET _email = '".$_POST['email']."' WHERE _id =".$GLOBALS['USER_ID'];
			$GLOBALS['BD']->update($query);
      
			break;

      case "savePwd":
        if(!isset($_POST['pwd'])) return;

        #update de la contraseña
					$cont = rand(9,20);
					$semilla = "";
					for($i=0;$i<$cont;$i++)
					{
						$semi=rand(0,9);
						$semilla=$semilla.$semi;
					}
					$GLOBALS['SESION']->set_password($GLOBALS['USER_ID'], $_POST['pwd'], $semilla);
      break;

      case "saveBirth":

      	$query = "UPDATE _user SET day_of_birth = '".$_POST['birth']."' WHERE _id =".$GLOBALS['USER_ID'];
			$GLOBALS['BD']->update($query);
      	break;
	}
