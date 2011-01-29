package parserXML;

import java.util.ArrayList;
import java.util.List;

public class ItemInfo {

	private String id;

	private String name;

	private String effect;

	private Boolean tmwbr = null; //< O item é exclusivo do TMW-BR ?

	//- Lista de contribuições.
	List<ItemInfoContrib> contribs = new ArrayList<ItemInfoContrib>();

	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getEffect() {
		return effect;
	}

	public void setEffect(String effect) {
		this.effect = effect;
	}

	public Boolean getTmwbr() {
		return tmwbr;
	}

	public void setTmwbr(Boolean tmwbr) {
		this.tmwbr = tmwbr;
	}

	public List<ItemInfoContrib> getContribs() {
		return contribs;
	}

	public void setContribs(List<ItemInfoContrib> contribs) {
		this.contribs = contribs;
	}

}
