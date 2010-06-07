

function append_error(obj, msg)
{
	if (!(obj instanceof jQuery)) obj = $(obj);
	var err = document.createElement("div");
	err.className = "ui-state-error-text";
	err.appendChild(document.createTextNode(msg));
	obj.addClass("ui-state-error").after(err);
}

function verify_account_namepass(field, obj)
{
	if (!(obj instanceof jQuery)) obj = $(obj);
	if (/[\x00-\x1f]/.test(obj.val()))
	{
		append_error(obj, field + " contem caracteres inválidos");
		return false;
	}
	if (obj.val().length < 4)
	{
		append_error(obj, field + " deve conter pelomenos 4 caracteres");
		return false;
	}
	if (obj.val().length > 23)
	{
		append_error(obj, field + " não deve conter mais do 23 caracteres");
		return false;
	}
	return true;
}

function verify_account_name(name)
{
	return verify_account_namepass("Login", name);
}

function verify_password(pass)
{
	return verify_account_namepass("Password", pass);
}

function verify_email(email)
{
	if (!(email instanceof jQuery)) email = $(email);
	if (!email.val().length)
	{
		append_error(email, "Você deve informar seu endereço de e-mail");
		return false;
	}
	if (email.val().length > 39 ||
        !/\@/.test(email.val()) || /[\,|\s|\;]/.test(email.val()) ||
	    (/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)|(\.$)/.test(email.val()) ||
	     (!/^.+\@localhost$/.test(email.val()) &&
	      !/^.+\@\[?(\w|[-.])+\.[a-zA-Z]{2,3}|[0-9]{1,3}\]?$/.test(email.val()))))
	{
		append_error(email, "O endereço de e-mail informado não parece ser um e-mail válido");
		return false;
	}
	return true;
}
