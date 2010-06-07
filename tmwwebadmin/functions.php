<?php

/**
 * Create new eAthenaLAdmin instance, then connect and autheticate it
 * 
 * @param eAthenaLAdmin $ladmin You can pass an eAthenaLAdmin instance to this
 *                              parameter, in this case if it's not conected or
 *                              authenticated this function will do it. If
 *                              parameter is not an eAthenaLAdmin instance,
 *                              then new created eAthenaLAdmin instance will be
 *                              assigned to this variable
 *
 * @return string|boolean If an error happens a string describe the error will
 * 						  be returned, otherwise return TRUE
 * */
function ladmin_connect_auth(&$ladmin = FALSE)
{
    if (!is_a($ladmin, 'eAthenaLAdmin'))
    {
        require_once '../ladmin.php';

        $ladmin = new eAthenaLAdmin();
    }
    if (!$ladmin->is_connected() || !$ladmin->is_authenticated())
    {
        require_once '../defs.php';

        if (!$ladmin->is_connected() && !$ladmin->connect(LADMIN_HOST, LADMIN_PORT))
            return 'Não foi possivel se conectar ao login-server do servidor eAthena';
        if (!$ladmin->is_authenticated() && !$ladmin->authenticate(LADMIN_PASS))
            return 'Não foi possivel se autenticar com usuário administrador';
    }
    return TRUE;
}

function session_setup(&$ladmin = FALSE)
{
    if (!is_string($_POST['login']) || !strlen($_POST['login']))
        return FALSE;

    $auth = ladmin_connect_auth($ladmin);
    if ($auth !== TRUE)
        return (is_string($auth) ? $auth : '');

    $id = $ladmin->id_account($_POST['login']);
    if (!is_int($id))
        return FALSE;
    $_SESSION['id'] = $id;
    $_SESSION['name'] = $_POST['login'];
    return TRUE;
}

function validate_login(&$ladmin = FALSE)
{
    if (!is_string($_POST['login']) || !strlen($_POST['login']) ||
        !is_string($_POST['password']) || !strlen($_POST['password']))
        return FALSE;
    
    $auth = ladmin_connect_auth($ladmin);
    if ($auth !== TRUE)
        return (is_string($auth) ? $auth : '');

    if (!$ladmin->verify_account_name($_POST['login']) ||
        !$ladmin->verify_password($_POST['password']) ||
        !$ladmin->check_account($_POST['login'], $_POST['password']))
        return 'Login ou senha incorretos';
    return TRUE;
}

function javascript_string_encode($input_str)
{
    $ret = '"';
    $input_len = strlen($input_str);
    for ($idx = 0; $idx < $input_len; $idx++)
    {
        $input_chr = $input_str[$idx];
        $input_ord = ord($input_chr);
        switch ($input_ord)
        {
        case 0:     $input_chr = '\0';      break;
        case 7:     $input_chr = '\a';      break;
        case 8:     $input_chr = '\b';      break;
        case 9:     $input_chr = '\t';      break;
        case 10:    $input_chr = '\n';      break;
        case 11:    $input_chr = '\v';      break;
        case 12:    $input_chr = '\f';      break;
        case 13:    $input_chr = '\r';      break;
        case 34:    $input_chr = '\"';      break;
        case 91:    $input_chr = '\[';      break;
        case 92:    $input_chr = '\\\\';    break;
        case 93:    $input_chr = '\]';      break;
        default:
            if ($input_ord < 32 || ($input_ord > 126 && $input_ord < 256))
                $input_chr = sprintf('\\x%02X', $input_ord);
            else if ($input_ord >= 256)
                $input_chr = sprintf('\\u%04X', $input_ord);
        }
        $ret .= $input_chr;
    }
    $ret .= '"';
    return $ret;
}

?>