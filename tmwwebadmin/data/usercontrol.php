<?php

require_once '../functions.php';

$valid = $ladmin = FALSE;
@session_name('tmwbr_admin');
if (@session_start() && isset($_SESSION['auth']) && $_SESSION['auth'] &&
    isset($_SESSION['id']) && @ladmin_connect_auth($ladmin) === TRUE)
{
    if (is_string($_POST['email']))
    {
        require_once '../defs.php';
        require_once '../db.php';
        require_once 'Mail.php';

        $sqlconn = new TMW_MySQL(MYSQL_HOST, MYSQL_PORT, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
        if (!$sqlconn || $sqlconn->connect_errno)
            $valid = 'Erro ao conectar no servidor MySQL';
        else if (!$ladmin->verify_email($_POST['email']))
            $valid = 'O e-mail informado não parece ser um e-mail válido';
        else if (email_exists($_POST['email'], $sqlconn))
            $valid = 'Algum outro player já tem cadastro neste servidor usando este e-mail. Por favor escolha outro';
        else
        {
            if ($sqlconn->delete_email_code($_SESSION['name']) === FALSE ||
                !($code=$sqlconn->set_email_code($_SESSION['name'], $_POST['email'])))
                $valid = 'Erro na consulta MySQL';
            else
            {
                $random_hash = md5(date('r', time()));
                $to = $_POST['email'];
                $headers = array(
                    'From' => EMAIL_FROM,
                	'To' => $_POST['email'],
                    'Subject' => '[The Mana World Brasil] Cadastro de e-mail',
                	'Content-Type' => "multipart/alternative; boundary=\"tmw-br-$random_hash\""
    	        );
                ob_start();
                include '../confirmemailmail.php';
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
        if ($valid === FALSE) $valid = TRUE;
    }
    $account = $ladmin->info_account($_SESSION['id']);
}
else
{
    header('Location: login.php');
    exit;
}

echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>The Mana World Brasil: Painel de Controle do Usuário</title>
    <link type="text/css" rel="stylesheet" href="jquery-ui-1.7.3.custom.css"/>
    <script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="functions.js"></script>
    <script language="javascript">
    /* <![CDATA[ */

$(document).ready(function() {
    $("#modify_email").one('click', function() {
        var tr = $("#email_field > td.field");
        if (!tr.get(0)) return;
        tr.empty();
        var form = document.createElement("form");
        $(form).attr({action: "usercontrol.php", method: "post"}).submit(function() {
            return verify_email("form :input[name=email]");
        });
        var input = $('<input type="text"/>').attr({
            name: "email",
            size: "25",
            maxlength: "39"
        }).appendTo(form);
<?php if ($account['email'] != 'a@a.com' && strlen($account['email']) > 4) { ?>
        input.val(<?php echo javascript_string_encode($account['email']); ?>);
<?php } ?>
        var div = form.appendChild(document.createElement("div"));
        input = $('<input type="submit"/>').appendTo(div).val("Cadastrar Novo E-Mail");
        tr.append(form);
    });
});

    /* ]]> */
    </script>
    <style type="text/css">
    /* <![CDATA[ */
table.ui-widget {
    width: 600px;
    border-collapse: collapse;
    margin-left:auto;
    margin-right:auto;
    margin-top: 100px;
}

div.ui-widget-content {
    width: 600px;
    margin-left:auto;
    margin-right:auto;
    margin-top: 200px;
}

.label {
    text-align: right;
}

.field {
    font-weight: normal;
    padding-left: 6px;
    color: #000060;
}

.logoff {
    float: right;
    font-weight: normal;
    color: #2f2e32;
    text-decoration: none;
}

.logoff:hover {
    text-decoration: underline;
}

#modify_email {
    color: #212121;
    cursor: default;
    width: 90px;
}

#modify_email:hover {
    color: #2f2e32;
    text-decoration: underline;
}
    /* ]]> */
    </style>
</head>
<body>
<a href="logoff.php" class="logoff ui-state-activer">Deslogar-se</a>
<?php if (is_array($account)) { 
if (is_string($valid)) { ?>
<div align="center" class="ui-state-error"><?php
    echo htmlspecialchars($valid, ENT_NOQUOTES, 'UTF-8'); ?></div>
<?php } else if ($valid === TRUE) { ?>
<div align="center">E-mail de confirmação enviado</div>
<?php } ?>
<form action="usercontrol.php" method="post">
<table id="userinfo" class="ui-widget">
    <tbody class="ui-widget-content">
        <tr>
            <td class="label">Login:</td>
            <td class="field"><?php
                echo htmlspecialchars($account['name'], ENT_NOQUOTES, 'UTF-8'); 
            ?></td>
        </tr>
        <tr>
            <td class="label">Nivel de GM:</td>
            <td class="field"><?php
                if (!$account['gm level'])
                    echo 'NÃO-GM';
                else
                    echo htmlspecialchars($account['gm level'], ENT_NOQUOTES, 'UTF-8'); 
            ?></td>
        </tr>
        <tr>
            <td class="label">Logins Efetuados:</td>
            <td class="field"><?php
                echo htmlspecialchars($account['count'], ENT_NOQUOTES, 'UTF-8'); 
            ?></td>
        </tr>
        <tr>
            <td class="label">Ultimo Login:</td>
            <td class="field"><?php
                if ($account['last login'] < 1)
                    echo 'Nunca Logado';
                else
                    echo htmlspecialchars(sprintf('%02d/%02d/%04d %02d:%02d:%02d',
                                                  $account['last login']['tm_mday'],
                                                  $account['last login']['tm_mon']+1,
                                                  $account['last login']['tm_year']+1900,
                                                  $account['last login']['tm_hour'],
                                                  $account['last login']['tm_min'],
                                                  $account['last login']['tm_sec']),
                                          ENT_NOQUOTES, 'UTF-8'); 
            ?></td>
        </tr>
        <tr>
            <td class="label">Status da conta:</td>
            <td class="field"><?php
                switch ($account['status'])
                {
                case 0:     echo 'Conta OK';                                    break;
                case 1:     echo 'ID não resgistrado';                          break;
                case 2:     echo 'Senha incorreta';                             break;
                case 3:     echo 'Conta expirada';                              break;
                case 4:     echo 'Rejeitado pelo servidor';                     break;
                case 5:     echo 'Bloqueado por algum GM';                      break;
                case 6:     echo 'Sua versão do jogo não corresponde a ultima'; break;
                case 7:     echo htmlspecialchars($account['error message'], ENT_NOQUOTES, 'UTF-8'); break;
                case 8:     echo 'Servidor lotado';                             break;
                case 9:     echo 'Erro não especificado';                       break;
                case 10:    echo 'Esta conta foi totalmente excluida';          break;
                default:    echo $account['status'];
                }
            ?></td>
        </tr>
        <tr id="email_field">
            <td class="label">E-Mail:</td>
            <td class="field"><?php
                if ($account['email'] == 'a@a.com' || strlen($account['email']) < 5)
                    echo 'Nenhum e-mail cadastrado';
                else
                    echo htmlspecialchars($account['email'], ENT_NOQUOTES, 'UTF-8');
            ?><div id="modify_email">Modificar</div>
            </td>
        </tr>
        <tr>
            <td class="label">Validade da conta:</td>
            <td class="field"><?php
                if ($account['validate'] < 1)
                    echo 'Sem validade definida';
                else
                    echo htmlspecialchars(sprintf('%02d/%02d/%04d %02d:%02d:%02d',
                                                  $account['validate']['tm_mday'],
                                                  $account['validate']['tm_mon']+1,
                                                  $account['validate']['tm_year']+1900,
                                                  $account['validate']['tm_hour'],
                                                  $account['validate']['tm_min'],
                                                  $account['validate']['tm_sec']),
                                          ENT_NOQUOTES, 'UTF-8'); 
                ?></td>
        </tr>
        <tr>
            <td class="label">Banimento:</td>
            <td class="field"><?php
                if ($account['ban date'] < 1)
                    echo 'Não banido';
                else
                    echo htmlspecialchars(sprintf('Até %02d/%02d/%04d %02d:%02d:%02d',
                                                  $account['ban date']['tm_mday'],
                                                  $account['ban date']['tm_mon']+1,
                                                  $account['ban date']['tm_year']+1900,
                                                  $account['ban date']['tm_hour'],
                                                  $account['ban date']['tm_min'],
                                                  $account['ban date']['tm_sec']),
                                          ENT_NOQUOTES, 'UTF-8'); 
                ?></td>
        </tr>
    </tbody>
</table>
</form>
<?php } else { ?>
<div class="ui-widget-content">
    <span class="ui-state-error-text">Conta de usuário não encontrada no servidor</span>
</div>
<?php } ?>
</body>
</html>