# Indrodução #

Esta página irá lhe orientar a como configurar e rodar nosso servidor eAthena em sua máquina.

Eu disse "nosso servidor eAthena", mas o termo correto seria "o servidor eAthena com nossas customizações". O eAthena é um servidor para jogos do tipo RPG online. Inicialmente ele era utilizado apenas pelo jogo Ragnarok, mas hoje é utilizado pelo jogo The Mana World e também em nosso servidor !

Os desenvolvedores do The Mana World criaram um novo servidor chamado tmwServ. Este será o novo servidor do The Mana World. Por enquanto ele não está totalmente pronto para uso, mas assim que estiver ele será utilizado pelo TMW e também pelo TMW-BR.

# Configuração #

É relativamente simples configurar e rodar nosso servidor eAthena. Ele inclusive já vem configurado para rodar localmente (IP 127.0.0.1).

| **Obs:** É recomendado o uso do sistema operacional **Linux**, pois iremos fazer uso de recursos não existentes no Windows. |
|:------------------------------------------------------------------------------------------------------------------------------|

Primeiro baixe o servidor do repositório:

```
svn checkout http://themanaworld-br.googlecode.com/svn/eathena-data eathena-data
```

No diretório **eathena-data/conf** existem diversos arquivos com a terminação **.example** em seu nome. Esta terminação deve ser retirada. Para facilitar o serviço use os comandos:

```
mv atcommand_local.conf.example atcommand_local.conf
mv battle_local.conf.example battle_local.conf
mv char_local.conf.example char_local.conf
mv eathena-monitor.conf.example eathena-monitor.conf
mv gm_account.txt.example gm_account.txt
mv help.txt.example help.txt
mv ladmin_local.conf.example ladmin_local.conf
mv login_local.conf.example login_local.conf
mv map_local.conf.example map_local.conf
mv motd.txt.example motd.txt
```

Destes arquivos, **char\_local.conf** e **map\_local.conf** necessitam de configuração caso você queira rodar o servidor em rede. Para isso basta trocar o IP **127.0.0.1** por seu IP. Para rodar localmente (apenas na sua máquina) não é necessário fazer nada.

No diretório **eathena-data/save** existe um outro arquivo com a terminação **.example** no nome. Este arquivo também precisa ser renomeado.

# Compilação #

Os binário do servidor não estão disponíveis com o **eathena-data** do repositório. Eles não estão disponíveis em lugar algum. E só podem ser obtidos através da compilação do eAthena.

Baixe o [eAthena](http://themanaworld-br.googlecode.com/svn/eathena/eathena.zip) e o extráia para um diretório chamado **eathena**. Em seguida compile o projeto com o comando make.

```
make
```

Isto irá gerar alguns binários para o diretório eathena. Copie os binário **char-server**, **login-server** e **map-server** para o diretório **eathena-data** que estávamos trabalhando agora pouco.

Agora, no diretório **eathena-data**, gere o binário **eathena-monitor** com o comando:

```
gcc -o eathena-monitor eathena-monitor.c
```

Agora sim temos todos os binário necessários.

# Execução #

Antes de executar o servidor você precisa se certificar que existe um link simbólico (nomeado como **tmwserver**) em seu diretório **HOME** apontando para o diretório do seu servidor. É isso ou configurar o servidor para rodar no endereço real !

Para criar o link simbólico use o comando:

```
ln -s /caminho_completo/eathena-data/ tmwserver
```

**caminho\_completo** deve ser trocado pelo endereço do seu servidor.

Agora sim... vamos rodar o servidor !!!

```
cd ~/tmwserver
./eathena.sh start
```

Caso tudo corra bem a saída será:

```
Starting eathena monitor...
Starting:
* interval: 5 s
* workdir: /home/diogorbg/tmwserver
* login_server: /home/diogorbg/tmwserver/login-server
* map_server: /home/diogorbg/tmwserver/map-server
* char_server: /home/diogorbg/tmwserver/char-server
```

Agora o servidor está rodando em sua máquina. O próximo passo é abrir o cliente The Mana World e se conectar com o IP **127.0.0.1** (ou o IP de rede da máquina em que se encontra o servidor) para jogar. É claro que você deve criar uma conta no novo servidor primeiro !

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/login-local.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/login-local.png)

Para parar a execução do servidor use **stop** e para reiniciar o servidor use **restart**:

```
./eathena.sh stop
```

Ainda não há muito para ser visto ! Estamos em fase de teste.