package converter;

import java.util.ArrayList;
import java.util.List;

public class Mob implements Comparable<Mob>{

	private int id = -1;
	private int x;
	private int y;
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

	public int getX() {
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
	}

	public List<Object> getScripts() {
		return scripts;
	}

	public List<MobSpawn> getSpawns() {
		return spawns;
	}

	static public MobScript paraMobScript(Object obj){
		if(obj instanceof MobScript)
			return (MobScript) obj;
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
