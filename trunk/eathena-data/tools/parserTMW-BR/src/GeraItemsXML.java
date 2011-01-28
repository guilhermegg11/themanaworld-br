import geraItemsXML.ReadItemsXML;

import java.io.PrintStream;
import java.util.HashMap;

import parser.Item;
import parser.Parser;

public class GeraItemsXML {

	static PrintStream out = System.out;

	public static void main (String args[]) {
		Parser txtItens = new Parser();
		ReadItemsXML xmlItens = new ReadItemsXML();

		try {
			txtItens.carregarItens("../../db/item_db.txt");
			xmlItens.carregarXML("../../../tmwdata/items.xml");
		} catch (Exception e) {
			e.printStackTrace();
		}

		try {
			gerarConteudoHTML(txtItens, xmlItens);
		} catch (Exception e) {
			e.printStackTrace();
		}

	}

	private static void gerarConteudoHTML(Parser txtItens, ReadItemsXML xmlItens) {
		HashMap<String,String> item2;

		out.println("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">");
		out.println("<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">");
		out.println("");
		out.println("<head>");
		out.println("	<title>Armas de uma mão</title>");
		out.println("	<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\" />");
		out.println("	<meta name=\"generator\" content=\"Geany 0.18\" />");
		out.println("	<link href=\"estilo.css\" type=\"text/css\" rel=\"stylesheet\"/>");
		out.println("</head>");
		out.println("<body>");
		out.println("	<table class=\"borda\">");
		out.println("		<tr align=\"center\" style=\"font-weight:bold;\">");
		out.println("			<td><a href=\"armas1.html\">Armas 1</a></td>");
		out.println("			<td><a href=\"armas2.html\">Armas 2</a></td>");
		out.println("		</tr>");
		out.println("	</table>");
		out.println("	<table class=\"borda\">");
		out.println("	<caption>Armas de uma mão</caption>");
		out.println("		<tr align=\"center\" style=\"font-weight:bold;\">");
		out.println("			<td></td>");
		out.println("			<td>Id</td>");
		out.println("			<td>Nome</td>");
		out.println("			<td>Dano</td>");
		out.println("			<td>Alcance</td>");
		out.println("			<td>Compra/Venda</td>");
		out.println("			<td>Peso</td>");
		out.println("			<td>Descrição</td>");
		//out.println("			<td>Raridade</td>");
		out.println("		</tr>");
		for( Item item1 : txtItens.getItens() ){
			if(item1.getTipo().equals("4")==false)
				continue;
			item2 = xmlItens.getItem(item1.getId());
			if(item2==null)
				continue;
			out.println("		<tr>");
			out.println("			<td><img src=\"itens/"+item1.getId()+".png\" width=\"32\" height=\"32\"/></td>");
			out.println("			<td>"+item1.getId()+"</td>");
			out.println("			<td>"+item2.get("name")+( Integer.valueOf(item1.getId()).compareTo(3000)>=0?" <img src=\"icos/tmwbr.png\" title=\"Item exclusido do TMW-BR\"/>":"" )+"</td>"); // <img src=\"icos/autor.png\" title=\"Fulano (09/10/2010), Beltrano (01/01/2011)\"/>
			out.println("			<td align=\"center\">"+item1.getAtaque()+"</td>");
			out.println("			<td align=\"center\">"+item1.getAlcance()+"</td>");
			out.println("			<td align=\"right\">"+item1.getCompra()+" GP<br/>"+item1.getVenda()+" GP</td>");
			out.println("			<td align=\"right\">"+item1.getPeso()+" g</td>");
			out.println("			<td>"+item2.get("description")+"<br/><font color=\"#8080A0\">"+item2.get("effect")+"</font></td>");
			//out.println("			<td>quest</td>");
			out.println("		</tr>");
		}
		out.println("	</table>");
		out.println("</body>");
		out.println("</html>");
	}

}
