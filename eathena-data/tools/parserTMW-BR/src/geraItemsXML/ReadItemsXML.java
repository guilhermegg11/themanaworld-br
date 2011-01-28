package geraItemsXML;

import java.util.HashMap;
import javax.xml.parsers.*;
import org.w3c.dom.*;

import com.sun.org.apache.xerces.internal.dom.DeferredAttrImpl;

public class ReadItemsXML {

	private HashMap<Integer, HashMap<String, String>> elementos = new HashMap<Integer, HashMap<String, String>>();

	public ReadItemsXML() {}

	public void carregarXML(String nomeArquivo) {
		DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		DocumentBuilder db = null;
		Document doc = null;

		try {
			db = dbf.newDocumentBuilder();
			doc = db.parse(nomeArquivo);
		} catch (Exception e) {
			System.out.println("Não é possível carregar o arquivo '"+nomeArquivo+"'.");
			e.printStackTrace();
		}

		Element elem = doc.getDocumentElement();

		// pega todos os elementos 'item' do XML ====================
		NodeList nl = elem.getElementsByTagName("item");
		DeferredAttrImpl def = null;
		HashMap<String, String> atribs = null;

		// percorre cada elemento 'item' encontrado
		for( int i=0; i<nl.getLength(); i++ ) {
			Element tag = (Element) nl.item(i);

			int id = getAtributo(tag, "id", 0);
			if(id>0){
				atribs = new HashMap<String, String>();
				for(int j=0; j<tag.getAttributes().getLength(); j++){
					def = (DeferredAttrImpl) tag.getAttributes().item(j);
					atribs.put(def.getName(), def.getValue());
				}
				elementos.put(id, atribs);
			}
		}

	}

	public HashMap<Integer, HashMap<String, String>> getElementos() {
		return elementos;
	}

	/**
	 * Retorna um item. Contem a HashMap de atributos.
	 * @param id id do item.
	 */
	public HashMap<String,String> getItem(String id) {
		return elementos.get( Integer.parseInt(id) );
	}

	/**
	 * Retorna uma propriedade da tag XML como int.
	 * @param elem Tag XML do tipo Node.ELEMENT_NODE
	 * @param atributo O nome do atributo
	 * @param padrao Valor padrão retornado caso a atributo não exista
	 */
	private int getAtributo(Element elem, String atributo, int padrao) {
		int num = 1;
		try{
			num = Integer.parseInt( elem.getAttribute(atributo) );
		} catch(Exception e){}

		return num;
	}

	/**
	 * Retorna uma propriedade do item como Integer.
	 * @param item Objeto do tipo HashMap<String,String>.
	 * @param atributo O nome do atributo
	 * @param padrao Valor padrão retornado caso a atributo não exista
	 */
	static public Integer getAtributoInteger(HashMap<String,String> item, String atributo, Integer padrao){
		Integer num = padrao;
		try{
			num = Integer.parseInt( item.get(atributo) );
		} catch(Exception e){}

		return num;
	}

	/**
	 * Retorna uma propriedade do item como int.
	 * @param item Objeto do tipo HashMap<String,String>.
	 * @param atributo O nome do atributo
	 * @param padrao Valor padrão retornado caso a atributo não exista
	 */
	static public int getAtributo(HashMap<String,String> item, String atributo, int padrao){
		int num = padrao;
		try{
			num = Integer.parseInt( item.get(atributo) );
		} catch(Exception e){}

		return num;
	}

	/**
	 * Retorna uma propriedade do item como String.
	 * @param item Objeto do tipo HashMap<String,String>.
	 * @param atributo O nome do atributo
	 * @param padrao Valor padrão retornado caso a atributo não exista
	 */
	static public String getAtributo(HashMap<String,String> item, String atributo, String padrao){
		String str = item.get(atributo);
		if( str==null )
			return padrao;
		return str;
	}

}
