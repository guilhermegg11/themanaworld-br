<?php

@session_name('tmwbr_admin');
if (!@session_start() || !isset($_SESSION['auth']) ||
    !$_SESSION['auth'] || !@session_destroy())
{
    header('Location: login.php');
    exit;
}

echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>The Mana World Brasil: Logoff do sistema</title>
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
</head>
<body>
<div class="ui-widget-content">Você foi deslogado do sistema</div>
</body>
</html>