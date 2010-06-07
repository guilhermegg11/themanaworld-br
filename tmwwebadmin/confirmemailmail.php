--tmw-br-<?php echo $random_hash; ?>

Content-Type: text/plain; charset="utf-8"
Content-Transfer-Encoding: 8bit

Você requisitou que seu e-mail de cadastro fosse alterado no servidor do
The Mana World Brasil, para confirmar a alteração acesse a seguinte URL:
http://<?php echo SERVER_DOMAIN; ?>/confirmemail.php?login=<?php
echo rawurlencode($_SESSION['name']); ?>&verifycode=<?php
echo rawurlencode($code); ?>

O novo e-mail só será incluido no sistema assim que você confirma-lo acessando
a URL indicada

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
<div>Você requisitou que seu e-mail de cadastro fosse alterado no servidor do
The Mana World Brasil, para confirmar a alteração click neste link:
<a href="http://<?php echo htmlspecialchars(SERVER_DOMAIN, ENT_COMPAT, 'UTF-8'); ?>/confirmemail.php?login=<?php
echo htmlspecialchars($_SESSION['name'], ENT_COMPAT, 'UTF-8'); ?>&verifycode=<?php
echo htmlspecialchars($code, ENT_COMPAT, 'UTF-8'); ?>">http://<?php echo htmlspecialchars(SERVER_DOMAIN, ENT_NOQUOTES, 'UTF-8'); ?>/confirmemail.php?login=<?php
echo htmlspecialchars($_SESSION['name'], ENT_NOQUOTES, 'UTF-8'); ?>&verifycode=<?php
echo htmlspecialchars($code, ENT_NOQUOTES, 'UTF-8'); ?></a></div>
</body>
</html>

--tmw-br-<?php echo $random_hash; ?>--