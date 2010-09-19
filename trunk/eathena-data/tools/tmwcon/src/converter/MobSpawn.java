package converter;

import java.util.TreeSet;

public class MobSpawn {

	private Integer tSpawn = null;
	private Integer tMorte = null;
	TreeSet<String> grupos = new TreeSet<String>();
	TreeSet<String> mapas = new TreeSet<String>();

	public MobSpawn() {}

	public Integer gettSpawn() {
		return tSpawn;
	}

	public void settSpawn(Integer tSpawn) {
		this.tSpawn = tSpawn;
	}

	public Integer gettMorte() {
		return tMorte;
	}

	public void settMorte(Integer tMorte) {
		this.tMorte = tMorte;
	}

	public TreeSet<String> getGrupos() {
		return grupos;
	}

	public TreeSet<String> getMapas() {
		return mapas;
	}

}
