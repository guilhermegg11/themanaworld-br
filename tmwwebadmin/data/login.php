<?php

require_once '../functions.php';

$valid = validate_login($ladmin);

if ($valid === TRUE && !headers_sent())
{
    @session_name('tmwbr_admin');
    if (@session_start() && @session_setup($ladmin))
    {
        $_SESSION['auth'] = TRUE;
        header('Location: usercontrol.php');
        exit;
    }
}

echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>The Mana World Brasil: Login</title>
    <link type="text/css" rel="stylesheet" href="jquery-ui-1.7.3.custom.css"/>
    <script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="functions.js"></script>
    <style type="text/css">
    /* <![CDATA[ */
table.ui-widget {
    width: 600px;
    border-collapse: collapse;
    margin-left:auto;
    margin-right:auto;
    margin-top: 200px;
}

.label {
    text-align: right;
}

.submit {
    text-align: center;
}

.submit > * {
    padding-left: 10px;
    padding-right: 10px;
}
    /* ]]> */
    </style>
    <script language="javascript">
    /* <![CDATA[ */
$(document).ready(function() {
   $("form :input[type=button]").click(function() {
       $("form").attr("action", "lostpassword.php");
       var tr = document.getElementById("password_field");
       $(tr).empty();
       var td = tr.appendChild(document.createElement("td"));
       td.className = "label";
       td.appendChild(document.createTextNode("E-Mail:"));
       td = tr.appendChild(document.createElement("td"));
       var input = $('<input type="text"/>').attr({
    	   name: "email",
           size: "25",
           maxlength: "39"
       }).appendTo(td);
       tr = document.getElementById("submit_field");
       $(tr).empty();
       td = tr.appendChild(document.createElement("td"));
       td.className = "submit ui-dialog-buttonpane";
       input = $('<input type="submit"/>')
               .appendTo($(td).attr("colspan", "2"))
               .val("Resetar senhar");
   });

   $("form").submit(function() {
       $("form .ui-state-error-text").remove();
       $("form :input").removeClass("ui-state-error");
       if (!verify_account_name("form :input[name=login]")) return false;
       var password = $("form :input[name=password]");
       if (password.get(0) && !verify_password(password)) return false;
       var email = $("form :input[name=email]");
       if (email.get(0) && !verify_email(email)) return false;
       return true;
   });
});
    /* ]]> */
    </script>
</head>
<body>
    <form action="login.php" method="post">
    <table class="ui-widget">
        <tbody class="ui-widget-content">
<?php if (is_string($valid) && strlen($valid)) { ?>
            <tr>
                <td class="ui-state-error-text" colspan="2"><?php
                    echo htmlspecialchars($valid, ENT_NOQUOTES, 'UTF-8'); ?></td>
            </tr>
<?php } ?>
            <tr id="login_field">
                <td class="label">Login:</td>
                <td class="field"><input type="text" name="login" size="25" maxlength="23"/></td>
            </tr>
            <tr id="password_field">
                <td class="label">Password:</td>
                <td class="field"><input type="password" name="password" size="25" maxlength="23"/></td>
            </tr>
            <tr id="submit_field">
                <td class="submit ui-dialog-buttonpane" colspan="2">
                    <input type="submit" value="Logar-se"/>
                    <input type="button" value="Esqueci a senha"/>
                </td>
            </tr>
        </tbody>
    </table>
    </form>
</body>
</html>