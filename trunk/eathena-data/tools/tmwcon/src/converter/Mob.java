package converter;

import java.util.ArrayList;
import java.util.List;

public class Mob implements Comparable<Mob>{

	private int id = -1;
	private List<String> scripts = new ArrayList<String>();
	private List<MobContagem> mobContagems = new ArrayList<MobContagem>();

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

	public List<String> getScripts() {
		return scripts;
	}

	public List<MobContagem> getMobContagems() {
		return mobContagems;
	}

}
