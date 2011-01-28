package parserXML;

import java.util.ArrayList;
import java.util.List;

public class ItemInfo {

	private Integer id;
	private String nome;
	private Boolean tmwbr = null; //< O item é exclusivo do TMW-BR ?

	//- Lista de contribuições.
	List<ItemInfoContrib> contribs = new ArrayList<ItemInfoContrib>();

	public Integer getId() {
		return id;
	}

	public void setId(Integer id) {
		this.id = id;
	}

	public String getNome() {
		return nome;
	}

	public void setNome(String nome) {
		this.nome = nome;
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
