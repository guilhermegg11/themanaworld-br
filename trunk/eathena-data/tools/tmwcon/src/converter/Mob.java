package converter;

//import java.util.ArrayList;
import java.util.ArrayList;
import java.util.List;

public class Mob implements Comparable<Mob>{

	private int mob = -1;
	private String script;
	private List<MobContagem> lMobContagem = new ArrayList<MobContagem>();

	public Mob() {}

	public Mob(int mob) {
		this.mob = mob;
	}

	public int compareTo(Mob mob) {
		if( getMob()<mob.getMob() ) return -1;  
		else if( getMob()>mob.getMob() ) return +1;  
		else return 0;
	}

	//static public void carregarlMobComtagem(TreeSet<Integer> mobs){}

	public int getMob() {
		return mob;
	}

	public void setMob(int mob) {
		this.mob = mob;
	}

	public String getScript() {
		return script;
	}

	public void setScript(String script) {
		this.script = script;
	}

	public List<MobContagem> getlMobContagem() {
		return lMobContagem;
	}

	public void setlMobContagem(List<MobContagem> lMobComtagem) {
		this.lMobContagem = lMobComtagem;
	}

}
