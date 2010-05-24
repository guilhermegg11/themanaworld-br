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

import parser.Token.TipoToken;

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

	/**
	 * Retorna a quantidade de tokens do comando.
	 * @return
	 */
	public int cont(){
		return tokens.size();
	}

	/**
	 * Retorna sempre o primeiro valor da lista.
	 */
	public String getComando(){
		return tokens.get(0).getValor();
	}

	/**
	 * Retorna o valor em forma de String.
	 */
	public String getString(int indice){
		return tokens.get(indice).getValor();
	}

	/**
	 * Retorna o tipo do tokem.
	 */
	public TipoToken getTipo(int indice){
		return tokens.get(indice).getTipo();
	}

}
