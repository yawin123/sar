<?
/*Esta clase gestiona el contenido de la página principal, las hojas de estilo,
  las páginas adicionales y el menú de navegación principal*/

  require_once($GLOBALS['INDEX_PATH'].'/_menu.php');

  class IndexContent extends Menu
  {
    public $stylesheets = array();
    public $specialpages = array();

    public function __construct($id = '', $logo = '')
    {
      parent::__construct($id, $logo);
    }

    public function addStyleSheet($path)
    {
      array_push($this->stylesheets, $path);
    }

    public function addSpecialPage($key, $path)
    {
      $this->specialpages[$key]=$path;
    }

    public function __toString()
    {
			$ret = parent::__toString();

      $ret = $ret.'Style Sheets: <ul>';
      foreach($this->stylesheets as $k => $t)
      {
        $ret = $ret.'<li>'.htmlentities($k).' => '.htmlentities($t).'</li>';
      }
      $ret = $ret.'</ul>';

      $ret = $ret.'Special Pages: <ul>';
      foreach($this->specialpages as $k => $t)
      {
        $ret = $ret.'<li>'.htmlentities($k).' => '.htmlentities($t).'</li>';
      }
      $ret = $ret.'</ul>';

			return $ret;
    }
  }
?>
