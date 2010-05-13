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

package parser;

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

}
