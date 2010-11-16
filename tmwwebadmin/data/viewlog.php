<?php

require_once '../functions.php';

$valid = $ladmin = FALSE;
@session_name('tmwbr_admin');
if (@session_start() && isset($_SESSION['auth']) && $_SESSION['auth'] &&
    isset($_SESSION['id']) && @ladmin_connect_auth($ladmin) === TRUE)
{
    $account = $ladmin->info_account($_SESSION['id']);
    $logfiles = array();
    foreach (glob(EA_GMLOG_DIR . '/gm.log.????-??') as $item)
        if (preg_match('/gm\.log\.([1-9][0-9]{3})-(0?[1-9]|1[0-2])$/', $item, $match))
            $logfiles[] = "$match[1]-$match[2]";
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
    <title>The Mana World Brasil: Log dos GMs</title>
    <link type="text/css" rel="stylesheet" href="jquery-ui-1.7.3.custom.css"/>
    <script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script language="javascript">
    /* <![CDATA[ */
$(document).ready(function() {
    $("#filter").change(function() {
        if ($(this).val() == "datetime")
        {
            $("#searchlog tr.datetime_choose").show();
            $("#searchlog tr.datetime_choose :input").removeAttr("disabled");
        }
        else
        {
            $("#searchlog tr.datetime_choose").hide();
            $("#searchlog tr.datetime_choose :input").attr("disabled", "disabled");
        }
    });

    $("form").submit(function() {
        $.ajax({
            url: this.action,
            dataType: "text",
            type: "POST",
            cache: false,
            data: $(this).serializeArray(),
            success: function(txtdata, status, xmlhttp) {
                $("#result").empty().append(document.createTextNode(txtdata));
            }
        });
        return false;
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

.submit {
    text-align: center;
}

.logoff {
    float: right;
    padding-left: 25px;
    padding-right: 25px;
    font-weight: normal;
    color: #2f2e32;
    text-decoration: none;
}

.logoff:hover {
    text-decoration: underline;
}
    /* ]]> */
    </style>
</head>
<body>
<a href="logoff.php" class="logoff ui-state-activer">Deslogar-se</a>
<a href="usercontrol.php" class="logoff ui-state-activer">Painel de Controle</a>
<?php if (is_array($account) && $account['gm level'] >= 20) { ?>
<form action="getlog.php" method="post">
<table id="searchlog" class="ui-widget">
    <tbody class="ui-widget-content">
        <tr>
            <td class="label">Data do LOG:</td>
            <td class="field">
                <select name="file"><?php foreach ($logfiles as $item) { ?>
                <option value="<?php echo htmlspecialchars($item, ENT_COMPAT, 'UTF-8'); ?>"><?php
                echo htmlspecialchars($item, ENT_NOQUOTES, 'UTF-8'); ?></option>
                <?php } ?></select>
            </td>
        </tr>
        <tr>
            <td class="label">Filtro:</td>
            <td class="field">
                <select id="filter" name="filter">
                <option value="last100">Ultimas 100 Linhas</option>
                <option value="last200">Ultimas 200 Linhas</option>
                <option value="last1000">Ultimas 1000 Linhas</option>
                <option value="datetime">Filtro por Dia/Horario</option>
                <option value="all">Todos os logs</option>
                </select>
            </td>
        </tr>
        <tr class="datetime_choose" style="display: none;">
            <td class="label">Dia:</td>
            <td class="field">
            <select name="filter_day">
            <option value="-">TODOS OS DIAS</option><?php for ($i = 1; $i <= 31; $i++) { ?>
            <option value="<?php echo $i; ?>"><?php echo sprintf('%02d', $i); ?></option>
            <?php } ?></select>
            </td>
        </tr>
        <tr class="datetime_choose" style="display: none;">
            <td class="label">Hora:</td>
            <td class="field">
            <select name="filter_hour">
            <option value="-">TODAS AS HORAS</option><?php for ($i = 0; $i <= 23; $i++) { ?>
            <option value="<?php echo $i; ?>"><?php echo sprintf('%02d', $i); ?></option>
            <?php } ?></select>
            </td>
        </tr>
        <tr class="datetime_choose" style="display: none;">
            <td class="label">Minuto:</td>
            <td class="field">
            <select name="filter_minute">
            <option value="-">TODOS OS MINUTOS</option><?php for ($i = 0; $i <= 59; $i++) { ?>
            <option value="<?php echo $i; ?>"><?php echo sprintf('%02d', $i); ?></option>
            <?php } ?></select>
            </td>
        </tr>
        <tr class="datetime_choose" style="display: none;">
            <td class="label">Segundo:</td>
            <td class="field">
            <select name="filter_second">
            <option value="-">TODOS OS SEGUNDOS</option><?php for ($i = 0; $i <= 59; $i++) { ?>
            <option value="<?php echo $i; ?>"><?php echo sprintf('%02d', $i); ?></option>
            <?php } ?></select>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="submit">
            <input type="submit" value="Ver Logs"/>
            </td>
        </tr>
    </tbody>
</table>
<pre id="result"></pre>
</form>
<?php } else { ?>
<div class="ui-widget-content">
    <span class="ui-state-error-text">Você não tem autorização para visualizar os logs de GM</span>
</div>
<?php } ?>