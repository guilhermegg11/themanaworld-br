package parserXML;

import java.util.ArrayList;
import java.util.List;

public class GrupoItemInfo {

	private String type;

	private String id;

	private String name;

	private String desc;

	private List<ItemInfo> items = new ArrayList<ItemInfo>();

	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}

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

	public String getDesc() {
		return desc;
	}

	public void setDesc(String desc) {
		this.desc = desc;
	}

	public List<ItemInfo> getItems() {
		return items;
	}

	public void setItems(List<ItemInfo> items) {
		this.items = items;
	}

}
