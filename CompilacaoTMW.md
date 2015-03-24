## Introdução ##

Mesmo para quem utiliza distribuições servidas de repositório com pacotes de binários pré-compilados... ainda é bom saber como é que se compila um programa. Principalmente se você quer utilizar a ultima versão do **cliente The Mana World**.

A seguir, darei algumas dicas para quem utiliza a distribuição **Ubuntu**. Mas pra quem utiliza o **Debian** ou o **Fedora** é praticamente a mesma coisa... é só resolver os problemas de dependência instalando os pacotes dev (desenvolvimento). Só os usuários do **Slackware** terão mais dificuldades, já que estes terão que compilar as dependências uma a uma.

## Obtendo a ultima versão do cliente The Mana World ##

O procedimento é simples... na [área de Downloads](http://themanaworld.org/downloads.php) do site http://themanaworld.org em **Official packages:** você baixa o código fonte do cliente The Mana World... atualmente: **Source code 0.0.29.1**

Após baixar o arquivo **tmw-0.0.29.1.tar.gz** você deverá extraí-lo. Você pode extraí-lo para qualquer diretório... **/home/seu\_usuário** está ótimo. Para extraí-lo, no menu do botão direito clique em **Extrair aqui**, **Extrair para...**. Ou abra o arquivo com dois cliques e, no programa, clique no botão extrair... escolha o diretório e extráia.

O próximo procedimento é a compilação.

## Resolvendo dependências ##

Os comandos para compilar são **./configure**, **make** e **make install**... Mas não vá com tanta sede ao pote ! Não antes de ler o arquivo **INSTALL**. Ele sempre trás informações valiosíssimas.

Por exemplo... a lista de dependências que devem ser solucionadas antes da compilação. O comando **./configure** irá alertá-lo sobre tais dependências... mas nós não precisamos fazer isso com base na tentativa e erro !

Tudo se simplifica quando temos a lista de todas as dependências... e no arquivo **INSTALL** temos:

```
* SDL               http://www.libsdl.org/
* SDL_mixer         http://www.libsdl.org/projects/SDL_mixer/
* SDL_image         http://www.libsdl.org/projects/SDL_image/
* SDL_net           http://www.libsdl.org/projects/SDL_net/
* SDL_ttf           http://www.libsdl.org/projects/SDL_ttf/
* ENet 1.2          http://enet.bespin.org/
* Guichan 0.8.x     http://guichan.sourceforge.net/
* libxml2           http://www.xmlsoft.org/
* physfs 1.x        http://icculus.org/physfs/
* zlib 1.2.x        http://www.gzip.org/zlib/
* libcurl           http://curl.haxx.se/libcurl/
```

Se você utiliza a distribuição **Ubuntu** você pode resolver todas estas dependências com um único comando:

```
sudo apt-get install libsdl1.2-dev libsdl-mixer1.2-dev libsdl-image1.2-dev \
libsdl-net1.2-dev libsdl-ttf2.0-dev libenet1-dev libguichan-dev libxml2-dev \
libphysfs-dev zlib1g-dev libcurl4-openssl-dev
```

Caso alguma destas dependências não possa ser instalada por este comando recorra a seu Gerenciador de pacotes. No **Ubuntu** temos o **Synaptic** (**Menu > Sistema > Administração > Gerenciador de pacotes Synaptic**). Instale os pacotes dev... exemplo: **libsdl`*`-dev**.

Agora sim... vamos à compilação.

## Compilando e instalando ##

Para compilar abra o terminal de comandos, acesse o diretório onde você extraiu e compile utilizando os comandos **./configure**, **make** e **make install**.

Dica para usuários do Ubuntu. Você provavelmente irá precisar de um outro pacote para usar os comandos ./configure e make. Se trata de outras dependências para compilação.

```
sudo apt-get install build-essential
```

Acessando o diretório:

```
cd /home/seu_usuário/tmw-0.0.29.1
```

Compilando e instalando:

```
./configure
make
sudo make install
```

O make costuma demorar ! Mas feito isso você terá a ultima versão do jogo pronta para usar, bastando executá-la com o comando **tmw**, ou seja, pressione **Alt+F2** e digite **tmw** na caixa de texto... só isso. O atalho do antigo TMW também poderá ser utilizado.

## Solucionando problemas ##

E se a compilação falhar ?! Isso geralmente acontece quando você não tem a versão correta de uma determinada dependência. O comando **./configure** irá alertá-lo sobre isso. É comum versões antigas (Ubuntu 8.04 por exemplo) terem este tipo de problema... mas tudo pode ser resolvido instalando a dependência por seu código fonte.

Caso você tenha problemas entre em contato conosco em [nosso fórum](http://www.themanaworld.com.br). Use a sessão [Suportes Operacionais](http://www.themanaworld.com.br/viewforum.php?f=26&sid=d79886de37a5b2eae11f88835c0b1e1a).