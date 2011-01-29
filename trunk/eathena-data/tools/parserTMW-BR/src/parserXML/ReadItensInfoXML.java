package parserXML;

import java.util.ArrayList;
import java.util.List;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

public class ReadItensInfoXML extends ReadXML {

	private List<GrupoItemInfo> grupos = new ArrayList<GrupoItemInfo>();

	public void carregarXML(String arquivo){
		DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		DocumentBuilder db = null;
		Document doc = null;

		try {
			db = dbf.newDocumentBuilder();
			doc = db.parse(arquivo);
		} catch (Exception e) {
			System.out.println("Não é possível carregar o arquivo '"+arquivo+"'.");
			e.printStackTrace();
		}

		Element elem = doc.getDocumentElement();

		// pega todos os elementos 'group' do XML ====================
		NodeList nl = elem.getElementsByTagName("group");

		// percorre cada elemento 'group' encontrado
		for( int i=0; i<nl.getLength(); i++ ) {
			Element tag = (Element) nl.item(i);
			popularGrupoInfo(tag);
		}
	}

	private void popularGrupoInfo(Element tag) {
		GrupoItemInfo grupo = new GrupoItemInfo();
		grupo.setType( getAtributo(tag, "type", "") );
		grupo.setId( getAtributo(tag, "id", "") );
		grupo.setName( getAtributo(tag, "name", "") );
		grupo.setDesc( getAtributo(tag, "desc", "") );
		grupos.add( grupo );

		NodeList nl = tag.getChildNodes();
		for( int i=0; i<nl.getLength(); i++ ) {
			Node nd = nl.item(i);
			if( nd.getNodeType()!=Node.ELEMENT_NODE)
				continue;
			Element tag2 = (Element) nd;
			if( tag2.getNodeName().equals("item") ){
				try {
					popularItemInfo(grupo, tag2);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		}
	}

	private void popularItemInfo(GrupoItemInfo grupo, Element tag) {
		ItemInfo item = new ItemInfo();
		item.setId( getAtributo(tag, "id", "") );
		item.setName( getAtributo(tag, "name", "") );
		item.setEffect( getAtributo(tag, "effect", "") );

		String tmwbr = getAtributo(tag, "tmwbr", "");
		if( tmwbr.equals("true") )
			item.setTmwbr(Boolean.TRUE);
		else if( tmwbr.equals("false") )
			item.setTmwbr(Boolean.FALSE);
		else
			item.setTmwbr(null);

		grupo.getItems().add( item );

		NodeList nl = tag.getChildNodes();
		for( int i=0; i<nl.getLength(); i++ ) {
			Node nd = nl.item(i);
			if( nd.getNodeType()!=Node.ELEMENT_NODE)
				continue;
			Element tag2 = (Element) nd;
			if( tag2.getNodeName().equals("contrib") ){
				try {
					popularItemInfoContrib(item, tag2);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		}
	}

	private void popularItemInfoContrib(ItemInfo item, Element tag) {
		ItemInfoContrib contrib = new ItemInfoContrib();
		contrib.setType( getAtributo(tag, "type", "") );
		contrib.setName( getAtributo(tag, "name", "") );
		contrib.setDate( getAtributo(tag, "date", "") );
		contrib.setDesc( getAtributo(tag, "desc", "") );
		item.getContribs().add(contrib);
	}

	public List<GrupoItemInfo> getGrupos() {
		return grupos;
	}

	public void setGrupos(List<GrupoItemInfo> grupos) {
		this.grupos = grupos;
	}

}
