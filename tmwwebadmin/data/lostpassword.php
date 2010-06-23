<?php

require_once '../defs.php';
require_once '../functions.php';
require_once '../ladmin.php';
require_once '../db.php';
require_once 'Mail.php';

function email_match(&$ladmin)
{
    if (!is_string($_POST['login']) || !strlen($_POST['login']) ||
        !is_string($_POST['email']) || !strlen($_POST['email']))
        return FALSE;

    if (!$ladmin->verify_account_name($_POST['login']) ||
        !$ladmin->verify_email($_POST['email']))
        return FALSE;

    $id = $ladmin->id_account($_POST['login']);
    if (!is_int($id))
        return FALSE;
    $account = $ladmin->info_account($id);
    if (!is_array($account) || !isset($account['email']) ||
        $account['email'] === 'a@a.com')
        return FALSE;
    if ($account['email'] === $_POST['email'])
        return TRUE;
    return FALSE;
}


$ladmin =& new eAthenaLAdmin();
$valid = ladmin_connect_auth($ladmin);


if ($valid === TRUE)
{
    if (!$ladmin->verify_account_name($_POST['login']) ||
        ($id=$ladmin->id_account($_POST['login'])) === FALSE)
        $valid = 'Login não encontrado no servidor';
    else if ($id === NULL)
        $valid = 'Conexão com o servidor finalizada inexperadamente';
    if ($valid === TRUE)
    {
        $sqlconn = new TMW_MySQL(MYSQL_HOST, MYSQL_PORT, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
        if (!$sqlconn || $sqlconn->connect_errno)
            $valid = 'Erro ao conectar no servidor MySQL';
    }
}
if ($valid === TRUE && !headers_sent())
{
    if (email_match($ladmin))
    {
        $code = $sqlconn->get_recover_password_code($_POST['login']);
        if ($code === FALSE)
            $valid = 'Erro na consulta MySQL';
        else if ($code === -1)
            $valid = 'O e-mail de verificação já foi enviado';
        else if ($code === 0)
        {
            $code = $sqlconn->set_recover_password_code($_POST['login']);
            if (!$code)
                $valid = 'Erro na consulta MySQL';
        }
        else if ($sqlconn->incr_recover_password_reqcount($_POST['login']) === FALSE)
            $valid = 'Erro na consulta MySQL';
        if ($valid === TRUE)
        {
            $random_hash = md5(date('r', time())); 
            $to = $_POST['email'];
            $headers = array(
                'From' => EMAIL_FROM,
            	'To' => $_POST['email'],
                'Subject' => '[The Mana World Brasil] Reinicialização da senha',
                'Content-Type' => "multipart/alternative; boundary=\"tmw-br-$random_hash\""
    	    );
    	    ob_start();
    	    include '../lostpassmail.php';
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
            if ($valid === TRUE)
                $sqlconn->set_operation_log($_POST['login'], 'REQ_CHPASS');
        }
    }
    else
        $valid = 'O e-mail informado não é o mesmo cadastrado em sua conta';
}
else if (!is_string($valid)) $valid = '';

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
    <title>Reset de Senha</title>
</head>
<body>
<div class="ui-widget-content">
<?php if ($valid === TRUE) { ?>
    <span>E-mail de confirmação enviado. Assim que receber acesse o link indicado no corpo do e-mail</span>
<?php } else { ?>
    <span class="ui-state-error-text"><?php echo htmlspecialchars($valid, ENT_NOQUOTES, 'UTF-8'); ?></span>
<?php } ?>
</div>
</body>
</html>