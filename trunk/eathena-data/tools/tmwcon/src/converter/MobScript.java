package converter;

import java.util.HashMap;

import javax.xml.parsers.*;
import org.w3c.dom.*;

public class MobScript {

	private HashMap<Integer, Mob> mobs = null;

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

		mobs = new HashMap<Integer, Mob>();
		Element elem = doc.getDocumentElement();
		Mob mob = null;

		// pega todos os elementos 'monstro' do XML
		NodeList nl = elem.getElementsByTagName("monstro");

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
				} else if( tag.getNodeName().equals("script") ){
					popularScript(mob, tag);
				}
			}

			mobs.put(mob.getId(), mob);
		 }

	}

	/**
	 * Popula um objeto String com os dados da tag XML e insere na lista do objeto Mob.
	 */
	private void popularScript(Mob mob, Element tag) {
		String script = tag.getFirstChild().getNodeValue();
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
		mob.getMobContagems().add(cont);
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
			str = padrao;
		return str;
	}

	public HashMap<Integer, Mob> getMobs() {
		return mobs;
	}

}
