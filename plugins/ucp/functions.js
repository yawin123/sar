//------------------------ Funciones del mail -------------------------

function editMail(email)
{
	$("#_email_field").val(email);
	$("#mailEditor").modal("show");
}

function saveMail()
{
	email = $("#_email_field").val();
	if(email == ""){
		$("#_mail_error").text("Introduce una dirección de correo");
		return;
	}

	if(!validateEmail(email)){
		$("#_mail_error").text("La dirección de correo introducida no es válida");
		return;
	}

	$.post("./loaderproxy.php", {plugin:"ucp", content:"saveMail", email:email},
	function(output){
		location.reload();
	});

}

function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

//-------------------- Funciones de la contraseña --------------------

function editPwd()
{
	$("#pwdEditor").modal("show");
}

function savePwd()
{
	pwd1 = $("#_pwd_field1").val();
	pwd2 = $("#_pwd_field2").val();

	/*if(!validatePwd(pwd1)){
		$("#_pwd_error").text("La contraseña ha de contener mínimo una palabara y un número");
		return;
	} */
	trimmedPwd = pwd1.trim();
	if(trimmedPwd == ""){
		$("#_pwd_error").text("La contraseña no puede ser solo espacios vacios");
		return;
	}

	if(pwd1 == ""){
		$("#_pwd_error").text("Ineserte una contraseña en el primer campo");
		return;
	}

	if (pwd2 == ""){
		$("#_pwd_error").text("Ineserte la misma contraseña en el segundo campo");
		return;
	}

	if (pwd1 != pwd2){
		$("#_pwd_error").text("Las contraseñas no coinciden");
		return;
	}

	$.post("./loaderproxy.php", {plugin:"ucp", content:"savePwd", pwd:pwd1},
	function(output){
		location.reload();
	});

}

/*function validatePwd(pwd)
{
	var re = /^.*(?=.*[a-zA-Z])(?=.*\d).*$/;
	return re.test(pwd);
}*/

//------------- Funciones de la fecha de nacimiento ------------------

function editBirth(fecha)
{
	$("#birthEditor").modal("show");
	year = fecha.substring(0, 4);
	month = fecha.substring(5, 7);
	day = fecha.substring(8, 10);
	$("#_year_field").val(year);
	$("#_month_field").val(month);
	$("#_day_field").val(day);
}

function saveBirth()
{
	year = $("#_year_field").val();
	month = $("#_month_field").val();
	day = $("#_day_field").val();

	if(year.length != 4){
		$("#_birth_error").text("El año ha de tener 4 carácteres");
		return;
	}

	if(month.length != 2){
		$("#_birth_error").text("El mes ha de tener 2 carácteres (si es 3, pon 03, por ejemplo)");
		return;
	}

	if(day.length != 2){
		$("#_birth_error").text("El día ha de tener 2 carácteres (si es 4, pon 04, por ejemplo)");
		return;
	}

	if(!validateBirth(year, month, day)){
		$("#_birth_error").text("Los valores no son correctos");
		return;

	}

	birth = year + '-' + month + '-' + day;
	$.post("./loaderproxy.php", {plugin:"ucp", content:"saveBirth", birth:birth},
	function(output){
		location.reload();
	});
}

function validateBirth(year_to_parse, month_to_parse, day_to_parse)
{
	var day = parseInt(day_to_parse);
	var year = parseInt(year_to_parse);
	var month = parseInt(month_to_parse);
	if(month == 4 || month == 6 || month == 11){
		if(1 > day || day > 30)
			return false;
	} else if(month == 2)	{
		if(year % 4 == 0){
			if(1 > day || day > 29){
				return false;
			}
		}else{
			if(1 > day || day > 28){
				return false;
			}
		}

	} else if(month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12){
		if(1 > day || day > 31){
			return false;
		}
	}else if(month < 1 || month > 12) {

		return false;
	}

	return true;
}
