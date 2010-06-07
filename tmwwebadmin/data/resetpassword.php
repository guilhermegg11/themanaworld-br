<?php

require_once '../defs.php';
require_once '../db.php';
require_once '../ladmin.php';
require_once '../functions.php';

if (is_string($_GET['login'])) $login = $_GET['login'];
else if (is_string($_POST['login'])) $login = $_POST['login'];
else $login = FALSE;

if (is_string($_GET['verifycode'])) $verifycode = $_GET['verifycode'];
else if (is_string($_POST['verifycode'])) $verifycode = $_POST['verifycode'];
else $verifycode = FALSE;

if (is_string($_GET['passwd'])) $pass = $_GET['passwd'];
else if (is_string($_POST['passwd'])) $pass = $_POST['passwd'];
else $pass = FALSE;

$valid = TRUE;
         
if (is_string($login) && is_string($verifycode))
{
    $sqlconn = new TMW_MySQL(MYSQL_HOST, MYSQL_PORT, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
    if (!$sqlconn || $sqlconn->connect_errno)
        $valid = 'Erro ao conectar no servidor MySQL';
    else
    {
        $getcode = $sqlconn->get_recover_password_code($login);
        if ($getcode === FALSE)
            $valid = 'Erro na consulta MySQL';
        else if ($getcode === 0)
            $valid = 'Nenhuma requisição de recuperação de senha foi feita para esta conta';
        else if ($getcode === -1)
            $valid = 'Conta bloqueada devido ao grande número de tentativas sem sucesso';
        else if ($getcode != $verifycode)
        {
            $valid = 'Código de verificação incorreto';
            if ($sqlconn->incr_recover_password_errcount($login) === FALSE)
                $valid = 'Erro na consulta MySQL';
        }
        else if (is_string($pass))
        {
            $ladmin =& new eAthenaLAdmin();
            $valid = ladmin_connect_auth($ladmin);
            if ($valid === TRUE)
            {
                $chpass = $ladmin->change_passwd($login, $pass);
                if ($chpass === NULL)
                    $valid = 'Conexão com o servidor terminada inexperadamente';
                else
                {
                    if ($chpass === FALSE)
                        $valid = 'Conta de usuário não encontrada no servidor';
                    $sqlconn->delete_recover_password_code($login);
                }
            }
        }
    }
}

echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link type="text/css" rel="stylesheet" href="jquery-ui-1.7.3.custom.css"/>
    <style type="text/css">
    /* <![CDATA[ */
div.ui-widget-content {
    padding-bottom: 30px;
    padding-top: 30px;
    min-width: 450px;
    width: 40%;
    margin-top: 200px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}
    /* ]]> */
    </style>
<?php if ($valid === TRUE && !is_string($pass)) { ?>
    <script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="functions.js"></script>
    <script language="javascript" type="text/javascript">
    /* <![CDATA[ */
$(document).ready(function() {
	$("form").submit(function() {
        if (!verify_password("form :input[name=passwd]")) return false;
        return true;
	});
});
    /* ]]> */
    </script>
<?php } ?>
    <title>Reset de Senha</title>
</head>
<body>
<?php
    if ($valid === TRUE) {
        if (is_string($pass)) {
?>
<div class="ui-widget-content"><span>Sua senha foi modificada</span></div>
<?php
        } else {
?>
<form action="resetpassword.php" method="post">
<input type="hidden" name="verifycode" value="<?php
    echo htmlspecialchars($verifycode, ENT_COMPAT, 'UTF-8'); ?>"/>
<input type="hidden" name="login" value="<?php 
    echo htmlspecialchars($login, ENT_COMPAT, 'UTF-8') ?>"/>
<div class="ui-widget-content">
    <div class="ui-widget">
        Digite sua nova senha: <input type="password" name="passwd" size="25" maxlength="23"/>
    </div>
    <div class="ui-widget">
        <input type="submit" value="Alterar Senha"/>
    </div>
</div>
</form>
<?php
        }
    } else {
?>
<div class="ui-widget-content"><span class="ui-state-error-text"><?php
    echo htmlspecialchars($valid, ENT_NOQUOTES, 'UTF-8'); ?></span></div>
<?php
    }
?>
</body>
</html>