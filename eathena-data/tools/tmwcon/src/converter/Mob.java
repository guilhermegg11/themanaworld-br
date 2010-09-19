package converter;

import java.util.ArrayList;
import java.util.List;

public class Mob implements Comparable<Mob>{

	private int id = -1;
	private String grupo; //< Uso indevidual
	private List<Object> scripts = new ArrayList<Object>();
	private List<MobSpawn> spawns = new ArrayList<MobSpawn>(); //< Uso genérico

	public Mob() {}

	public Mob(int mob) {
		this.id = mob;
	}

	public int compareTo(Mob mob) {
		return getIdGrupo().compareTo(mob.getIdGrupo());
	}

	public String getIdGrupo(){
		return id + (grupo!=null&&!grupo.equals("") ? "_"+grupo : "");
	}

	public int getId() {
		return id;
	}

	public void setId(int mob) {
		this.id = mob;
	}

	public List<Object> getScripts() {
		return scripts;
	}

	public List<MobSpawn> getSpawns() {
		return spawns;
	}

	static public String paraString(Object obj){
		if(obj instanceof String)
			return (String) obj;
		return null;
	}

	static public MobCallsub paraMobCallsub(Object obj){
		if(obj instanceof MobCallsub)
			return (MobCallsub) obj;
		return null;
	}

	public String getGrupo() {
		return grupo;
	}

	public void setGrupo(String grupo) {
		this.grupo = grupo;
	}

}
