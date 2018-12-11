<?
  /*Sestablece como MAIN de la web el script "index.php" situado en
    la carpeta del plugin. Se emplea este método porque, así, se
    permite establecer MAIN alternativos sin tener que reprogramar
    el core*/
    $GLOBALS['MAIN'] = $plugin_path."index.php";

  /*Se registra la ruta del plugin INDEX del core*/
    $GLOBALS['INDEX_PATH'] = $plugin_path;

  /*Se definen la variables globales INDEX_TITLE e
    INDEX_FAVICON que almacenan los datos de título y favicon
    de la web*/
    $GLOBALS['INDEX_TITLE'] = "";
    $GLOBALS['INDEX_FAVICON'] = "";

  /*Se crea una instancia de la clase IndexContent que gestiona el
    contenido de la página principal y del menú de navegación*/
    require_once($plugin_path."_indexcontent.php");
    $GLOBALS['INDEX_CONTENT'] = new IndexContent("index");
    $GLOBALS['INDEX_CONTENT']->addStyleSheet($plugin_path."style.css");

    require_once($plugin_path."_lazy_load.php");
    $GLOBALS['LAZY_LOAD'] = new LazyLoad();

    $GLOBALS['INDEX_FEED_RSS'] = '';
?>
