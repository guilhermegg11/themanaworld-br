<?php

require_once '../defs.php';
require_once '../functions.php';
require_once '../recaptchalib.php';

$valid = FALSE;

if (isset($_POST['login']) && is_string($_POST['login']) &&
    isset($_POST['email']) && is_string($_POST['email']) &&
    @ladmin_connect_auth($ladmin) === TRUE)
{
    $recaptcha_result = recaptcha_check_answer(RECAPTCHA_PRIVATE_KEY, $_SERVER["REMOTE_ADDR"],
                                               $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
    if ($recaptcha_result->is_valid)
    {
        require_once '../db.php';
        require_once 'Mail.php';

        $sqlconn = new TMW_MySQL(MYSQL_HOST, MYSQL_PORT, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
        if (!$sqlconn || $sqlconn->connect_errno)
            $valid = 'Erro ao conectar no servidor MySQL';
        else if (!$ladmin->verify_email($_POST['email']))
            $valid = 'O e-mail informado não parece ser um e-mail válido';
        else if ($ladmin->id_account($_POST['login']) !== FALSE)
            $valid = 'Login selecionado já existe. Escolha outro';
        else if (email_exists($_POST['email'], $sqlconn))
            $valid = 'Algum outro player já tem cadastro neste servidor usando este e-mail. Por favor escolha outro';
        else
        {
            if ($sqlconn->delete_register_code($_POST['login']) === FALSE ||
                !($code=$sqlconn->set_register_code($_POST['login'], $_POST['email'])))
                $valid = 'Erro na consulta MySQL';
            else
            {
                $random_hash = md5(date('r', time()));
                $to = $_POST['email'];
                $headers = array(
                    'From' => EMAIL_FROM,
                        'To' => $_POST['email'],
                    'Subject' => '[The Mana World Brasil] Registro de Conta',
                        'Content-Type' => "multipart/alternative; boundary=\"tmw-br-$random_hash\""
                );
                ob_start();
                include '../registermail.php';
                $message = ob_get_clean();
                $mail =& Mail::factory('smtp', array(
                    'host' => SMTP_HOST,
                    'port' => SMTP_PORT,
                    'auth' => TRUE,
                    'username' => SMTP_USER,
                    'password' => SMTP_PASS
                ));
                if (PEAR::isError($mail) ||
                    PEAR::isError($mail->send($to, $headers, $message)))
                    $valid = 'Erro ao enviar e-mail de confirmação';
            }
        }
        if ($valid === FALSE)
        {
            $valid = TRUE;
            $sqlconn->set_operation_log($_POST['login'], 'REQ_REG', $_POST['email']);
        }
    }
    else
        $valid = 'Texto de verificação do reCAPTCHA incorreto';
}

echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>The Mana World Brasil: Registro de Conta</title>
    <link type="text/css" rel="stylesheet" href="jquery-ui-1.7.3.custom.css"/>
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

#recaptcha_area {
    margin-left:auto;
    margin-right:auto;
}
    /* ]]> */
    </style>
    <script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="functions.js"></script>
    <script language="javascript" type="text/javascript">
    /* <![CDATA[ */
$(document).ready(function() {
    $("form").submit(function() {
        var valid = true;
        for (var i = 0; i < this.elements.length; i++)
        {
            if (this.elements[i].name == "login" && !verify_account_name(this.elements[i])) valid = false;
            if (this.elements[i].name == "email" && !verify_email(this.elements[i])) valid = false;
        }
        return valid;
    });
});
    /* ]]> */
    </script>
</head>
<body>
<?php if ($valid === TRUE) { ?>
<div align="center">E-mail de confirmação enviado</div>
<?php } else { ?>
<form action="register.php" method="post">
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
            <tr id="email_field">
                <td class="label">E-Mail:</td>
                <td class="field"><input type="text" name="email" size="25" maxlength="39"/></td>
            </tr>
            <tr id="submit_field">
                <td class="submit ui-dialog-buttonpane" colspan="2">
                    <div class="recaptcha"><?php echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY); ?></div>
                    <input type="submit" value="Solicitar Cadastro"/>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<?php } ?>
</body>
</html>