/**
 * Nesta classe é escrito o código que realmente irá parsear o arquivo de itens do eAthena.
 * @date 10/05/2010
 * @author Diogo_RBG - http://diogorbg.blogspot.com/
 * 
 */

package parser;

import java.util.ArrayList;

import parser.Token.TipoToken;

enum Est{
	INICIAL, //< estado inicial
	COMENT,  //< comentário
	COMENT2,
	VLR1,    //< valor... pode ser qualquer coisa L,N,_,ã,ç...
	VLR2,
	SCRIPT,  //< script
	SCRIPT2,
	SINAL,   //< + e -
	VAR,     //< variavel
	NUM,     //< número
	EXP,     //< Expressão não reconhecida pelo analisador
	TXT1,    //< texto com áspas
	TXT2,
	TXT3,
	TOKEN    //< token qualquer não identificado.
}

public class ParserItens {
	
	//#ID,Name,Label,Type,Price,Sell,Weight,ATK,DEF,Range,Mbonus,Slot,Gender,Loc,wLV,eLV,View,{UseScript},{EquipScript}
	public enum ColItem{
		ID,     //< Id do item
		NOME,   //< Nome do item
		DESC,   //< Descricão do item
		TIPO,   //< Tipo do item
		COMP,   //< Valor de compra
		VEND,   //< Valor de venda
		PESO,   //< Peso do item
		ATK,    //< Pontos de ataque
		DEF,    //< Pontos de defese
		RANGE,  //< Distância de ataque
		MBONUS, //< Bonus de Magia
		SLOT,   //< Itens por slot
		GENERO, //< Gênero do item
		LOC,    //< ?
		WLV,    //< ?
		ELV,    //< ?
		VIEW,   //< ?
		USO,    //< Script de uso
		EQP     //< Script de equip
	}

	int lin;       //< linha atual
	int col;       //< coluna atual
	Est est;       //< estado atual
	String str;    //< string formada
	String aux;    //< string auxiliar

	Parser parser; //< parser pai
	ArrayList<Object> itens;
	Script script;
	Comando cmd;
	Token tok;

	public ParserItens(Parser pai) {
		init(pai);
	}

	/**
	 * Inicializa todas as variáveis internas de controle do analisador.
	 * @param pai Objeto Parser que chamou o analisador ParserItens.
	 */
	public void init(Parser pai){
		lin = 1;
		col = 0;
		est = Est.INICIAL;
		str = "";

		parser = pai;
		itens = new ArrayList<Object>();
		script = new Script();
		cmd = null;
		tok = null;
	}

