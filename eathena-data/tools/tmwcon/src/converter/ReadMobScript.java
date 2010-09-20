package converter;

import java.util.HashMap;

import javax.xml.parsers.*;
import org.w3c.dom.*;

public class ReadMobScript {

	private HashMap<Integer, Mob> mobs = new HashMap<Integer, Mob>();
	private HashMap<String, String> scripts = new HashMap<String, String>();
	private HashMap<String, String> callsubs = new HashMap<String, String>();

	public ReadMobScript() {}

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

		// percorre cada elemento 'script' encontrado
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
				try {
					if( tag.getNodeName().equals("spawn") ){
						popularSpawn(mob, tag);
					} else if( tag.getNodeName().equals("callsub") ){
						popularCallsub(mob, tag);
					} else if( tag.getNodeName().equals("script") ){
						popularScript(mob, tag);
					} else if( tag.getNodeName().equals("gerar_script") ){
						popularGerarScript(mob, tag);
					}
				} catch (Exception e) {
					e.printStackTrace();
				}
			}

			mobs.put(mob.getId(), mob);
		 }

	}

	private void popularSpawn(Mob mob, Element tag) {
		MobSpawn spawn = new MobSpawn();
		spawn.settSpawn( getAtributoInteger(tag, "tempo_spawn", null) );
		spawn.settMorte( getAtributoInteger(tag, "tempo_morte", null) );

		String str;
		String[] strs;
		int i;

		str = getAtributo(tag, "grupos", null);
		if(str!=null && !str.equals("")) {
			strs = str.split("\\,");
			for(i=0; i<strs.length; i++){
				spawn.getGrupos().add(strs[i]);
			}
		}
		mob.getSpawns().add(spawn);
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
				try {
					popularGerarLabel(mob, tag2, sub.getCallsub());
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		}
	}

	private void popularGerarLabel(Mob mob, Element tag, String callsub) throws Exception {
		String idKey = getAtributo(tag, "script", null);
		if(idKey==null)
			throw new Exception("Impossível gerar_label para o monstro: " +mob.getId()+ ". Tag 'gerar_label' não possui atributo 'script'.");

		String script = scripts.get(idKey);
		if(script==null)
			throw new Exception("Impossível gerar_label para o monstro: " +mob.getId()+ ". Não foi possível encontrar o script '" +idKey+ "'.");

		if(callsubs.containsKey(callsub))
			throw new Exception("Impossível gerar_label para o monstro: " +mob.getId()+ ". Já existe uma label com o nome '" +callsub+ "'.");

		script = new String(callsub+":\n\t"+script+"\n\treturn;");

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
		callsubs.put(callsub, script);
	}

	private void popularGerarScript(Mob mob, Element tag) throws Exception {
		String idKey = getAtributo(tag, "script", null);
		if(idKey==null)
			throw new Exception("Impossível gerar_script para o monstro: " +mob.getId()+ ". Tag 'gerar_script' não possui atributo 'script'.");

		String script = scripts.get(idKey);
		if(script==null)
			throw new Exception("Impossível gerar_script para o monstro: " +mob.getId()+ ". Não foi possível encontrar o script '" +idKey+ "'.");

		MobScript mobScript = new MobScript();

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
		mobScript.setScript(script);

		String str = getAtributo(tag, "grupos", null);
		if(str!=null && !str.equals("")) {
			String[] strs = str.split("\\,");
			for(int i=0; i<strs.length; i++){
				mobScript.getGrupos().add(strs[i]);
			}
		}

		mob.getScripts().add(mobScript);
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
		if(script==null || (script!=null&&script.equals("")))
			return;

		MobScript mobScript = new MobScript();
		mobScript.setScript(script);

		String str = getAtributo(tag, "grupos", null);
		if(str!=null && !str.equals("")) {
			String[] strs = str.split("\\,");
			for(int i=0; i<strs.length; i++){
				mobScript.getGrupos().add(strs[i]);
			}
		}

		mob.getScripts().add( mobScript );
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
