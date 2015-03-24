# Introdução #

Todas as falas dos NPCs, itens adicionados ou removidos, controle de quests e até posições iniciais e quantidade de monstros... tudo é desenvolvido em script. Conhecido como **eAthena Script**.

Parte de sua sintaxe é parecida com a sintaxe da linguagem C... já a outra parte nem tanto !

## Exemplo 1 - Script simples ##

Para criar um personagem que diz algo é simples, basta criar um código como este:

```
008.gat,108,23,0	script	Roger	104,{
	mes "[Roger (Guarda da Torre)]";
	mes "ZzzZzzZ...";
	next;
	mes "Hã!!! Eu não estava dormindo.";
	close;
}
```

Logo de cara temos muitas informações que não fazem sentido... mas elas vão fazer:

  * **008.gat** - Diz respeito ao mapa onde será criado o NPC.
  * **108,23** - São as coordenadas onde o NPC irá aparecer neste mapa.
  * **0** - É a orientação do NPC... não importa muito.
  * TAB - Deve existir uma tabulação aqui.
  * **script** - Significa que iremos escrever um script
  * TAB - Deve existir uma tabulação aqui.
  * **Roger** - O nome do nosso NPC. Pode conter espaços ou acentos.
  * TAB - Deve existir uma tabulação aqui.
  * **104** - O id da imagem do nosso NPC. Neste caso, o guarda.
  * **{** - Início do bloco script.
  * INSTRUÇÕES - O script em si estará entre as chaves. **{** e **}**.
  * **}** - Fim do bloco scritp.

Entre os delimitadores **{** e **}** escrevemos instruções... tais instruções podem ser:

  * **mes** - Mensagem que será impressa para o jogador.
  * **next** - Uma pausa. Mostra o botão **próximo** para que o jogador não veja todo o texto de uma só vez.
  * **close** - Finaliza o script. Mostra o batão **fechar**.

Outras coisas que podem ser observadas:
  * Todo texto deve estar entre aspas. **"** e **"**.
  * Toda linha deve ser finalizada com **;**.
  * Toda linha é iniciada por uma tabulação. Mas isso serve apenas para organizar o código. Camada de identação de código.

## Exemplo 2 - Falas aleatórias ##

E se agora quisermos dar falas aleatórias para nosso NPC ?! É aqui que iremos precisar conhecer mais instruções. set, rand, if, goto...

```
008.gat,45,81,0	script	Juliana	114,{
	set @tmp,rand(5);
	if(@tmp==0) goto _1;
	if(@tmp==1) goto _2;
	if(@tmp==2) goto _3;
	if(@tmp==3) goto _4;
	if(@tmp==4) goto _5;

_1:
	mes "[Juliana]";
	mes "\"Este é meu jardim secreto.\"";
	close;
_2:
	mes "[Juliana]";
	mes "\"Eu adoro sentir o aroma das flores.\"";
	close;
_3:
	mes "[Juliana]";
	mes "\"Cuide bem da natureza e ela te recompensará em dobro.\"";
	close;
_4:
	mes "[Juliana]";
	mes "\"Uma vez eu ví um Escorpião Vermelho!\"";
	close;
_5:
	mes "[Juliana]";
	mes "\"A brisa daqui é muito agradável.\"";
	close;
}
```

Nosso personagem irá falar uma das 5 falas. Para que isso aconteça devemos direcionar a execução do script para umas dos seguimentos `_`1, `_`2, `_`3, `_`4 ou `_`5. As instruções utilizadas para isso são:

  * **set** - Seta, define o valor de uma variável.
  * **rand()** - Retorna um número aleatório (número randômico).
  * **if** - Instrução condicional. Se a comparação retornar verdadeiro ele executa o comando. (tradução **se**, **por acaso**).
  * **==** - Faz comparação. Retorna verdadeiro caso os valores sejam iguais. 2==1+1 ==> sim!
  * **goto** - Direciona a linha execução do script para um seguimento de código.
  * **:** - Define o início de um seguimento de código.

