function edit_form(id, content)
{
  $("#thread_id").val(id);
  $("#edit-content").val(content);
  preview_edit();

  $("#editThreadModal").on('hidden.bs.modal', function (e) {
    $(this).removeData('bs.modal');
  }).modal("show");
}
function key_up(event) {

  if (event.defaultPrevented) {
    return;
  }

  if(event.key === "Esc" || event.key === "Escape" )
  {
    push_cancel();
  }

  event.preventDefault();
}

function preview()
{
  content = $("#response-content").val();
  if(content != "")
  {
    $("#preview_post_content").html(content.replace("\n", "<br/>"));

    var d = new Date();
    fecha = d.toLocaleDateString("es-ES", { day: 'numeric', month: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric'});

    $("#preview_post_date").html(fecha);
    if($("#preview_post_content").is(":hidden"))
    {
      $("#preview_post_row").slideDown("slow", function () {
        $("#response-content")[0].scrollIntoView({behavior: "smooth"});
      });
      $("#btn_response").removeClass("disabled").addClass("enabled");
      $("#btn_cancel").removeClass("disabled").addClass("enabled");
    }
  }
  else if($("#preview_post_content").is(":visible"))
  {
    $("#preview_post_row").slideUp("slow");
    $("#btn_response").removeClass("enabled").addClass("disabled");
    $("#btn_cancel").removeClass("enabled").addClass("disabled");
  }
}

function content_check()
{
  content = $("#edit-content").val();
  if(content == "")
  {
    $("#modal_body").addClass("has-error", "has-feedback");
    $("#thread_content_label").removeClass("hidden");
  }
  else
  {
    $("#modal_body").removeClass("has-error", "has-feedback");
    $("#thread_content_label").addClass("hidden");
  }

  return content;
}

function preview_edit()
{
  content = content_check();

  if(content != "")
  {
    $("#preview_edit_content").html(content.replace("\n", "<br/>"));

    var d = new Date();
    fecha = d.toLocaleDateString("es-ES", { day: 'numeric', month: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric'});

    $("#preview_edit_date").html(fecha);

    if($("#preview_edit_row").is(":hidden"))
    {
      $("#preview_edit_row").slideDown("slow");
    }
  }
  else if($("#preview_edit_row").is(":visible"))
  {
    $("#preview_edit_content").text('');
    $("#preview_edit_row").slideUp("slow");
  }
}

function quotation(msg, usname)
{
  $("#response-content").val("<blockquote>"+msg+"<small>"+usname+"</small></blockquote>");
  preview();
}

function push_cancel()
{
  $("#response-content").val('');
  preview();
}
