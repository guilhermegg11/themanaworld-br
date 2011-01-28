/**
 * @date 12/05/2010
 * @author Diogo_RBG - http://diogorbg.blogspot.com/
 */

package parserTXT;

public class Item {

	//#ID,Name,Label,Type,Price,Sell,Weight,ATK,DEF,Range,Mbonus,Slot,Gender,Loc,wLV,eLV,View,{UseScript},{EquipScript}
	public static final int ID =     0;  //< Id do item
	public static final int NOME =   1;  //< Nome do item
	public static final int DESC =   2;  //< Descricão do item
	public static final int TIPO =   3;  //< Tipo do item
	public static final int COMP =   4;  //< Valor de compra
	public static final int VEND =   5;  //< Valor de venda
	public static final int PESO =   6;  //< Peso do item
	public static final int ATK =    7;  //< Pontos de ataque
	public static final int DEF =    8;  //< Pontos de defese
	public static final int RANGE =  9;  //< Distância de ataque
	public static final int MBONUS = 10; //< Bonus de Magia
	public static final int SLOT =   11; //< Itens por slot
	public static final int GENERO = 12; //< Gênero do item
	public static final int LOC =    13; //< ?
	public static final int WLV =    14; //< ?
	public static final int ELV =    15; //< ?
	public static final int VIEW =   16; //< ?
	public static final int USO =    17; //< Script de uso
	public static final int EQP =    18; //< Script de equip

	public enum TipoCol {
		VALOR,  // Valor. Objeto do tipo String.
		SCRIPT  // Script. Objeto do tipo Script.
	}

	private Object[] cols = null;

	public Item(Object[] cols) {
		this.cols = cols;
	}

	/**
	 * Retorna o tipo de objeto armazenado na coluna desejada.
	 * @param col Índice da coluna desejada.
	 * @return Retorna null caso não seja de nenhum tipo. ¬¬ neste caso é porque tá bugado !
	 */
	public TipoCol getColTipo(int col) {
		Object obj = cols[col];

		if(obj instanceof String) {
			return TipoCol.VALOR;
		} else if(obj instanceof Script) {
			return TipoCol.SCRIPT;
		}
		return null;
	}

	/**
	 * Retorna um objeto String no índice da coluna informada.
	 * @param col Índice da coluna desejada.
	 * @return Retorna null caso o objeto da coluna não seja do tipo String.
	 */
	public String getColString(int col) {
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
	public Script getColScript(int col) {
		Object obj = cols[col];

		if(obj instanceof Script)
			return (Script) obj;
		return null;
	}

	/**
	 * Retorna a quantidade de colunas. Também pode ser ulilizado obj.getCols().length
	 */
	public int cont() {
		return cols.length;
	}

	public Object[] getCols() {
		return cols;
	}

	public String getId() {
		return getColString(ID);
	}

	public String getNome() {
		return getColString(NOME);
	}

	public String getDesc() {
		return getColString(DESC);
	}

	public String getTipo() {
		return getColString(TIPO);
	}

	public String getCompra() {
		return getColString(COMP);
	}

	public String getVenda() {
		return getColString(VEND);
	}

	public String getPeso() {
		return getColString(PESO);
	}

	public String getAtaque() {
		return getColString(ATK);
	}

	public String getDefesa() {
		return getColString(DEF);
	}

	public String getAlcance() {
		return getColString(RANGE);
	}

	public String getMBonus() {
		return getColString(MBONUS);
	}

	public String getSlot() {
		return getColString(SLOT);
	}

	public String getGenero() {
		return getColString(GENERO);
	}

	public String getLoc() {
		return getColString(LOC);
	}

	public String getWLV() {
		return getColString(WLV);
	}

	public String getELV() {
		return getColString(ELV);
	}

	public String getView() {
		return getColString(VIEW);
	}

	public Script getUso() {
		return getColScript(USO);
	}

	public Script getEquip() {
		return getColScript(EQP);
	}

}
