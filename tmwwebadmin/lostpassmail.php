--tmw-br-<?php echo $random_hash; ?>

Content-Type: text/plain; charset="utf-8"
Content-Transfer-Encoding: 8bit

Você requisitou que sua senha de acesso ao servidor The Mana World Brasil fosse
resetada. Acesse o link http://<?php echo SERVER_DOMAIN; ?>/resetpassword.php?login=<?php 
echo rawurlencode($_POST['login']); ?>&verifycode=<?php echo rawurlencode($code); ?>


Caso não tenha requisitado o reset de senha é provavel que outra pessoa tenha
requisitado em seu nome, neste caso apenas ignore esta mensagem.

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
<div>Você requisitou que sua senha de acesso ao servidor The Mana World Brasil fosse
resetada. Acesse o link <a href="http://<?php
echo htmlspecialchars(SERVER_DOMAIN, ENT_COMPAT, 'UTF-8'); ?>/resetpassword.php?login=<?php 
echo htmlspecialchars(rawurlencode($_POST['login']), ENT_COMPAT, 'UTF-8'); ?>&verifycode=<?php
echo htmlspecialchars(rawurlencode($code), ENT_COMPAT, 'UTF-8'); ?>">
http://<?php
echo htmlspecialchars(SERVER_DOMAIN, ENT_NOQUOTES, 'UTF-8'); ?>/resetpassword.php?login=<?php 
echo htmlspecialchars(rawurlencode($_POST['login']), ENT_NOQUOTES, 'UTF-8'); ?>&amp;verifycode=<?php
echo htmlspecialchars(rawurlencode($code), ENT_NOQUOTES, 'UTF-8'); ?></a></div>
<div>Caso não tenha requisitado o reset de senha é provavel que outra pessoa tenha
requisitado em seu nome, neste caso apenas ignore esta mensagem.</div>
</body>
</html>

--tmw-br-<?php echo $random_hash; ?>--