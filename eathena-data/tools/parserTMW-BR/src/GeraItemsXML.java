import geraItemsXML.ReadItemsXML;

import java.io.PrintStream;

import parser.Parser;

public class GeraItemsXML {

	static PrintStream out = System.out;

	public static void main (String args[]) {
		Parser txtItens = new Parser();
		ReadItemsXML xmlItens = new ReadItemsXML();

		try{
			txtItens.carregarItens("item_db.txt");
			xmlItens.carregarXML("items.xml");
		}catch (Exception e) {
			e.printStackTrace();
		}
	}

}
