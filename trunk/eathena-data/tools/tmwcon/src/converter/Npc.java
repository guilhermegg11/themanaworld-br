package converter;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

public class Npc implements Comparable<Npc>{

	private String id;
//	private int x;
//	private int y;
	private String grupo; //< Uso individual
	private List<Object> scripts = new ArrayList<Object>();
	private HashMap<String, String> vars = new HashMap<String, String>();

	public Npc() {}

	public Npc(String id) {
		this.id = id;
	}

	public int compareTo(Npc npc) {
		return getIdGrupo().compareTo(npc.getIdGrupo());
	}

	public String getIdGrupo(){
		return id + (grupo!=null&&!grupo.equals("") ? "_"+grupo : "");
	}

	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}

/*	public int getX() {
		return x;
	}

	public void setX(int x) {
		this.x = x;
	}

	public int getY() {
		return y;
	}

	public void setY(int y) {
		this.y = y;
	}*/

	public List<Object> getScripts() {
		return scripts;
	}

	static public GrupoScript paraGrupoScript(Object obj){
		if(obj instanceof GrupoScript)
			return (GrupoScript) obj;
		return null;
	}

	public String getGrupo() {
		return grupo;
	}

	public void setGrupo(String grupo) {
		this.grupo = grupo;
	}

	public HashMap<String, String> getVars() {
		return vars;
	}

	/**
	 * Retorna o valor de uma variável... ou o valor default caso ela não exista.
	 */
	public String getVar(String name, String def){
		String ret = vars.get(name);
		if(ret==null)
			ret = def;
		return ret;
	}

}
