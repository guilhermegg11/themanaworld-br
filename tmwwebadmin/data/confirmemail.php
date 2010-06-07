<?php


if (!is_string($_GET['login']) || !strlen($_GET['login']))
    $valid = 'Login incompleto, não foi possível completar a operação';
else if (!is_string($_GET['verifycode']) || strlen($_GET['verifycode']) != 64)
    $valid = 'Código de verificação incompleto, não foi possível completar a operação';
else
{
    require_once '../defs.php';
    require_once '../db.php';

    $sqlconn = new TMW_MySQL(MYSQL_HOST, MYSQL_PORT, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
    if (!$sqlconn || $sqlconn->connect_errno)
        $valid = 'Erro ao conectar no servidor MySQL';
    else
        $valid = TRUE;
}
if ($valid === TRUE)
{
    $getcode = $sqlconn->get_email_code($_GET['login'], $getmail);
    if ($getcode === FALSE)
        $valid = 'Erro na consulta MySQL';
    else if ($getcode === 0)
        $valid = 'Nenhuma requisição de cadastro de e-mail foi feito para esta conta';
    else if ($getcode === -1)
        $valid = 'Requisição de cadastro de e-mail removido devido ao grande número de tentativas sem sucesso';
    else if ($getcode != $_GET['verifycode'])
    {
        $valid = 'Código de verificação incorreto';
        if ($sqlconn->incr_email_code_errcount($_GET['login']) === FALSE)
            $valid = 'Erro na consulta MySQL';
    }
    else
    {
        require_once '../functions.php';

        if (($valid=ladmin_connect_auth($ladmin)) === TRUE)
        {
            $valid = $ladmin->change_email($_GET['login'], $getmail);
            if ($valid === NULL)
                $valid = 'Conexão com o servidor terminada inexperadamente';
            else if ($valid === FALSE)
                $valid = 'Conta de usuário não encontrada no servidor';
            else if ($sqlconn->delete_email_code($_GET['login']) === FALSE)
                $valid = 'Requisição finalizada, porem houve um erro na consulta MySQL';
        }
    }
}

echo '<?xml version="1.0" encoding="utf-8"?>';
?>

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

div.ui-widget-content span.success {
    color: #212121;
}
    /* ]]> */
    </style>
    <title>Reset de Senha</title>
</head>
<body>
<?php
if ($valid === TRUE) {
?>
<div class="ui-widget-content"><span class="success">Seu e-mail foi alterado com sucesso</span></div>
<?php
} else {
?>
<div class="ui-widget-content"><span class="ui-state-error-text"><?php
echo htmlspecialchars($valid, ENT_NOQUOTES, 'UTF-8') ?></span></div>
<?php
}
?>
</body>
</html>
