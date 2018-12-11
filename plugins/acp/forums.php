<div class="row">
  <div class="col-md-6">
    <h2>Árbol de foros <span class="btn btn-xs btn-info pull-right" OnClick="newCategory();">Nueva categoría</span></h2>
    <div id="forum_tree" class="panel-group"></div>
  </div>
  <div id="editor" class="col-md-6 hidden">
    <div id="config-panel" class="panel panel-default">
      <div class="panel-heading"><span id="editor-title">Editor</span> <span class="btn btn-xs btn-info pull-right" OnClick="saveConfig();">Guardar</span></div>
      <div class="panel-body">
        <input type="text" id="id_field" class="hidden" value="-1"/>
        <input type="text" id="cat" class="hidden" value="1"/>
        <div class="form-group">
					<label for="name">Nombre</label>
					<input autofocus class="form-control" type="text" id="name" style="height:25px;" size="20" maxlength="49" value=""/>
				</div>
				<div id="description_group" class="form-group">
					<label for="description">Descripcion</label>
					<textarea class="form-control" style="height:200px;" id="description" maxlength="254"></textarea>
				</div>
        <div class="form-group">
          <label for="range">Rango de visibilidad</label>
          <select id="range">
            <?
              $query = 'SELECT _id, _name FROM _ranges ORDER BY _range_level ASC';
              $row = $GLOBALS['BD']->select($query);
              foreach($row as $r)
              {
                ?><option value="<? echo $r['_id'];?>"><? echo $r['_name'];?></option><?
              }
            ?>
          </select>
        </div>
        <div id="father_group" class="form-group">
          <label for="range">Padre</label>
          <select id="id_father">
          </select>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function get_input_field(id, field, input_field)
  {
    $.post('./loaderproxy.php',{plugin:"acp", content:"forum-get", id:id, campo:field},
          function(output)
          {
            $(input_field).val(output);
          });
  }

  function get_check_field(id, field, input_field)
  {
    $.post('./loaderproxy.php',{plugin:"acp", content:"forum-get", id:id, campo:field},
          function(output)
          {
            $(input_field).prop("checked", output==0);
          });
  }

  function get_father_list(id)
  {
    lista = '<option value="-1">Ninguno</option><option>---------------</option>';
    $.post('./loaderproxy.php',{plugin:"acp", content:"get-father-list", exclude:id},
        function(output)
        {
          $("#id_father").html(lista+output);
          get_input_field(id, "id_padre", "#id_father");
        });
  }

  function get_new_father_list(id)
  {
    lista = '<option value="-1">Ninguno</option><option>---------------</option>';
    $.post('./loaderproxy.php',{plugin:"acp", content:"get-father-list"},
        function(output)
        {
          $("#id_father").html(lista+output);
          $("#id_father").val(id);
        });
  }

  function loadForum(id, fatherCBox)
  {
    $("input#id_field").val(id);
    get_father_list(id);
    get_input_field(id, "nombre", "input#name");
    get_input_field(id, "descripcion", "textarea#description");
    get_input_field(id, "tipo", "input#cat");
    //get_check_field(id, "tipo", "#cat");
    get_input_field(id, "rango", "#range");

    if(fatherCBox == true)
    {
      $("#editor-title").html("Editar foro");
      $("#father_group").removeClass("hidden");
      $("#description_group").removeClass("hidden");
    }
    else
    {
      $("#editor-title").html("Editar categoría");
      $("#father_group").addClass("hidden");
      $("#description_group").addClass("hidden");
    }

    $("#editor").removeClass("hidden");
  }

  function newForum(id)
  {
    $("input#id_field").val(-1);
    get_new_father_list(id);
    $("input#name").val("");
    $("textarea#description").val("");
    $("input#cat").val(1);
    $("#range").val(0);

    $("#father_group").removeClass("hidden");
    $("#description_group").removeClass("hidden");

    $("#editor-title").html("Nuevo foro");
    $("#editor").removeClass("hidden");
  }

  function newCategory()
  {
    $("input#id_field").val(-1);
    get_new_father_list(-1);
    $("input#name").val("");
    $("textarea#description").val("");
    $("input#cat").val(0);
    $("#range").val(0);

    $("#father_group").addClass("hidden");
    $("#description_group").addClass("hidden");

    $("#editor-title").html("Nueva categoría");
    $("#editor").removeClass("hidden");
  }

  function rmForum(id)
  {
    $.post('./loaderproxy.php',{plugin:"acp", content:"forum-del", id:id},
      function(output)
      {
        print_forum();
      });
  }

  function saveConfig()
  {
    id_forum = $("input#id_field").val();
    id_father = $("#id_father").val();
    nombre = $("input#name").val();
    descripcion = $("textarea#description").val();
    tipo = $("input#cat").val();
    rango = $("#range").val();

    $.post('./loaderproxy.php',{plugin:"acp", content:"forum-set", id_forum:id_forum, id_father:id_father, nombre:nombre, descripcion:descripcion, tipo:tipo, rango:rango},
          function(output)
          {
            print_forum();
            loadForum(id_forum, tipo == 1);

            $("#config-panel").addClass("panel-success");
            $("#config-panel").removeClass("panel-default");
            setInterval(function()
                        {
                          $("#config-panel").addClass("panel-default");
                          $("#config-panel").removeClass("panel-success");
                          clearInterval();
                        }, 3000);
          });
  }

  function print_forum()
  {
    $.post('./loaderproxy.php',{plugin:"acp", content:"print_forum"},
        function(output)
        {
          $("#forum_tree").html(output);
        });
  }

  print_forum();
</script>
