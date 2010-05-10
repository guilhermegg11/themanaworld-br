/**
 * Um comando armazena uma lista de tokens.
 * 
 * @data 10/05/2010
 * @author Diogo_RBG - http://diogorbg.blogspot.com/
 * 
 * O comando "itemheal 25,0;" seria dividido em:
 * { {"cmd", itemheal}, {"vlr", "25"}, {"vlr", "0"} }
 * 
 * Já o comando "callfunc "presenteSurpresa";" seria dividido em:
 * { {"cmd", "callfunc"}, {"txt", "presenteSurpresa"} }
 * 
 * E o comando "bonus bLuk, 10;" dividido em:
 * { {"cmd", "bonus"}, {"var", "bLuk"}, {"vlr", "10"} }
 * 
 */

package parser;

import java.util.ArrayList;

public class Comando {

	private ArrayList<Token> tokens = new ArrayList<Token>();

	public Comando() {
		// constructor
	}

	/**
	 * Retorna a lista de tokens para consultas.
	 * @return A lista de tokens.
	 */
	public ArrayList<Token> getTokens() {
		return tokens;
	}

	/**
	 * Adiciona um token ao final da lista de tokens.
	 * @param _token Token a ser adicionado.
	 * @return true caso seja inserido com sucesso.
	 */
	public boolean addToken(Token token){
		return tokens.add(token);
	}

}
