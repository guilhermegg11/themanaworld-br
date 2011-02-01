package converter;

import java.util.HashMap;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

public class ReadNpcScript {

	private HashMap<String, Npc> npcs = new HashMap<String, Npc>();
	private HashMap<String, String> scripts = new HashMap<String, String>();

	public ReadNpcScript() {}

	/**
	 * Carrega um arquivo XML e popula o objeto NpcScript.
	 * @param nomeArquivo Nome do arquivo XMl.
	 */
	public void carregarXML(String nomeArquivo){
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
		Npc npc = null;
		String idStr = null;
		String script = null;

		// pega todos os elementos 'script' do XML ====================
		NodeList nl = elem.getElementsByTagName("script");

		// percorre cada elemento 'script' encontrado
		for( int i=0; i<nl.getLength(); i++ ) {
			Element tag = (Element) nl.item(i);

			idStr = getAtributo(tag, "id", null);
			script = getValorTrim(tag);
			if(idStr!=null && !idStr.equals("") && script!=null && !script.equals("")){
				scripts.put(idStr, script);
			}
		}

		// pega todos os elementos 'npc' do XML ===================
		nl = elem.getElementsByTagName("npc");

		// percorre cada elemento 'npc' encontrado
		for( int i=0; i<nl.getLength(); i++ ) {
			Element tagNpc = (Element) nl.item(i);

			npc = new Npc();
			npc.setEnabled( getAtributo(tagNpc, "enabled", "") );
			npc.setId( getAtributo(tagNpc, "id", null) );
			if( npc.getId()==null ) continue;

			NodeList nl2 = tagNpc.getChildNodes();
			for( int j=0; j<nl2.getLength(); j++ ) {
				Node nd2 = nl2.item(j);
				if( nd2.getNodeType()!=Node.ELEMENT_NODE)
					continue;
				Element tag = (Element) nd2;
				try {
					if( tag.getNodeName().equals("script") ){
						popularScript(npc, tag);
					} else if( tag.getNodeName().equals("gerar_script") ){
						popularGerarScript(npc, tag);
					} else if( tag.getNodeName().equals("var") ){
						popularVar(npc, tag);
					}
				} catch (Exception e) {
					e.printStackTrace();
				}
			}

			npcs.put(npc.getId(), npc);
		 }

	}

	private void popularVar(Npc npc, Element tag) throws Exception {
		String name = getAtributo(tag, "name", null);
		String value = getAtributo(tag, "value", null);
		if(name==null || value==null)
			throw new Exception("Não foi possível encontrar name ou value para a tag var.");

		npc.getVars().put(name, value);
	}

	private void popularGerarScript(Npc npc, Element tag) throws Exception {
		String idKey = getAtributo(tag, "script", null);
		if(idKey==null)
			throw new Exception("Impossível gerar_script para o npc: " +npc.getId()+ ". Tag 'gerar_script' não possui atributo 'script'.");

		String script = scripts.get(idKey);
		if(script==null)
			throw new Exception("Impossível gerar_script para o npc: " +npc.getId()+ ". Não foi possível encontrar o script '" +idKey+ "'.");

		GrupoScript grupoScript = new GrupoScript();

		script = new String(script);
		NodeList nl = tag.getChildNodes();
		for( int i=0; i<nl.getLength(); i++ ) {
			if( nl.item(i).getNodeType()!=Node.ELEMENT_NODE )
				continue;
			Element tagVar = (Element) nl.item(i);
			if( tagVar.getNodeName().equals("var") ){
				try {
					script = popularVar(script, tagVar);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		}
		grupoScript.setScript(script);

		String str = getAtributo(tag, "grupos", null);
		if(str!=null && !str.equals("")) {
			String[] strs = str.split("\\,");
			for(int i=0; i<strs.length; i++){
				grupoScript.getGrupos().add(strs[i]);
			}
		}

		npc.getScripts().add(grupoScript);
	}

	private String popularVar(String script, Element var) throws Exception {
		String id = getAtributo(var, "id", null);
		String valor = getAtributo(var, "valor", null);
		if(id==null || valor==null)
			throw new Exception("Não foi possível encontrar id ou valor para a tag var.");

		return script.replace(id, valor);
	}

	/**
	 * Popula um objeto String com os dados da tag XML e insere na lista do objeto Npc.
	 */
	private void popularScript(Npc npc, Element tag) {
		String script = getValorTrim(tag);
		if(script==null || (script!=null&&script.equals("")))
			return;

		GrupoScript grupoScript = new GrupoScript();
		grupoScript.setScript(script);

		String str = getAtributo(tag, "grupos", null);
		if(str!=null && !str.equals("")) {
			String[] strs = str.split("\\,");
			for(int i=0; i<strs.length; i++){
				grupoScript.getGrupos().add(strs[i]);
			}
		}

		npc.getScripts().add( grupoScript );
	}

	/**
	 * Retorna uma propriedade da tag XML como Integer.
	 * @param elem Tag XML do tipo Node.ELEMENT_NODE
	 * @param atributo O nome do atributo
	 * @param padrao Valor padrão retornado caso a atributo não exista
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
	 * @param atributo O nome do atributo
	 * @param padrao Valor padrão retornado caso a atributo não exista
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
	 * @param padrao Valor padrão retornado caso a atributo não exista
	 */
	static public String getAtributo(Element elem, String atributo, String padrao){
		String str = elem.getAttribute(atributo);
		if( str==null )
			return padrao;
		return str;
	}

	/*private String getValor(Element tag) {
		if(tag.getFirstChild()!=null)
			return tag.getFirstChild().getNodeValue();
		return null;
	}*/

	private String getValorTrim(Element tag) {
		if(tag.getFirstChild()!=null)
			return tag.getFirstChild().getNodeValue().trim();
		return null;
	}

	public HashMap<String, Npc> getNpcs() {
		return npcs;
	}

	public HashMap<String, String> getScripts() {
		return scripts;
	}

}
