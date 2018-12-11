function debug(txt)
{
  $("#debug-box").html(txt);
  $("#debug-panel").removeClass("hidden");
}

function debug_hide()
{
  $("#debug-panel").addClass("hidden");
}

function getParameterByName(name)
{
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
  results = regex.exec(location.search);
  return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
