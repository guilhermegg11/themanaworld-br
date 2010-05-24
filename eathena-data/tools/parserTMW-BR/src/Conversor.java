/**
 * 
 * Este programa pode ser utilizado para gerar uma lista de itens, monstros, informações sobre jogadores
 * e outras coisas mais complexas como criar um histórico de tudo que acontece no jogo. Por enquanto só
 * estou trabalhando nas classes.
 * Estando feitas as classe qualquer um poderá dar sua contribuição... fazendo uma classe que explora
 * um aspécto X nos dados do jogo.
 * 
 * @data 10/05/2010
 * @author Diogo_RBG - http://diogorbg.blogspot.com/
 * 
 */

import java.io.PrintStream;

import parser.Comando;
import parser.Item;
import parser.Parser;
import parser.Script;
import parser.Token;
import parser.ParserItens.ColItem;
import parser.Token.TipoToken;

public class Conversor {

	static PrintStream out = System.out;

	public static void main (String args[]) {

		try{
			tabelaItens();

			//out.println("123... testando...");

			/*for(int i=0; i<a.length; i++){
				if(item.getColTipo(i)==TipoCol.VLR)
					out.println("VLR");
				else
					out.println("outro");
			}*/
		}catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
	 * Esta função apenas gera um exemplo para testes. Quando os códigos estiverem prontos ele irão carregar diretaente dos arquivos do eAthena.
	 */
	public static Parser geraExemplo(){
		Parser parser = new Parser();

		//512,biscoitoGengibre,biscoitoGengibre,0,50,25,1,,,,,,2,,,0,,{ itemheal 25,0; },{}
		Script uso = new Script();
		Script eqp = new Script();
		Comando cmd = new Comando();
		cmd.addToken( new Token(TipoToken.CMD, "itemheal") );
		cmd.addToken( new Token(TipoToken.NUM, "25") );
		cmd.addToken( new Token(TipoToken.NUM, "0") );
		uso.addComando(cmd);
		Object[] item1 = {"512", "biscoitoGengibre", "biscoitoGengibre" , "0", "50", "25" , "1", "", "", "", "", "", "2", "", "", "0", "", uso, eqp};

		//3046,verdadeiroTrevo,Verdadeiro Trevo,5,50000,25000,10,,,,5,0,2,8,,0,0,{},{ bonus bLuk, 10; bonus bMdef, 25; }
		uso = new Script();
		eqp = new Script();
		cmd = new Comando();
		cmd.addToken( new Token(TipoToken.CMD, "bonus") );
		cmd.addToken( new Token(TipoToken.VAR, "bLuk") );
		cmd.addToken( new Token(TipoToken.NUM, "10") );
		eqp.addComando(cmd);
		cmd = new Comando();
		cmd.addToken( new Token(TipoToken.CMD, "bonus") );
		cmd.addToken( new Token(TipoToken.VAR, "bMdef") );
		cmd.addToken( new Token(TipoToken.NUM, "25") );
		eqp.addComando(cmd);
		Object[] item2 = {"3046", "verdadeiroTrevo", "Verdadeiro Trevo", "5", "50000", "25000", "10", "", "", "", "5", "0", "2", "8", "", "0", "0", uso, eqp};

		parser.addItem(new Item(item1));
		parser.addItem(new Item(item2));

		return parser;
	}

	public static void tabelaItens(){
		Item item = null;
		String str = null;
		Script script = null;
		Comando cmd = null;

		//Parser itens = geraExemplo();
		Parser itens = new Parser();
		try {
			itens.carregarItens("item_db.txt");
			//itens.carregarItens("/home/tmw-br/trunk/eathena-data/tools/parserTMW-BR/item_db.txt");
		} catch (Exception e) {
			e.printStackTrace();
		}
		ColItem[] cols = { ColItem.ID, ColItem.NOME, ColItem.DESC, ColItem.TIPO, ColItem.COMP, ColItem.VEND, ColItem.USO, ColItem.EQP };

		out.println("| ID | NOME | DESCRICAO | TIPO | COMPRA | VENDA | USO | EQUIP |");
		itens.initIterator();
		while( itens.seProx() ) {
			item = itens.getProx();
			out.print("|");
			for(int i=0; i<cols.length; i++){

				// Quando for do tipo String:
				str = item.getColString( cols[i].ordinal() );
				if( str!=null ){
					out.print(" "+str+" |");
				}

				// Quando for do tipo Script:
				script = item.getColScript( cols[i].ordinal() );
				if( script!=null ){
					script.initIterator();
					while( script.seProx() ){
						cmd = script.getProx();
						if( cmd.getComando().equals("itemheal") ){
							if( !cmd.getString(1).equals("0") )
								out.print(" HP: +" +cmd.getString(1));
							if( !cmd.getString(2).equals("0") )
								out.print(" MP: +" +cmd.getString(2));
						}else if( cmd.getComando().equals("bonus") ){
							if( cmd.getString(1).equals("bLuk") ){
								out.print(" Sorte: +" +cmd.getString(2));
							}else if( cmd.getString(1).equals("bMdef") ){
								out.print(" Def.Mag: +" +cmd.getString(2));
							}else
								out.print(" +");
						}else{
							out.print(" *");
						}
					}
					out.print(" |");
				}
			}
			out.println();
		}
	}

}









