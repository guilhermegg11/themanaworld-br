# Introdução #

Esta página fornece algumas informações sobre como utilizar nosso repositório SVN.
Se você puder observar em [Source > Browser](http://code.google.com/p/themanaworld-br/source/browse/) é possível navegar pela árvore de diretórios ou ainda pela url http://themanaworld-br.googlecode.com/svn. Mas como copiar todos os arquivos de uma só vez ?

Para copiar arquivos de um **Repositório Subversion** você precisará do programa **svn**. Ele está disponível em: [Getting Subversion](http://subversion.tigris.org/getting.html).

Instalado o programa é só fazer uma cópia de um dos diretórios disponíveis no repositório. Esta cópia é feita por linha de comando utilizando a opção **checkout**.

Esta tabela possui uma lista de diretórios e seus respectivos comandos.

| Projeto | Comando |
|:--------|:--------|
| localhost | svn checkout http://themanaworld-br.googlecode.com/svn/trunk tmw-br |
| updates | svn checkout http://themanaworld-br.googlecode.com/svn/updates updates |
| art | svn checkout http://themanaworld-br.googlecode.com/svn/art art |

Todos os comandos fazem uma cópia de **somente leitura**. Isso é porque "svn checkout http://..." só permite fazer cópias anônimas. Se você é um membro você pode usar seu login e senha (disponibilizada pelo Google) para fazer cópias que permitam alteração.

O comando seria algo como "svn checkout https://... --username **`<seu_nome_de_usuário_registrado>`**". O Google fornece mais informações em [Source > Checkout](http://code.google.com/p/themanaworld-br/source/checkout)