<?
$GLOBALS['INDEX_TITLE'] = "Sistema Autónomo de Registro";

$GLOBALS['INDEX_CONTENT']->nav_logo = "S.A.R";
$GLOBALS['INDEX_CONTENT']->addStyleSheet($plugin_path . "style.css");
$GLOBALS['INDEX_CONTENT']->addPage("view", $plugin_path."view.php");

$GLOBALS['MAIN_PAGE_PATH'] = $plugin_path;

try_to_load($plugin_path, "functions.php");
$related_div = generateBody('');

$GLOBALS['INDEX_CONTENT']->addContent(
    "index",
    "Index",
    "",
    "location.href= './';",
    $related_div);

$GLOBALS['INDEX_CONTENT']->addContent(
    "header",
    "",
    "",
    "",
    '<div id="chevron" class="container-fluid text-center">
       <a href="#pagina" title="To Top">
         <span class="glyphicon glyphicon-chevron-up"></span>
       </a>
     </div>');

$GLOBALS['INDEX_FOOTER'] = '
      <footer class="text-center">
        <strong>Copyright &copy; 2018 GC09</strong> <a target="_blank" href="https://www.gnu.org/licenses/gpl.html"><img alt="Logo de licencia GNU-GPL v3" style="height: 25px;" src="https://www.gnu.org/graphics/gplv3-127x51.png"/></a>
      </footer>';
