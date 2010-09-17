package converter;

import java.util.ArrayList;
import java.util.List;

public class Mob implements Comparable<Mob>{

	private int id = -1;
	private List<Object> scripts = new ArrayList<Object>();

	public Mob() {}

	public Mob(int mob) {
		this.id = mob;
	}

	public int compareTo(Mob mob) {
		if( getId()<mob.getId() ) return -1;  
		else if( getId()>mob.getId() ) return +1;  
		else return 0;
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

	static public MobContagem paraMobContagem(Object obj){
		if(obj instanceof MobContagem)
			return (MobContagem) obj;
		return null;
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

}
