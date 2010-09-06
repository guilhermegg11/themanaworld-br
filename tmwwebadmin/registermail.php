--tmw-br-<?php echo $random_hash; ?>

Content-Type: text/plain; charset="utf-8"
Content-Transfer-Encoding: 8bit

Você requisitou um cadastro de conta no servidor do The Mana World Brasil com
este endereço de e-mail, para confirmar seu cadastro acesse a seguinte URL:
http://<?php echo SERVER_DOMAIN; ?>/confirmregister.php?login=<?php
echo rawurlencode($_POST['login']); ?>&verifycode=<?php
echo rawurlencode($code); ?>

Você poderá cadastrar sua senha de acesso e se logar no servidor do
The Mana World Brasil assim que confirmar seu cadastro acessando a URL indicada

--tmw-br-<?php echo $random_hash; ?>

Content-Type: text/html; charset="utf-8"
Content-Transfer-Encoding: 8bit

<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style type="text/css">
    /* <![CDATA[ */
strong {
    color: #000066;
}

a {
    color: #880000;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
    /* ]]> */
    </style>
</head>
<body>
<div>Você requisitou um cadastro de conta no servidor do The Mana World Brasil
com este endereço de e-mail, para confirmar seu cadastro acesse a seguinte URL:
<a href="http://<?php echo htmlspecialchars(SERVER_DOMAIN, ENT_COMPAT, 'UTF-8'); ?>/confirmregister.php?login=<?php
echo htmlspecialchars($_POST['login'], ENT_COMPAT, 'UTF-8'); ?>&verifycode=<?php
echo htmlspecialchars($code, ENT_COMPAT, 'UTF-8'); ?>">http://<?php echo htmlspecialchars(SERVER_DOMAIN, ENT_NOQUOTES, 'UTF-8'); ?>/confirmregister.php?login=<?php
echo htmlspecialchars($_POST['login'], ENT_NOQUOTES, 'UTF-8'); ?>&verifycode=<?php
echo htmlspecialchars($code, ENT_NOQUOTES, 'UTF-8'); ?></a></div>
<div>Você poderá cadastrar sua senha de acesso e se logar no servidor do
The Mana World Brasil assim que confirmar seu cadastro acessando a
URL indicada</div>
</body>
</html>

--tmw-br-<?php echo $random_hash; ?>--