package parserXML;

import org.w3c.dom.Element;

public class ReadXML {

	public void carregarXML(String arquivo){
	}

	/**
	 * Retorna uma propriedade da tag XML como Integer.
	 * @param elem Tag XML do tipo Node.ELEMENT_NODE
	 * @param atributo O nome do atributo.
	 * @param padrao Valor padrão retornado caso a atributo não exista.
	 */
	static public Integer getAtributoInteger(Element elem, String atributo, Integer padrao){
		Integer num = padrao;
		try{
			num = Integer.parseInt( elem.getAttribute(atributo) );
		} catch(Exception e){}

		return num;
	}

	/**
	 * Retorna uma propriedade da tag XML como int.
	 * @param elem Tag XML do tipo Node.ELEMENT_NODE
	 * @param atributo O nome do atributo.
	 * @param padrao Valor padrão retornado caso a atributo não exista.
	 */
	static public int getAtributo(Element elem, String atributo, int padrao){
		int num = 1;
		try{
			num = Integer.parseInt( elem.getAttribute(atributo) );
		} catch(Exception e){}

		return num;
	}

	/**
	 * Retorna uma propriedade da tag XML como String.
	 * @param elem Tag XML do tipo Node.ELEMENT_NODE
	 * @param atributo O nome do atributo
	 * @param padrao Valor padrão retornado caso a atributo não exista.
	 */
	static public String getAtributo(Element elem, String atributo, String padrao){
		String str = elem.getAttribute(atributo);
		if( str==null )
			return padrao;
		return str;
	}

	/**
	 * Retorna o valor interno da tag sem os espaços das extremidades.
	 * @param tag elem Tag XML do tipo Node.ELEMENT_NODE
	 * @return Pode retornar null.
	 */
	static public String getValorTrim(Element tag) {
		if(tag.getFirstChild()!=null)
			return tag.getFirstChild().getNodeValue().trim();
		return null;
	}

}
