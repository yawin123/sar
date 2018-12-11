<?
	require_once("./cript.php");

	class Sesiones
	{
		private $bd;

		public function __construct($_bd, $_user, $_pass, $bd_name)
		{
			$this->bd = new DB($_bd, $_user, $_pass, $bd_name);
		}

		public function getrealip()
		{
			if (!empty($_SERVER['HTTP_CLIENT_IP']))
			{
				return $_SERVER['HTTP_CLIENT_IP'];
			}

			if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
			}

			return $_SERVER['REMOTE_ADDR'];
		}

		public function creasesion($userid)
		{
			//Capturar ip
				$ipadd = $this->getrealip();

			//Generamos nombre de variable y contenido
				//Nombre de variable
					$cont=rand(9,20);
					$semilla="";
					for($i=0;$i<$cont;$i++)
					{
						$semi=rand(0,9);
						$semilla=$semilla.$semi;
					}
					$nvar=cript(substr(md5(uniqid(rand())),0,6),$semilla);

				//Contenido de la variable
					$cont=rand(9,20);
					$semilla="";
					for($i=0;$i<$cont;$i++)
					{
						$semi=rand(0,9);
						$semilla=$semilla.$semi;
					}
					$nval=cript(substr(md5(uniqid(rand())),0,6),$semilla);

			//Si existe entrada para ese usuario
					$query = 'SELECT COUNT(ip) FROM sessions WHERE ip=\''.$ipadd.'\'';
					$row = $this->bd->select($query);
					$_cont = 0;
					foreach($row as $r){$_cont = $r[0];}
					if($_cont > 0)
					{
						//Updatear la entrada con el nombre de variable
						$query="UPDATE sessions SET nvar = '$nvar', nval = '$nval', ip = '$ipadd', userid = $userid WHERE ip = '$ipadd'";
						$this->bd->update($query);
					}
					else
					{
						//Insertar la entrada con el nombre de variable
						$query = "INSERT INTO sessions (ip,nvar,nval,userid) VALUES ('$ipadd','$nvar','$nval',$userid)";
						$this->bd->insert($query);
					}

			//Crear sesión
				$_SESSION[$nvar]=$nval;
		}

		public function compruebases()
		{
			//Capturar ip
				$ipadd = $this->getrealip();

			//Recuperamos entrada para esa ip
				$hay=0;

					$query = 'SELECT count(ip), nvar, nval, userid FROM sessions WHERE ip=\''.$ipadd.'\'';
					if($row=$this->bd->select($query))
					{
						$hay=1;
					}

			//Si existe entrada para esa ip
				if($hay>0)
				{
					//Comprobamos si las variables de sesión son correctas
						$nvar = "";
						$nval = "";
						$userid = "";

						foreach($row as $r)
						{
							$nvar = $r['nvar'];
							$nval = $r['nval'];
							$userid = $r['userid'];
						}

						if(isset($_SESSION[$nvar]))
						{
							if($_SESSION[$nvar]==$nval)
							{
							    //NOS HA APARECIDO UN BUG RELACIONADO CON LAS VARIABLES DE SESIÓN
							    //MIENTRAS SE RESUELVE, APAGAMOS ESTE MECANISMO
								    /*$query = 'DELETE FROM sessions WHERE ip=\''.$ipadd.'\'';
								    unset($_SESSION[$nvar]);
								    $this->creasesion($userid);*/
								return $userid;
							}
							else
								return -3;
						}
						else
							return -2;
				}
				return -1;
		}

		public function set_username($userid, $username)
		{
			$query = 'UPDATE security SET username=md5(\''.$username.'\') WHERE id = '.$userid;
			$this->bd->update($query);
		}

		public function set_password($userid, $pass, $semilla)
		{
			$query = 'UPDATE security SET pass=\''.cript($pass,$semilla).'\', semilla=\''.$semilla.'\' WHERE id = '.$userid;
			$this->bd->update($query);
		}
		public function set_login($username, $pass, $semilla)
		{
			//Insertar la entrada con el nombre de variable
			$query = 'INSERT INTO security (username,pass,semilla) VALUES (\''.md5($username).'\',\''.$pass.'\',\''.$semilla.'\')';
			$this->bd->insert($query);

			$query = "SELECT id FROM security WHERE username='".md5($username)."'";
			$row = $this->bd->select($query);

			foreach($row as $r)
			{
				return $r['id'];
			}

			return -1;
		}

		public function get_login($user, $pass)
		{
			$query = "SELECT count(id), pass, id, username, semilla FROM security WHERE username='$user'";

			$row = $this->bd->select($query);

			$_cont = 0;
			$_semilla = '';
			$_userid = '';
			$_pass = '';
			foreach($row as $r)
			{
				$_cont = $r[0];
				$_semilla = $r['semilla'];
				$_userid = $r['id'];
				$_pass = $r['pass'];
			}

			if($_cont > 0)
			{
				$pass = cript($pass,$_semilla);

				if($_pass == $pass && $_userid!=0)
				{
					$_ban = $this->checkBan($_userid);

					if($_ban != 0)
					{
						return -2;
					}
					else
					{
						$this->creasesion($_userid);
						return 1;
					}
				}
				else
				{
					return -1;
				}
			}
			else
			{
				return -1;
			}
		}

		function doLogout()
		{
			$ipadd = $this->getrealip();
			$query = 'DELETE FROM sessions WHERE ip=\''.$ipadd.'\'';
      session_destroy();
		}

		public function checkBan($_userid)
		{
			$query = "SELECT _ban FROM _user WHERE _id = $_userid";
			$row = $GLOBALS['BD']->select($query);

			$_ban = 0;
			foreach($row as $r)
			{
				$_ban = $r['_ban'];
			}

			return $_ban;
		}

		public function getRangeId($_userid)
		{
			$query = "SELECT _range FROM _user WHERE _id = $_userid";
			$row = $GLOBALS['BD']->select($query);
			$_priv = 0;

			if(!empty($row))
			{
				foreach($row as $r)
				{
					$_priv = $r['_range'];
				}

				return $_priv;
			}
			else
			{
				return 0;
			}
		}

		public function getRangeLevel($rangeid)
		{
			$query = "SELECT _range_level FROM _ranges WHERE _id =".$rangeid;
			$row = $GLOBALS['BD']->select($query);
			$_ret = 0;

			if(!empty($row))
			{
				foreach($row as $r)
				{
					$_ret = $r['_range_level'];
				}

				return $_ret;
			}
			else
			{
				return 0;
			}
		}

		public function __toString()
    {
        return (string)$this->bd;
    }
	}

	try
	{
		$secur_bd_name = '';
		if(isset($GLOBALS['DISABLE_SECURITY_DB']) && $GLOBALS['DISABLE_SECURITY_DB'])
		{
			$secur_bd_name = $GLOBALS['BD_NAME'];
		}
		else
		{
			$secur_bd_name = $GLOBALS['BD_NAME'].$GLOBALS['SECURITY_DB_SUFIX'];
		}

		$GLOBALS['SESION'] = new Sesiones($GLOBALS['BD_SERVER'], $GLOBALS['BD_USER'], $GLOBALS['BD_PASS'], $secur_bd_name);
		$GLOBALS['USER_ID'] = $GLOBALS['SESION']->compruebases();
		$GLOBALS['RANGE_ID'] = $GLOBALS['SESION']->getRangeId($GLOBALS['USER_ID']);
		$GLOBALS['RANGE'] = $GLOBALS['SESION']->getRangeLevel($GLOBALS['RANGE_ID']);
	}
	catch(Exception $ex)
	{
		echo "[ERROR] No se ha podido conectar con la base de datos de seguridad";
	}
?>
