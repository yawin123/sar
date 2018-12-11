<?
  class LazyLoad
  {
    public $content = array();

    public function addContent($id)
    {
      array_push($this->content, $id);
    }

    public function load()
    {
      foreach($this->content as $plugin_path)
      {
        if(is_dir($plugin_path))
				{
					if(file_exists($plugin_path."__lazy_load__.php"))
					{
            try_to_load($plugin_path, "__lazy_load__.php", "[LAZY_LOAD] ");
						//require_once($plugin_path."__lazy_load__.php");
          }
        }
      }
    }

    public function __toString()
    {
      $ret = 'Lazy Plugins: <ul>';
      foreach($this->content as $g)
      {
        $ret = $ret.'<li>'.htmlentities((string)$g).'</li>';
      }
      $ret = $ret.'</ul>';

      return $ret;
    }
  }
?>
