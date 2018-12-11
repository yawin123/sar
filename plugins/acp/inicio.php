<div class="row">
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">Globales</div>
      <div class="panel-body">
        <?
          foreach($GLOBALS as $t => $g)
          {
            $linea = '';
            switch(gettype($g))
            {
              case "string":
                $linea = $g;
                break;

              case "boolean":
                $linea = (($g) ? 'true' : 'false');
                break;

              case "array":
                break;

              default:
                if($t != 'SET_LANG')
                {
                  $linea= (string)$g;
                }
            }

            if($linea != '')
            {?>
              <strong><? echo $t;?>:</strong><div class="well well-sm"><? echo $linea;?></div>
            <?}
          }
        ?>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div id="config-panel" class="panel panel-default">
      <div class="panel-heading">Configuración <span class="btn btn-xs btn-info pull-right" OnClick="saveConfig();">Guardar</span></div>
      <div class="panel-body">
        <textarea id="config_field" class="form-control"><? $pf = fopen("config.php", "r"); echo fread($pf, filesize("config.php")); fclose($pf);?></textarea>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div id="tos-panel" class="panel panel-default">
      <div class="panel-heading">Términos y condiciones de uso <span class="btn btn-xs btn-info pull-right" OnClick="saveTos();">Guardar</span></div>
      <div class="panel-body">
        <textarea id="tos_field" class="form-control"><? $pf = fopen("tos.txt", "r"); echo fread($pf, filesize("tos.txt")); fclose($pf);?></textarea>
      </div>
    </div>
  </div>
</div>
<script>
  function saveConfig()
  {
    saveFile("saveConfig", $('textarea#config_field').val(), "#config-panel");
  }
  function saveTos()
  {
      saveFile("saveTos", $('textarea#tos_field').val(), "#tos-panel");
  }
  function saveFile(file, value, panel)
  {
    $.post('./loaderproxy.php',{plugin:"acp", content:file, value:value});
    $(panel).addClass("panel-success");
    $(panel).removeClass("panel-default");
    setInterval(function()
                {
                  $(panel).addClass("panel-default");
                  $(panel).removeClass("panel-success");
                  clearInterval();
                }, 3000);
  }
</script>
