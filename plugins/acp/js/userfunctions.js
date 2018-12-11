function editUser(id, name, email, day_of_birth, range, avatar)
{
  $("#_id_field").val(id);
  $("#_name_field").val(name);
  $("#_email_field").val(email);
  $("#_range_field").val(range);
  $("#_birth_field").val(day_of_birth);
  $("#avatar").attr("src",avatar);
  $("#userEditor").modal("show");
}

function saveUser()
{
  userid = $("#_id_field").val();
  name = $("#_name_field").val();
  email = $("#_email_field").val();
  range = $("#_range_field").val();
  birth = $("#_birth_field").val();

  if(name == "" || email == ""){return;}

  $.post("./loaderproxy.php", {plugin:"acp", content:"saveUser", name:name, id:userid, email:email, range:range, birth:birth},
    function(output)
    {
      location.reload();
    });
}

function switchBan(id)
{
  $.post("./loaderproxy.php", {plugin:"acp", content:"switchBan", id:id},
    function(output)
    {
      location.reload();
    });
}