O que mais podemos observar deste código ?!

  * Criamos uma variável chamada **@tmp**. O **@** no início do nome não foi atoa. Uma variável iniciada com @ é uma variável temporária... ela só existe enquanto o script é executado.
  * No final de todo seguimento de código existe um **close**. Se ele não existir a execução continua e o seguimento seguinte é executado.
  * Notou o **\"** em todas as falas do NPC ?! O Contra barra **\** é um caractere de escape. Isto significa que ele não tem valor sozinho... só serve para escrever um outro caractere especial. **\"** escreve **"**. **\\** escreve **\**.

## Exemplo 3 - Exemplo completo ##

O próximo passo é escrever um NPC mais completo. Que saiba que você já fez a quest ou não... e que tbm saiba o que você tem em seu inventório. Eis um exemplo de um NPC que irá dar maçãs.

```
008.gat,27,61,0	script	Joana	163,{
	if(QUEST_Macas==1) goto _continuacao;
	if(QUEST_Macas==2) goto _ok;

	mes "[Joana]";
	mes "\"Olá jovem. Eu estou colhendo maçãs para fazer uma deliciosa torta.";
	mes "Eu precisava de mais algumas, mas as únicas que sobraram estão lá no alto.\"";
	next;
	mes "[Joana]";
	mes "\"Você pode me ajudar a pegá-las?\"";
	next;
	menu
		"Sim, será um prazer.", _sim,
		"Estou com pressa!", _nao;
	goto _nao;

_sim:
	mes "[Joana]";
	mes "\"Muito obrigada!\"";
	next;
	mes "[Joana]";
	mes "\"Nossa! Você colheu tantas maçãs!";
	mes "Já que eu não preciso de tantas vou te dar algumas.\"";
	set QUEST_Macas, 1;
	next;
	goto _darMacas;

_darMacas:
	getinventorylist;
	if( (@inventorylist_count + (countitem(535)==0) ) > 100 ) goto _cheio;

	mes "[Joana]";
	mes "\"Tome estas. São as mais suculentas.\"";
	getitem 535, 10; //Maçãs
	set QUEST_Macas, 2;
	close;

_continuacao:
	mes "[Joana]";
	mes "\"Parece que agora posso te dar aquelas maçãs.\"";
	next;
	goto _darMacas;

_cheio:
	mes "[Joana]";
	mes "\"Que pena, parece que sua sacola está cheia.";
	mes "Volte mais tarde.\"";
	close;

_nao:
	mes "[Joana]";
	mes "\"Os jovens constumavam ser mais prestativos.\"";
	close;

_ok:
	mes "[Joana]";
	mes "\"Obrigada por me ajudar!\"";
	close;
}
```

Reparou nas novas instruções ?! Vamos descrevê-las.
  * **menu** - Em vez de mensagem de texto, será exibido um menu com várias opções.
  * **getinventorylist** - Lê os dados do inventório. Neste momento @inventorylist\_count para a existir.
  * **countitem()** - Conta quantas unidades de um mesmo item existe.
  * **getitem** - Adiciona unidades de um item ao inventório.
  * **delitem** - Remove unidades de um item do inventório.

## Exemplo 4 - Exemplo de vendedor ##

Se a nossa intenção for escrever um NPC de compra e venda... aí é que é fácil mesmo:

```
008.gat,161,98,0	shop	Ronaldo	109,525:800,531:3000,530:8000,1199:3,603:-1
```

Em vez de **script** usamos **shop**. E em vez das instruções, só precisamos dos dados. **Id** do item e **valor** de compra.

obs.: Os valores de venda estão no banco de dados do servidor.

## Exemplos de scripts mais complexos ##

Para saber como escrever scripts mais complexos estude pelos scripts já implementados. [Neste link](http://themanaworld-br.googlecode.com/svn/eathena-data/npc) você irá encontrar alguns exemplos.

Links:

  * [Wiki The Mana World](http://wiki.themanaworld.org/index.php/EAthena_Scripting_Standards)
  * [Maual de referências em Português](http://themanaworld-br.googlecode.com/svn/wiki/arquivos/eAthena_Script.txt)
  * [Manual de referências em Inglês (Salve como HTML)](http://themanaworld-br.googlecode.com/svn/wiki/arquivos/eAthena_Script.html)