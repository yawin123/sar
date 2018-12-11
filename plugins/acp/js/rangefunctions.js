function editRange(id, name)
{
    $("#rangeid").val(id);
    $("#rangeedit").val(name);
    $("#rangeEditor").modal("show");
}
function saverange()
{
    rangeid = $("#rangeid").val();
    name = $("#rangeedit").val();
    if(name == ""){return;}

    $.post("./loaderproxy.php", {plugin:"acp", content:"saveRange", name:name, id:rangeid},
      function(output)
      {
        location.reload();
      });
}

function newRange()
{
  name = $("#rangename").val()
  if(name == ""){return;}

  $.post("./loaderproxy.php", {plugin:"acp", content:"newRange", name:name},
    function(output)
    {
      location.reload();
    });
}

function rmRange(id)
{
  $.post("./loaderproxy.php", {plugin:"acp", content:"rmRange", id:id},
    function(output)
    {
      location.reload();
    });
}

function upRange(id)
{
  $.post("./loaderproxy.php", {plugin:"acp", content:"upRange", id:id},
    function(output)
    {
      location.reload();
    });
}

function downRange(id)
{
  $.post("./loaderproxy.php", {plugin:"acp", content:"downRange", id:id},
    function(output)
    {
      location.reload();
    });
}
