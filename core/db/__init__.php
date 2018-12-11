<?
	class DB
	{
		private $bd, $bd_conf;

		public function __construct($_bd, $_user, $_pass, $bd_name)
		{
			$this->bd = new PDO("mysql:dbname=".$bd_name.";host=".$_bd, $_user, $_pass);
			$this->bd_conf = "mysql:dbname=".$bd_name.";host=".$_bd.$_user.$_pass;
		}

		public function getBD()
		{
			return $this->bd;
		}

		public function select($query)
		{
			return $this->bd->query($query);
		}

		public function update($query)
		{
			try
			{
				$stmnt = $this->bd->prepare($query);
				if($stmnt->execute() == false)
				{
					return $stmnt->errorInfo()[2];
				}
			}
			catch(PDOException $e)
			{
				return $e->getMessage();
			}
		}

		public function insert($query)
		{
			try
			{
				$stmnt = $this->bd->prepare($query);
				if($stmnt->execute() == false)
				{
					return $stmnt->errorInfo()[2];
				}
			}
			catch(PDOException $e)
			{
				return $e->getMessage();
			}
		}

		public function delete($query)
		{
			try
			{
				$stmnt = $this->bd->prepare($query);
				if($stmnt->execute() == false)
				{
					return $stmnt->errorInfo()[2];
				}
			}
			catch(PDOException $e)
			{
				return $e->getMessage();
			}
		}

		public function __toString()
    {
        return $this->bd_conf;
    }
	}

	if(!isset($GLOBALS['BD_SERVER']))
	{
		$GLOBALS['BD_SERVER'] = "";
	}
	if(!isset($GLOBALS['BD_NAME']))
	{
		$GLOBALS['BD_NAME'] = '';
	}
	if(!isset($GLOBALS['BD_USER']))
	{
		$GLOBALS['BD_USER'] = '';
	}
	if(!isset($GLOBALS['BD_PASS']))
	{
		$GLOBALS['BD_PASS'] = '';
	}

	try
	{
		$GLOBALS['BD'] = new DB($GLOBALS['BD_SERVER'], $GLOBALS['BD_USER'], $GLOBALS['BD_PASS'], $GLOBALS['BD_NAME']);
	}
	catch(Exception $ex)
	{
		echo "[ERROR] No se ha podido conectar con la base de datos";
	}
?>
