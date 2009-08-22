#!/bin/bash

echo ".:: Renomeando arquivos do diretório conf ::.";
./conf/configure.sh
echo "";

echo ".:: Renomeando arquivo do diretório save ::.";
./save/configure.sh
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
