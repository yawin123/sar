function title_check()
{
    title = $("#thread_title").val();
    if(title == '')
    {
      $("#modal_header").addClass("has-error", "has-feedback");
      $("#thread_title_label").removeClass("hidden");
    }
    else
    {
      $("#modal_header").removeClass("has-error", "has-feedback");
      $("#thread_title_label").addClass("hidden");
    }

    return title;
}

function content_check()
{
    content = $("#response-content").val();
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

function newThread()
{
    $("#newThreadModal").on('hidden.bs.modal', function (e) {
      $(this).removeData('bs.modal');
    }).modal("show");
}

function preview()
{
    content = content_check();

    if(content != "")
    {
      $("#preview_post_content").html(content.replace("\n", "<br/>"));

      var d = new Date();
      fecha = d.toLocaleDateString("es-ES", { day: 'numeric', month: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric'});

      $("#preview_post_date").html(fecha);

      if($("#preview_post_row").is(":hidden"))
      {
        $("#preview_post_row").slideDown("slow");
      }
    }
    else if($("#preview_post_row").is(":visible"))
    {
      $("#preview_post_content").text('');
      $("#preview_post_row").slideUp("slow");
    }
}
