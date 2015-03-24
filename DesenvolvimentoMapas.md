# Introdução #

Nesta página consta os primeiros passos para se construir um mapa que possa ser facilmente aproveitado pelo **The Mana World BR**.

Se você não sabe como editar mapas, recomendo que leia alguns tutorias:
  * [Creating a simple map with Tiled (Wiki Tiled)](http://mapeditor.org/wiki/Creating_a_simple_map_with_Tiled)
  * [Mapping Tutorial (Wiki The Mana World)](http://wiki.themanaworld.org/index.php/Mapping_Tutorial)
  * [Game map edition using Tiled (Blog Silveira Neto)](http://silveiraneto.net/2009/01/11/game-map-edition-using-tiled/)
  * [Construindo um MMORPG - Parte 3 (Blog Diogo\_RBG)](http://diogorbg.blogspot.com/2009/03/construindo-um-mmorpg-parte-3.html)

# Medidas e Configurações #

Nem preciso dizer que você não deve criar mapas com tiles de medidas diferentes de 32x32 em tilesets normais e tiles de altura múltipla de 32 em tilesets de objetos "altos", por assim dizer.

Existem outras tantas configurações que deveriam ser levadas em consideração... por enquanto use o [Wiki do The Mana World](http://wiki.themanaworld.org/index.php/Mapping_Tutorial) para se orientar.

Peço que respeitem a margem de 20 tiles de borda na edição de uma mapa... estes 20 tiles possuem senário, mas o usuário, por um obstáculo ou outro, é impedido de ultrapassar esta margem. Esta margem é uma medida que assegura o realismo do mapa... a rolagem tem 20 tiles livres para expandir em qualquer direção.

## "Tiles altos" ##

Por falta de uma nomenclatura melhor resolvi chamar os tiles agrupados de **tiles altos**. Mas o que seria um tiles alto? São os tiles dos tilesets terminados com `_`x2.png, `_`x3.png... exemplo:

![http://themanaworld-br.googlecode.com/svn/tmwdata/graphics/tiles/desert_x2.png](http://themanaworld-br.googlecode.com/svn/tmwdata/graphics/tiles/desert_x2.png)

Existe uma configuração especial para utilizar estes tilestes. Um tileset `_`x2.png precisa ter altuta 32`*`2 = **64**. Um `_`x3.png precisa ter altura 32`*`3 = **96**. Isto é configurado dessa forma:

![http://themanaworld-br.googlecode.com/svn/wiki/imgs/novo-tileset-x2.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/novo-tileset-x2.png) **==>** ![http://themanaworld-br.googlecode.com/svn/wiki/imgs/usando-tile-x2.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/usando-tile-x2.png)

## Camadas ##

Quando se está editando um mapa é um comum o artista não saber qual tile vai em cada camada ou misturar os tiles entre todas as camadas. As camadas seguem uma lógica e se não for obedecida seu mapa pode ter um resultado diferente do esperado quando estiver em jogo.

As camadas são:
  * **Ground** - Camada para terrenos. Nesta camada vão os tiles de grama, terra, areia, água, calçadas... tudo que faz parte do piso. Alguns tiles de pequenos objetos misturados ao piso também vão aqui. **Regra:** Devem estar abaixo da camada Fringe e possuir tiles totalmente preenchidos (sem transparência) para evitar bugs gráficos. Não precisa se chamar Ground.
![http://themanaworld-br.googlecode.com/svn/wiki/imgs/layer-ground.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/layer-ground.png)
  * **Fringe** - Camada de objetos. Ela é que dá dimensão 2D ao mapa imprimindo objetos em movimento na frente ou atrás de seus tiles. **Regra:** Esta camada separa as camadas de piso das camadas de objetos aéreos. Precisa se chamar Fringe.
![http://themanaworld-br.googlecode.com/svn/wiki/imgs/layer-fringe.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/layer-fringe.png)
  * **Top** - Camada de objetos aéreos. Nesta camada vão os tiles que estão acima da superfície do mapa ou aquele que você quer impresso sobre o personagem. **Regra:** Devem estar acima da camada Fringe. Não precisa se chamar Top.
![http://themanaworld-br.googlecode.com/svn/wiki/imgs/layer-top.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/layer-top.png)
  * **Collision** - Camada de colisão. Esta camada não é visível no jogo e determina se um tile permite passagem ou não. Quando terminar de marcar os tiles que não permitem passagem pintes as áreas que ficarem inacessíveis, pois isto evita que o servidor coloque monstros nestas áreas. **Regra:** Somente o tile vermelho do tileset collision.png pode ser utilizado nesta camada. Precisa se chamar Collision.
![http://themanaworld-br.googlecode.com/svn/wiki/imgs/layer-collision.png](http://themanaworld-br.googlecode.com/svn/wiki/imgs/layer-collision.png)

# Mapeamento em ação #

Estando familiarizado com o editor [Tiled](http://mapeditor.org/) e tendo conhecimento das medidas e configurações que devem ser adotadas na edição de mapas.. é hora de trabalhar com sua dedicação e criatividade na edição de mapas.

Comece carregando os tilesets, mapas e outros arquivos do diretório **tmwdata** em noso Repositório ([RepositorioSVN](RepositorioSVN.md)).

Comando:
```
svn checkout http://themanaworld-br.googlecode.com/svn/tmwdata tmwdata
```
O comando irá criar um diretório chamado **tmwdata**. No diretório tmwdata se encontram todos os dados de customizados para o TMW-BR. Estes são os mesmos dados de update baixados após a conexão com nosso servidor **server.themanaworld.com.br**. (obs.: ainda não trabalhamos o servidor... por isso esta informação é uma meia verdade !)

De toda a estrutura em tmwdata o que importa para nós são apenas dois diretórios: **tiles** e **maps**. Veja a estrutura logo abaixo:
```
tmwdata/
|_ graphics/
|  |_ tiles/
|     |_ arena.png
|     |_ block_walls-0043.png
|     |_ cave_bilevel.png
|     |_ ...
|     |_ Woodland_x8.png
|_ maps/
   |_ meu_mapa.tmx
```
O arquivo **meu\_mapa.tmx** é uma sugestão para nome de arquivo para o mapa que você irá fazer. O importante é que, dentro do diretório **tmwdata/**, ele esteja em **maps/** e também que utilize apenas os tiles do diretório **graphics/tiles/**.

| **AVISO**: No TMW seu mapa deve estar no diretório **maps/** e seus tilesets em **graphics/tiles/**. Qualquer arquivo fora deste esquema não é carregado. |
|:-------------------------------------------------------------------------------------------------------------------------------------------------------------|

Sim... agora você já pode construir seu novo mapa !

Quando terminar abra um tópico de discussão no [fórum TMW-BR](http://www.themanaworld.com.br/) e mostre seu trabalho. Nós receberemos agradecidos !

# Testando #

Para testar o mapa no jogo TMW você deve executá-lo com os parâmetros **-ud** e em seguida apontar onde estão os dados que ele deve utilizar. Como nosso servidor ainda não foi configurado utilize os dados do servidor oficial:
```
git clone git://gitorious.org/tmwdata/mainline.git tmwdata
```
Em seguida substitua um dos mapas pelo mapa que você editou, copie todos os tilesets e teste logando no servidor oficial.
```
tmw -ud /caminho_completo/tmwdata
```

# Em desenvolvimento #

  * [Ilha Fortaleza](http://www.themanaworld.com.br/viewtopic.php?f=17&t=66&sid=bf18211162dd39e9aa24bbc652fe2e93)