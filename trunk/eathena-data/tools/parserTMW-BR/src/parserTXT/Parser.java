/**
 * Classe parser genérica que será utilizada por todas as outras.
 * 
 * @data 10/05/2010
 * @author Diogo_RBG - http://diogorbg.blogspot.com/
 * 
 * Wikipédia: Em ciência da computação e linguística, análise sintática (também conhecida pelo termo
 * em inglês parsing) é o processo de analisar uma sequência de entrada (lida de um arquivo de computador
 * ou do teclado, por exemplo) para determinar sua estrutura gramatical segundo uma determinada gramática
 * formal. Essa análise faz parte de um compilador, junto com a análise léxica e análise semântica.
 */

package parserTXT;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.util.ArrayList;
import java.util.Iterator;

public class Parser {

	private ArrayList<Item> itens = new ArrayList<Item>();
	private Iterator<Item> it = null;

	public Parser() {
		// constructor
	}

	public ArrayList<Item> getItens() {
		return itens;
	}

	public int cont(){
		return itens.size();
	}

	/**
	 * Apenas retorna o iterator interno do parser.
	 */
	public Iterator<Item> getIterator() {
		return it;
	}

	/**
	 * Apenas seta o iterator interno do parser.
	 */
	public void setIterator(Iterator<Item> it) {
		this.it = it;
	}

	/**
	 * Prepara o iterator interno do parser para que ele liste toda a lista de itens.
	 */
	public void initIterator(){
		it = itens.iterator();
	}

	/**
	 * Retorna a existência ou não de um próximo item. Além de não mover o iterator.
	 * @return Retorna null caso o iterator interno não tenha sido inicializado.
	 */
	public boolean seProx(){
		if(it==null)
			return false;
		return it.hasNext();
	}

	/**
	 * Retorna o próximo item ao mesmo tempo que move o iterator interno para o próxmo item.
	 * @return Retorna null caso o iterator interno não tenha sido inicializado.
	 */
	public Item getProx(){
		if(it==null)
			return null;
		return it.next();
	}

	public void addItem(Item item) {
		itens.add(item);
	}

	public Item getItem(int lin) {
		return itens.get(lin);
	}

	/**
	 * Carrega um arquivo de texto com a formatação do script de item_db.txt do eAthena e o trasnforma
	 * em uma árvore de objetos.
	 * @param arquivo Endereço do arquivo.
	 * @throws Exception Dispara Exception caso o arquivo não exista ou não possa ser lido.
	 */
	public void carregarItens(String arquivo) throws Exception {
		try {
			int rc;
			ParserItens parser = new ParserItens(this);
			File file = new File(arquivo);

			if( !file.exists() )
				throw new Exception("Arquivo '"+arquivo+"' não encontrado.");

			BufferedReader br = new BufferedReader (new FileReader (file));
			while ((rc = br.read()) != -1) {
				parser.analisar((char)rc);
			}
		}catch(Exception ex){
			ex.printStackTrace();
		}
	}

}
