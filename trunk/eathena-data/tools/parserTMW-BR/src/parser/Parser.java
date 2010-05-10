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

import java.util.HashMap;

public class Parser {

	private HashMap<String,String[]> dados = new HashMap<String,String[]>();

	public Parser() {
		// constructor
	}

	public HashMap<String, String[]> getDados() {
		return dados;
	}

}
