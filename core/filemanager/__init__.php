<?
	class FileManager
	{
		private $path = "./uploads/";

		public function __construct($plugin = "no_plugin")
		{
			$this->path = $this->path.$plugin.'/';
		}

		public function SetPath($plugin)
		{
			$this->path = './uploads/'.$plugin.'/';
		}

		public function GetPath()
		{
			return $this->path;
		}

		public function CheckFolder()
		{
			return file_exists($this->path);
		}

		public function ListFolder($folder = "")
		{
			$pila = array();
			$ruta = $this->path.$folder;

			if(is_dir($ruta))
			{
				if($dh = opendir($ruta))
				{
					while(($file = readdir($dh)) !== false)
					{
						if(!is_dir($ruta.$file) && $file!="." && $file!="..")
						{
							array_push($pila,$file);
						}
					}
				}
				closedir($dh);
			}

			return $pila;
		}

		public function Upload($file, $name = "")
		{
			if(!$this->CheckFolder())
			{
				return;
			}

			if($file==NULL)
			{
				return;
			}

			$tamano=$file['size'];
			$tipo=$file['type'];

			$nombre = $name;
			if($name == "")
			{
				$archivo=$file['name'];

				$dar_acena= str_replace("á","a",$archivo);
				$dar_acene= str_replace("é","e",$dar_acena);
				$dar_aceni= str_replace("í","i",$dar_acene);
				$dar_aceno= str_replace("ó","o",$dar_aceni);
				$dar_acenu= str_replace("ú","u",$dar_aceno);
				$dar_ene= str_replace("ñ","n",$dar_acenu);
				$dar_esp= str_replace(" ","_",$dar_ene);
				$nombre=$dar_esp;
			}

			$destino=$this->path.$nombre;
			$status='';

			if(copy($file['tmp_name'],$destino))
			{
				$status=$nombre;
			}

			return $status;
		}

		public function Delete($file)
		{
			if(file_exists($this->path.$file))
			{
				unlink($this->path.$file);
			}
		}

		public function __toString()
    {
			$tmp_path = $this->path;
			$this->path = './uploads/';
      $ret = json_encode($this->ListFolder());
			$this->path = $tmp_path;

			if($ret == "[]")
			{
				$ret = "./uploads/";
			}

			return $ret;
    }
	}

	$GLOBALS['FILEMANAGER'] = new FileManager();
?>
