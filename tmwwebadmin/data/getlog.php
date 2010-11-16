<?php

header('Content-Type: text/plain');

require_once '../functions.php';

$valid = $ladmin = FALSE;
@session_name('tmwbr_admin');
if (@session_start() && isset($_SESSION['auth']) && $_SESSION['auth'] &&
    isset($_SESSION['id']) && @ladmin_connect_auth($ladmin) === TRUE)
{
    $account = $ladmin->info_account($_SESSION['id']);
}
else
{
    echo 'LOGIN REQUIRED';
    exit;
}

if (is_array($account) && $account['gm level'] >= 20)
{
    require_once '../parsers.php';

    if (!isset($_POST['file']) || !is_string($_POST['file']))
    {
        echo 'UNSPECIFIED LOG FILE';
        exit;
    }
    $logfile = EA_GMLOG_DIR . "/gm.log.{$_POST['file']}";
    if (!is_readable($logfile))
    {
        echo 'CANNOT READ LOG FILE';
        exit;
    }
    if (isset($_POST['filter']) && is_string($_POST['filter']))
        $filter = $_POST['filter'];
    else
        $filter = 'all';
    switch ($filter)
    {
    case 'last100':
        if (LogParser::parseLogLast($logfile, 100, LogParser::VALUE_PRINT) === FALSE) echo 'OUTPUT ERROR';
        break;
    case 'last200':
        if (LogParser::parseLogLast($logfile, 200, LogParser::VALUE_PRINT) === FALSE) echo 'OUTPUT ERROR';
        break;
    case 'last1000':
        if (LogParser::parseLogLast($logfile, 1000, LogParser::VALUE_PRINT) === FALSE) echo 'OUTPUT ERROR';
        break;
    case 'datetime':
        $date = array();
        if (isset($_POST['filter_day']) && is_string($_POST['filter_day']))
            $date['mday'] = $_POST['filter_day'];
        if (isset($_POST['filter_hour']) && is_string($_POST['filter_hour']))
            $date['hours'] = $_POST['filter_hour'];
        if (isset($_POST['filter_minute']) && is_string($_POST['filter_minute']))
            $date['minutes'] = $_POST['filter_minute'];
        if (isset($_POST['filter_second']) && is_string($_POST['filter_second']))
            $date['seconds'] = $_POST['filter_second'];
        if (LogParser::parseLogDate($logfile, $date, LogParser::VALUE_PRINT) === FALSE) echo 'OUTPUT ERROR';
        break;
    case 'all':
        if (LogParser::parseLog($logfile, LogParser::VALUE_PRINT) === FALSE) echo 'OUTPUT ERROR';
    }
}

