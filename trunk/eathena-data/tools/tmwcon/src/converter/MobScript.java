package converter;

import java.util.TreeSet;

/**
 * Objeto de uso individual da classe Mob. Para ser inserido em Mob.scripts do tipo List<Object>.
 * Não é utilizado na classe ReadMobScript.
 * @author diogorbg - http://diogorbg.blogspot.com/
 */
public class MobScript {

	String script;
	TreeSet<String> grupos = new TreeSet<String>();

	public MobScript() {}

	public String getScript() {
		return script;
	}

	public void setScript(String script) {
		this.script = script;
	}

	public TreeSet<String> getGrupos() {
		return grupos;
	}

	public boolean contemGrupo(String grupo){
		if(grupos.size()<1)
			return true;
		return grupos.contains(grupo);
	}

}
