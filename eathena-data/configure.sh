#!/bin/bash

# Scripts para configurar o localhost
# @autor Diogo_RBG - http://diogorbg.blogspot.com

echo ".:: Renomeando arquivos do diretório conf ::.";
mv -uv conf/atcommand_local.conf.example conf/atcommand_local.conf
mv -uv conf/battle_local.conf.example conf/battle_local.conf
mv -uv conf/char_local.conf.example conf/char_local.conf
mv -uv conf/eathena-monitor.conf.example conf/eathena-monitor.conf
mv -uv conf/gm_account.txt.example conf/gm_account.txt
mv -uv conf/help.txt.example conf/help.txt
mv -uv conf/ladmin_local.conf.example conf/ladmin_local.conf
mv -uv conf/login_local.conf.example conf/login_local.conf
mv -uv conf/map_local.conf.example conf/map_local.conf
mv -uv conf/motd.txt.example conf/motd.txt
echo "";

echo ".:: Renomeando arquivo do diretório save ::.";
mv -uv save/account.txt.example save/account.txt
echo "";

echo ".:: Criando diretório de log ::.";
mkdir log
echo "";

echo ".:: Compilando eathena-monitor ::.";
echo "gcc -o eathena-monitor eathena-monitor.c"
gcc -o eathena-monitor eathena-monitor.c
echo "";

echo ".:: Criando link simbólico em $HOME ::.";
./mkdir.sh
echo "";

echo ".:: Instruções ::.";
echo "Se tudo foi configurado com sucesso basta você rodar o servidor com o comando:";
echo "./eathena.sh start";
echo "";
echo "Ou se preferir execute cada servidor separadamente, pois você verá as mensagens de erro dos mesmos:";
echo "./login-server";
echo "./char-server";
echo "./map-server";
