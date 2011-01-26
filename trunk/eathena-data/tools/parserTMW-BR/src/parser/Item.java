/**
 * 
 * @date 12/05/2010
 * @author Diogo_RBG - http://diogorbg.blogspot.com/
 * 
 */

package parser;

import parser.ParserItens.ColItem;

public class Item {

	public enum TipoCol{
		VALOR,  // Valor. Objeto do tipo String.
		SCRIPT  // Script. Objeto do tipo Script.
	}

	private Object[] cols = null;

	public Item(Object[] _cols) {
		cols = _cols;
	}

	/**
	 * Retorna o tipo de objeto armazenado na coluna desejada.
	 * @param col Índice da coluna desejada.
	 * @return Retorna null caso não seja de nenhum tipo. ¬¬ neste caso é porque tá bugado !
	 */
	public TipoCol getColTipo(int col){
		Object obj = cols[col];

		if(obj instanceof String){
			return TipoCol.VALOR;
		}else if(obj instanceof Script){
			return TipoCol.SCRIPT;
		}
		return null;
	}

	/**
	 * Retorna um objeto String no índice da coluna informada.
	 * @param col Índice da coluna desejada.
	 * @return Retorna null caso o objeto da coluna não seja do tipo String.
	 */
	public String getColString(int col){
		Object obj = cols[col];

		if(obj instanceof String)
			return (String) obj;
		return null;
	}

	/**
	 * Retorna um objeto Script no índice da coluna informada.
	 * @param col Índice da coluna desejada.
	 * @return Retorna null caso o objeto da coluna não seja do tipo Script.
	 */
	public Script getColScript(int col){
		Object obj = cols[col];

		if(obj instanceof Script)
			return (Script) obj;
		return null;
	}

	/**
	 * Retorna a quantidade de colunas. Também pode ser ulilizado obj.getCols().length
	 */
	public int cont(){
		return cols.length;
	}

	public Object[] getCols() {
		return cols;
	}

	public String getId(){
		return getColString( ColItem.ID.ordinal() );
	}

	public String getNome(){
		return getColString( ColItem.NOME.ordinal() );
	}

	public String getDesc(){
		return getColString( ColItem.DESC.ordinal() );
	}

	public String getTipo(){
		return getColString( ColItem.TIPO.ordinal() );
	}

	public String getCompra(){
		return getColString( ColItem.COMP.ordinal() );
	}

	public String getVenda(){
		return getColString( ColItem.VEND.ordinal() );
	}

	public String getPeso(){
		return getColString( ColItem.PESO.ordinal() );
	}

	public String getAtaque(){
		return getColString( ColItem.ATK.ordinal() );
	}

	public String getDefesa(){
		return getColString( ColItem.DEF.ordinal() );
	}

	public String getAlcance(){
		return getColString( ColItem.RANGE.ordinal() );
	}

	public String getMBonus(){
		return getColString( ColItem.MBONUS.ordinal() );
	}

}
