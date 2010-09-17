package converter;

import java.util.HashMap;

import javax.xml.parsers.*;
import org.w3c.dom.*;

public class MobScript {

	private HashMap<Integer, Mob> mobs = new HashMap<Integer, Mob>();
	private HashMap<String, String> scripts = new HashMap<String, String>();
	private HashMap<String, String> callsubs = new HashMap<String, String>();

	public MobScript() {}

	/**
	 * Carrega um arquivo XML e popula o objeto MobScript.
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
		Mob mob = null;
		String idStr = null;
		String script = null;

		// pega todos os elementos 'script' do XML ====================
		NodeList nl = elem.getElementsByTagName("script");

		// percorre cada elemento 'monstro' encontrado
		for( int i=0; i<nl.getLength(); i++ ) {
			Element tag = (Element) nl.item(i);

			idStr = getAtributo(tag, "id", null);
			script = getValorTrim(tag);
			if(idStr!=null && !idStr.equals("") && script!=null && !script.equals("")){
				scripts.put(idStr, script);
			}
		}

		// pega todos os elementos 'monstro' do XML ===================
		nl = elem.getElementsByTagName("monstro");

		// percorre cada elemento 'monstro' encontrado
		for( int i=0; i<nl.getLength(); i++ ) {
			Element tagMob = (Element) nl.item(i);

			mob = new Mob();
			mob.setId( getAtributo(tagMob, "id", -1) );
			if( mob.getId()<0 ) continue;

			NodeList nl2 = tagMob.getChildNodes();
			for( int j=0; j<nl2.getLength(); j++ ) {
				Node nd2 = nl2.item(j);
				if( nd2.getNodeType()!=Node.ELEMENT_NODE)
					continue;
				Element tag = (Element) nd2;
				if( tag.getNodeName().equals("contagem") ){
					popularContagem(mob, tag, mob.getId());
				} else if( tag.getNodeName().equals("callsub") ){
					popularCallsub(mob, tag);
				} else if( tag.getNodeName().equals("script") ){
					popularScript(mob, tag);
				} else if( tag.getNodeName().equals("gerar_script") ){
					try {
						popularGerarScript(mob, tag);
					} catch (Exception e) {
						e.printStackTrace();
					}
				}
			}

			mobs.put(mob.getId(), mob);
		 }

	}

	private void popularCallsub(Mob mob, Element tag) {
		MobCallsub sub = new MobCallsub();
		sub.setCallsub( getAtributo(tag, "label", null) );
		sub.setArgs( getAtributo(tag, "args", null) );
		mob.getScripts().add(sub);

		NodeList nl = tag.getChildNodes();
		for( int i=0; i<nl.getLength(); i++ ) {
			Node nd = nl.item(i);
			if( nd.getNodeType()!=Node.ELEMENT_NODE)
				continue;
			Element tag2 = (Element) nd;
			if( tag2.getNodeName().equals("gerar_label") ){
				popularGerarLabel(mob, tag2, sub.getCallsub());
			}
		}
	}

	private void popularGerarLabel(Mob mob, Element tag2, String callsub) {
		// FIXME: continuar daki...
	}

	private void popularGerarScript(Mob mob, Element tag) throws Exception {
		String idKey = getAtributo(tag, "script", null);
		if(idKey==null)
			throw new Exception("Impossível gerar_script para o monstro: " +mob.getId()+ ". Tag 'gerar_script' não possui atributo 'script'.");

		String script = scripts.get(idKey);
		if(script==null)
			throw new Exception("Impossível gerar_script para o monstro: " +mob.getId()+ ". Não foi possível encontrar o script '" +idKey+ "'.");

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
		mob.getScripts().add(script);
	}

	private String popularVar(String script, Element var) throws Exception {
		String id = getAtributo(var, "id", null);
		String valor = getAtributo(var, "valor", null);
		if(id==null || valor==null)
			throw new Exception("Não foi possível encontrar id ou valor para a tag var.");

		return script.replace(id, valor);
	}

	/**
	 * Popula um objeto String com os dados da tag XML e insere na lista do objeto Mob.
	 */
	private void popularScript(Mob mob, Element tag) {
		String script = getValorTrim(tag);
		if(script==null)
			return;
		mob.getScripts().add( script.trim() );
	}

	/**
	 * Popula um objeto MobContagem com os dados da tag XML e insere na lista do objeto Mob.
	 */
	private void popularContagem(Mob mob, Element tag, int idMob) {
		MobContagem cont = new MobContagem();
		cont.setIdMob(idMob);
		cont.setCallsub( getAtributo(tag, "callsub", "?") );
		cont.setReturn( getAtributo(tag, "return", "?") );
		cont.setMax( getAtributo(tag, "max", 0) );
		cont.setVarMobs( getAtributo(tag, "var_mobs", "?") );
		cont.setVarFlag( getAtributo(tag, "var_flag", "?") );
		mob.getScripts().add(cont);
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

	public HashMap<Integer, Mob> getMobs() {
		return mobs;
	}

	public HashMap<String, String> getScripts() {
		return scripts;
	}

	public HashMap<String, String> getCallsubs() {
		return callsubs;
	}

}
