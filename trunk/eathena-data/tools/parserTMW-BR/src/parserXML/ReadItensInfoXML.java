package parserXML;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import com.sun.org.apache.xerces.internal.dom.DeferredAttrImpl;

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

		// pega todos os elementos 'grupo' do XML ====================
		NodeList nl = elem.getElementsByTagName("grupo");
//		DeferredAttrImpl def = null;
//		HashMap<String, String> atribs = null;
	}

	public List<GrupoItemInfo> getGrupos() {
		return grupos;
	}

	public void setGrupos(List<GrupoItemInfo> grupos) {
		this.grupos = grupos;
	}

}
