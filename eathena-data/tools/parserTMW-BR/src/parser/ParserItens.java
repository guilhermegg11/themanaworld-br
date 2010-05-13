/**
 * 
 * @data 10/05/2010
 * @author Diogo_RBG - http://diogorbg.blogspot.com/
 * 
 */

package parser;

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

}
