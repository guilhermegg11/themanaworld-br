# Introducão #

Este documento é destinado a aqueles que foram convidados a contribuir diretamente com o jogo. E que vão passar a enviar modificações de arquivos diretamente ao nosso repositório de arquivos. Este é um grupo seleto por vários motivos:

  * Trabalhos aqui enviados devem estar lives de bugs, pois a partir do momento que são enviados vão passar a fazer parte do projeto. Por isso os membros que aqui contribuem devem estar cientes de que devem ser feitos vários testes para qualquer alteração feita.
  * Devem ter um profundo conhecimento do projeto e ajudar a desenvolver melhorias no jogo respeitando prioridades, necessidades, normas, convenções, objetos gerais e tarefas discutidas em reuniões.
  * E seleto também pela dificuldade em coordenar várias mentes criativas. Onde a qualquer momento duas ou mais pessoas pode estar trabalhando em um mesmo arquivo. Causando inconsistência nos arquivos do repositório e perdas de trabalhos.
  * Tais membros devem ter uma boa comunicação se manter em contato constante, pois desenvolver pela internet requer o domínio de grupos de discussão, e-mail e demais ferramentes de comunicação.

Mas isso não significa que qualquer pessoa não possa contribuir com o jogo, para isso basta enviar suas sugestões, imagens, esboços e histórias para [nosso fórum](http://forums.themanaworld.com.br/).

# Fazendo Checkout #

Para fazer checkout dos dados do repositório habilitado para edição, primeiro é preciso ter uma conta do Google (um GMail por exemplo) e em seguida gerar sua senha em [source/checkout](http://code.google.com/p/themanaworld-br/source/checkout) clicando no link [googlecode.com password](https://code.google.com/hosting/settings).

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo01.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo01.png)

Feito isso basta fazer um (ou vários) checkout do repositório usando o programa svn e o protocolo http\*s**:**

```
svn checkout https://themanaworld-br.googlecode.com/svn/eathena-data eathena-data --username <nome_usuário>
svn checkout https://themanaworld-br.googlecode.com/svn/tmwdata tmwdata --username <nome_usuário>
```

Feito o checkout você já estará apto a fazer alterações em arquivos e enviá-las diretamente ao servidor através de um commit:

```
svn commit <arquivo_modificado> -m "<uma mensagem descrevendo as modificações>"
```

Mas não se preocupe com os comandos... se você estiver utilizando uma ferramenta gráfica isso fica transparente.

# Apresentando o RapidSVN #

Podem até existir ferramentas melhores, mas o RapidSVN se destaca por ser uma ferramente muito fácil de de configurar e utilizar.

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/rapidSVN.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/rapidSVN.png)

Ele será o seu navegador de arquivos e irá destacar o status de cada arquivo. Além de te dar vários comandos:
  * **add** - Adiciona um arquivo para versionamento. Apenas arquivos versionados serão enviados ao repositório.
  * **delete** - remove um arquivo versionado.
  * **update** - irá trazer as novas modificações de arquivos no repositório. Se por acaso existir uma modificação em um mesmo arquivo que você estiver editando, e o subversion não puder tratar estas modificações, irá ocorrer um conflito e então serão gerados mais 3 arquivos: arquivo.mine (com as modificações mescadas), arquivo.versaoAntiga e arquivo.versaoNova... necessitando de correções manuais.

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo4.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo4.png)

  * **commit** - onde você poderá escolher um ou mais arquivos para serem sincronizados no repositório. Aqui é interessante descrever as alterações feitas em detalhes.
  * **revert** - aqui vc ignora as suas modificações e o sobrescreve com a versão do repositório. Isso também irá apagar os 3 arquivos gerados no update conflitoso.
  * **info** - para ter detalhes da localização exata do arquivo... entre outras coisas.
  * **log** - para ter detalhes de todos os logs de alteração do arquivo.
  * **diff** - para fazer uma comparação entre duas versões diferentes de um mesmo arquivo. Para funcionar é preciso configuração.

# Configurando o RapidSVN #

Legal, mas como eu faço para configurar o RapidSVN?

Primeiro vamos adicionar nossas cópias de trabalho a ele. Clique em **Bookmarks** com o direito e depois em **Add Existing working Copy...**.

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo02.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo02.png)

Em seguida basta selecionar o diretório para onde acabamos de fazer checkout e clica em ok.

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo03.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo03.png)

Agora é só adicionar os demais. Clicando no botão **refresh** o programa ira comparar seu arquivos aos do repositório. É muito simples.

E seguida devemos configurar os programas padrões:

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo6.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo6.png)

Vá em **View > Preferences...** e na janela vá em **Programs** pra configurar suas ferramentas padrões.
  * **Standard Editor** - para configurar o editor padrão. Aqui eu uso o geany e configuro escrevendo **geany** na primeira caixa e **%1** na segunda.
  * **Standard Explorer** - Como uso Ubuntu Linux escrevo **nautilus** na primeira e **%1** na segunda.
  * **Diff Tool** - para que ao pedir um diff de um arquivo ele possa abrir um programa que irá compara as duas versões de um mesmo arquivo. Configure com os parâmetros **meld** na primeira e **%1 %2** na segunda.

# Usando o Meld #

Meld é um programa que permite visualizar de forma clara as diferenças entre dois arquivos de texto. Além de dar opções de desfazer ou adicionar alterações bastando clicar nas setas do centro.

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo5.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo5.png)

Não tem segredo. tudo que estiver marcado de azul claro teve alguma alteração em sua linha. O que estiver em um azul mais escuro com letras vermelhas é o que realmente foi mudado.

O programa Meld pode ser encontrado no repositório do Ubuntu... o que torna a instalação dele muito prática.

# Dicas Para Desenvolvedores #

Os conflitos devem ser reduzidos ao máximo, para que ninguém tenha seu trabalho perdido ao fim do dia... ou tenha dificuldades para mesclar alterações de um outro membro. O próprio repositório é uma ferramenta para unir esforços colaborativos, mas ele só se torna realmente eficiente se forem seguidas algumas regras:

  * Antes de pensar em fazer uma alteração em um arquivo certifique-se que ninguém está editando o mesmo. Um modo de fazer isso é avisando através do fórum.
  * Faça um update diariamente. Isso vai reduzir sua chance de editar a versão antiga de um arquivo que outra pessoa editou. Por convenção, devemos fazer updates todas as manhãs. Você também pode verificar os logs no repositório [neste link](http://code.google.com/p/themanaworld-br/source/list) isso certamente irá lhe trazer garantias sobre a possível consequência de um update.

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo1.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo1.png)

Na lista de logs você, ao ver uma nova alteração, poderá ver quais arquivos foram alterados:

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo2.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo2.png)

E ainda tirar um diff ali mesmo:

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo3.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/rapidsvn_meld/passo3.png)

  * Outra coisa é enviar commits diários também. Na medida do possível é claro. Não é interessante enviar arquivos que ao serem utilizados geram um bug em todo o projeto. Isso iria tomar tempo de outro membro, que levaria algum tempo até descobrir o problema. Nestes casos envie as modificações comentadas ou só um comentário:
```
// Fazendo grandes alterações neste arquivo.
```
  * Toda alteração que possa ter um grande impacto no jogo deve estar previamente aprovadas.

É isso ! ;)