	/**
	 * Coração do parser. Analisa um caractere por vez modificando os estados da máquina de estados.
	 * @param c
	 */
	public void analisar(char c){
		boolean movVaz = true;

		//= Contagem de linhas e colunas
		col++;
		if(c=='\n'){
			lin++;
			col = 0;
		}

		while( movVaz ){
			movVaz = false;

			switch(est){

			case INICIAL: //= Estado inicial do analisador ========================================
				if(c=='/')
					est = Est.COMENT2;
				else if(c=='#')
					est = Est.COMENT;
				else if(c=='{')
					est = Est.SCRIPT;
				else if( esp(c) || c=='\n' );
				else{
					est = Est.VLR1;
					movVaz = true;
				}
				break;

			case COMENT: //= Estado de comentário =================================================
				if(c=='\n')
					est = Est.INICIAL;
				break;

			case COMENT2: //= O estado espera o caractere '/' para se sertificar que se trata de um comentário.
				if(c=='/')
					est = Est.COMENT;
				else{
					est = Est.INICIAL;
					movVaz = true;
					aviso("Era esperado o caractere '/' para completar o comentario.", c);
				}
				break;

			case VLR1: //= Analisa valores entre vírgulas =========================================
				if(c==','){
					est = Est.INICIAL;
					itens.add(str);
					str = new String();
				}else if(c=='\n'){
					est = Est.INICIAL;
					itens.add(str);
					str = new String();
					parser.addItem( new Item(itens.toArray()) );
					//System.out.println("itens: "+ itens.toString() );
					itens = new ArrayList<Object>();
				}else if( esp(c) ){
					est = Est.VLR2;
					aux = ""+c;
				}else
					str += c;
				break;

			case VLR2: //= adiciona espaços e tabulações entre valores ============================
				if(c==',' || c=='\r' || c=='\n'){
					est = Est.VLR1;
					movVaz = true;
				}else if( esp(c) ){
					aux += c;
				}else{
					est = Est.VLR1;
					str += aux;
					str += c;
				}
				break;

			case SCRIPT: //========================================================================
				if( tok!=null ){ // c==' ' c=='\t' c==',' c==';' c=='}'
					if(cmd==null){
						cmd = new Comando();
						tok.paraComando();
					}
					cmd.addToken(tok);
					tok = null;
					if( c==';' || c=='}'){
						script.addComando(cmd);
						cmd = null;
					}
				}else if(c==',' || c==';'){
					erro("Nenhum tokem lido antes de encerrar.", c);
				}
				if( c=='}' ){
					est = Est.SCRIPT2;
					str = "";
					itens.add(script);
					script = new Script();
				}else if( esp(c) || c==',' || c==';'){
					//< espaços, tabulações e separadores entre comandos.
				}else if(c=='+' || c=='-'){
					est = Est.SINAL;
					str = ""+c;
				}else if( let(c) || var(c) ){
					est = Est.VAR;
					str = ""+c;
				}else if( num(c) ){
					est = Est.NUM;
					str = ""+c;
				}else if( c=='"' ){
					est = Est.TXT1;
					str = "";
				}else{
					est = Est.EXP;
					str = ""+c;
				}
				break;

			case SCRIPT2: //= Estado para o encerramento do script ================================
				if( c==',' )
					est = Est.INICIAL;
				else if( c=='\n' ){
					est = Est.INICIAL;
					parser.addItem( new Item(itens.toArray()) );
					//System.out.println("itens: "+ itens.toString() );
					itens = new ArrayList<Object>();
				}
				break;

			case SINAL: //=========================================================================
				if( let(c) || var(c) ){
					est = Est.VAR;
					str += c;
				}else if( num(c) ){
					est = Est.NUM;
					str += c;
				}else if(c==',' || c==';' || c=='}'){
					est = Est.SCRIPT;
					movVaz = true;
				}else{
					est = Est.EXP;
					str += c;
				}
				break;

			case VAR: //===========================================================================
				if( let(c) || num(c) || c=='_'){
					str += c;
				}else if(esp(c) || c==',' || c==';' || c=='}'){
					est = Est.SCRIPT;
					movVaz = true;
					tok = new Token(TipoToken.VAR, str);
				}else{
					est = Est.EXP;
					str += c;
					//movVaz = true;
					//aviso("Caractere nao pode ser uma variavel.", c);
				}
				break;

			case NUM: //===========================================================================
				if( num(c) ){
					str += c;
				}else if(esp(c) || c==',' || c==';' || c=='}'){
					est = Est.SCRIPT;
					movVaz = true;
					tok = new Token(TipoToken.NUM, str);
				}else{
					est = Est.EXP;
					str += c;
					//movVaz = true;
					//aviso("Caractere nao pode ser um numero.", c);
				}
				break;

			case EXP: //===========================================================================
				if(c==',' || c==';' || c=='}'){
					est = Est.SCRIPT;
					movVaz = true;
					tok = new Token(TipoToken.EXP, str.trim());
				}else
					str += c;
				break;

			case TXT1: //==========================================================================
				if( c=='\n' ){
					est = Est.SCRIPT2;
					movVaz = true;
					erro("String foi interrompida por um \n.", c);
				}else if( c=='"' ){
					est = Est.TXT3;
					tok = new Token(TipoToken.TXT, str);
				}else if( c=='\\' )
					est = Est.TXT2;
				else
					str += c;
				break;

			case TXT2: //==========================================================================
				if( c=='\n' ){
					est = Est.SCRIPT2;
					movVaz = true;
					erro("String foi interrompida por um \n.", c);
				}else{
					est = Est.TXT1;
					str += c;
				}
				break;

			case TXT3: //==========================================================================
				if( c==',' || c==';' || c=='}'){
					est = Est.SCRIPT;
					movVaz = true;
				}else if( !esp(c) )
					aviso("Caractere nao esperado apos string.", c);
				break;

			default: //============================================================================
				erro("Erro interno!", c);
				break;
			}

		}
		//if( est!=Est.COMENT && est!=Est.COMENT2 && est!=Est.VLR1 && est!=Est.VLR2 )
		//	System.out.println("lin:"+lin+" col:"+col+" tok:'"+str+"' '"+c+"' est:"+est.toString());
	}

	boolean var(char c){
		if(c=='_' || c=='@' || c=='#' || c=='$')
			return true;
		return false;
	}

	boolean let(char c){
		if(c>='a'&&c<='z' || c>='A'&&c<='Z')
			return true;
		return false;
	}

	boolean num(char c){
		if(c>='0'&&c<='9')
			return true;
		return false;
	}

	boolean esp(char c){
		if(c==' ' || c=='\t')
			return true;
		return false;
	}

	void aviso(String msg, char c){
		System.out.println("#aviso ParserItens. "+msg+" lin:"+lin+" col:"+col+" tok:'"+str+"' '"+c+"'");
	}

	void erro(String msg, char c){
		System.out.println("#erro ParserItens. "+msg+" lin:"+lin+" col:"+col+" tok:'"+str+"' '"+c+"'");
	}

}
