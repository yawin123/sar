<?
function generateBody($related_div)
{
    $related_div = $related_div.'<div class="row">
      <a title="Feed RSS" target="_blank" href="?sp=feed">
        <img src="https://www.w3schools.com/xml/pic_rss.gif" width="36" height="14">
      </a>
      <form class="form-inline pull-right" onsubmit="event.preventDefault(); return false;">
          <i class="btn fa fa-search" aria-hidden="true" OnClick="query_search($(\'#search_box\').val());"></i>&ensp;
          <input id="search_box" class="form-control form-control-sm mr-3 w-75" type="search" placeholder="Buscar" aria-label="Buscar" required minlength="2">
      </form>
      <br/>
      <br/>
    </div>';
    $query = "SELECT forums.id as id, forums.nombre as nombre FROM forums INNER JOIN _ranges ON forums.rango = _ranges._id WHERE forums.id_padre=-1 AND forums.tipo=0 AND _ranges._range_level <=".$GLOBALS['RANGE'];
    $row = $GLOBALS['BD']->select($query);
    $cont = $row->rowCount();

    if($cont > 0)
    {
        foreach($row as $r)
        {
            $related_div = $related_div.'<div id="forums-div" class="row">
          <div class="panel panel-default">
            <div class="panel-heading"><h4>'.$r['nombre'].'</h4></div>
            <div class="panel-body">';

            $query = "SELECT forums.id as id, forums.nombre as nombre, forums.ult_mod as ult_mod, forums.descripcion as descripcion FROM forums INNER JOIN _ranges ON forums.rango = _ranges._id WHERE forums.id_padre=".$r['id']." AND forums.tipo=1 AND _ranges._range_level <=".$GLOBALS['RANGE'];
            $row2 = $GLOBALS['BD']->select($query);

            $i = 0;
            foreach($row2 as $r2)
            {
                if($i++ != 0)
                {
                    $related_div = $related_div.'<hr/>';
                }
                $related_div = $related_div.'<div class="row">
              <div class="col-md-8"><h4><a href="?p=view&post='.$r2['id'].'">'.$r2['nombre'].'</a></h4>'.$r2['descripcion'].' </div>
              <div class="col-md-4">';
                if($r2['ult_mod'] == "")
                {
                    $related_div = $related_div."No hay temas";
                }
                else
                {
                    $related_div = $related_div.'Última modificación: '.date("d/m/y H:i", strtotime($r2['ult_mod']));
                }
                $related_div = $related_div.'</div>
            </div>';
            }
            $related_div = $related_div.'</div>
          </div>
        </div>';
        }
    }

    $related_div = $related_div.'
    <div id="search-result" class="row">        
    </div>
    <script>
      
      $("#search_box").on("input", function(e){
        //query_search($(this).val());       
      });
      
      $("#search_box").on("keyup", function(e) {

        if (e.defaultPrevented) {
          return;
        }
        
        switch (e.key) {
          case "Enter":
            if($(this)[0].validity.valid) {
              query_search($(this).val());
            }
            break;
          case "Esc":
          case "Escape":
             $(this).val("");
            break;
          default:
            return;
        }

        e.preventDefault();
      });

      function query_search(search) {        
        
        if($("#search_box").val().length > 1) {
          
          // Remplazar saltos de lineas y espacios
          var search = $("#search_box").val();//.replace(/(\\r\\n\\t|\\n|\\r\\t|\\s+)/gm,"");
          
          if(search.length > 0) {
            
            $("#search-result").show();
            $("#forums-div").hide();
            
            $.post("./loaderproxy.php", {plugin: "MainPage", content: "querySearch", search_content: search},
            function(response) {
                if(response.length > 0) {
                  $("#search-result").html(response);
                }
            });
          
          }
          
        } else {
          
          $("#search-result").hide();
          $("#forums-div").show();
        
        } 
        
      }
    </script>';

    return $related_div;
}

?>
