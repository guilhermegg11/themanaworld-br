<?php

require_once '../defs.php';
require_once '../db.php';
require_once '../ladmin.php';
require_once '../functions.php';

if (isset($_GET['login']) && is_string($_GET['login'])) $login = $_GET['login'];
else if (isset($_POST['login']) && is_string($_POST['login'])) $login = $_POST['login'];
else $login = FALSE;

if (isset($_GET['verifycode']) && is_string($_GET['verifycode'])) $verifycode = $_GET['verifycode'];
else if (isset($_POST['verifycode']) && is_string($_POST['verifycode'])) $verifycode = $_POST['verifycode'];
else $verifycode = FALSE;

if (isset($_GET['passwd']) && is_string($_GET['passwd'])) $pass = $_GET['passwd'];
else if (isset($_POST['passwd']) && is_string($_POST['passwd'])) $pass = $_POST['passwd'];
else $pass = FALSE;

if (isset($_GET['sex']) && is_string($_GET['sex'])) $sex = $_GET['sex'];
else if (isset($_POST['sex']) && is_string($_POST['sex'])) $sex = $_POST['sex'];
else $sex = FALSE;


$valid = TRUE;
         
if (is_string($login) && is_string($verifycode))
{
    $sqlconn = new TMW_MySQL(MYSQL_HOST, MYSQL_PORT, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
    if (!$sqlconn || $sqlconn->connect_errno)
        $valid = 'Erro ao conectar no servidor MySQL';
    else
    {
        $getcode = $sqlconn->get_register_code($login, $getmail);
        if ($getcode === FALSE)
            $valid = 'Erro na consulta MySQL';
        else if ($getcode === 0)
            $valid = 'Nenhuma solicitação de registro foi feita para o login indicado';
        else if ($getcode === -1)
            $valid = 'Registro cancelado devido ao grande número de tentativas sem sucesso';
        else if ($getcode != $verifycode)
        {
            $valid = 'Código de verificação incorreto';
            if ($sqlconn->incr_register_errcount($login) === FALSE)
                $valid = 'Erro na consulta MySQL';
        }
        else if (is_string($pass) && is_string($sex))
        {
            if ($sex != 'M' && $sex != 'F')
                $valid = 'Selecione o sexo do personagem de sua conta';
            else if (!eAthenaLAdmin::verify_password($pass))
                $valid = 'A senha selecionada não é válida';
            else
            {
                $ladmin =& new eAthenaLAdmin();
                $valid = ladmin_connect_auth($ladmin);
                if ($valid === TRUE)
                {
                    $newaccount = $ladmin->create_account($login, $sex, $getmail, $pass);
                    if ($newaccount === NULL)
                        $valid = 'Conexão com o servidor terminada inexperadamente';
                    else
                    {
                        if ($newaccount === FALSE)
                            $valid = 'Algum outro usuário já registrou um conta com o login escolhido';
                        $sqlconn->delete_register_code($login);
                    }
                    if ($valid === TRUE)
                        $sqlconn->set_operation_log($login, 'CFM_REG');
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
    min-width: 590px;
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
        if (!verify_password("form :input[name=passwd]") ||
            !verify_sex("form :input[name=sex]:checked")) return false;
        return true;
    });
});
    /* ]]> */
    </script>
<?php } ?>
    <title>The Mana World Brasil: Registro de Conta</title>
</head>
<body>
<?php
    if ($valid === TRUE) {
        if (is_string($pass)) {
?>
<div class="ui-widget-content"><span>Sua conta foi ativada</span></div>
<?php
        } else {
?>
<form action="confirmregister.php" method="post">
<input type="hidden" name="verifycode" value="<?php
    echo htmlspecialchars($verifycode, ENT_COMPAT, 'UTF-8'); ?>"/>
<input type="hidden" name="login" value="<?php 
    echo htmlspecialchars($login, ENT_COMPAT, 'UTF-8') ?>"/>
<div class="ui-widget-content">
    <div class="ui-widget">
        Digite sua senha de acesso: <input type="password" name="passwd" size="25" maxlength="23"/>
    </div>
    <div class="ui-widget">
        Selecione o sexo de seu personagem:
        <input type="radio" name="sex" id="sexM" value="M"/><label for="sexM">Homem</label>
        <input type="radio" name="sex" id="sexF" value="F"/><label for="sexF">Mulher</label>
    </div>
    <div class="ui-widget">
        <input type="submit" value="Finalizar Cadastro"/>
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