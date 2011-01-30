import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintStream;
import java.io.PrintWriter;
import java.nio.channels.FileChannel;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;

import parserTXT.Item;
import parserTXT.Parser;
import parserXML.GrupoItemInfo;
import parserXML.ItemInfo;
import parserXML.ItemInfoContrib;
import parserXML.ReadItemsXML;
import parserXML.ReadItensInfoXML;

public class GeraItemsXML {

	static PrintStream log = System.out;

	public static void main (String args[]) {
		Parser txtItens = new Parser();
		ReadItemsXML xmlItens = new ReadItemsXML();
		ReadItensInfoXML xmlInfos = new ReadItensInfoXML();

		try {
			txtItens.carregarItens("../../db/item_db.txt");
			xmlItens.carregarXML("../../../tmwdata/items.xml");
			xmlInfos.carregarXML("infoItens.xml");

			gerarConteudoHTML(xmlInfos, txtItens, xmlItens);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	private static void gerarConteudoHTML(ReadItensInfoXML xmlInfos, Parser txtItens, ReadItemsXML xmlItens) {
		File file = null;
		FileWriter fileW = null;

		//- Gera um HashMap da lista txtItens para facilitar o aacesso direto.
		HashMap<String,Item> hashTxtItens = new HashMap<String,Item>();
		for( Item item : txtItens.getItens() ) {
			hashTxtItens.put(item.getId(), item);
		}

		String links = "";
		for( GrupoItemInfo grupo : xmlInfos.getGrupos() ) {
			if(grupo.getId().length()==0)
				grupo.setId( grupo.getType() );
			links += "\t\t\t<td><a href=\""+grupo.getId()+".html\">"+grupo.getName()+"</a></td>\n";
		}
		for( GrupoItemInfo grupo : xmlInfos.getGrupos() ) {
			file = new File("wiki/infoItens/"+grupo.getId()+".html");
			try {
				fileW = new FileWriter(file);
			} catch (IOException e1) {
				log.println("Arquivo '"+file+"' não pode ser criado.");
				e1.printStackTrace();
			}
			gerarConteudoHTML(new PrintWriter(fileW), links, grupo, hashTxtItens, xmlItens);
		}
	}

	private static void gerarConteudoHTML(PrintWriter out, String links, GrupoItemInfo grupo, HashMap<String,Item> txtItens, ReadItemsXML xmlItens) {
		out.println("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">");
		out.println("<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">");
		out.println("");
		out.println("<head>");
		out.println("	<title>"+grupo.getDesc()+"</title>");
		out.println("	<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\" />");
		out.println("	<meta name=\"generator\" content=\"Geany 0.18\" />");
		out.println("	<link href=\"estilo.css\" type=\"text/css\" rel=\"stylesheet\"/>");
		out.println("</head>");
		out.println("<body>");
		out.println("	<table class=\"borda\">");
		out.println("		<tr align=\"center\" style=\"font-weight:bold;\">");
		out.print(links);
		out.println("		</tr>");
		out.println("	</table>");
		out.println("	<table class=\"borda\">");
		out.println("	<caption>"+grupo.getDesc()+"</caption>");
		out.println("		<tr align=\"center\" style=\"font-weight:bold;\">");
		out.println("			<td></td>");
		out.println("			<td>Nome</td>");
		out.println("			<td>Id</td>");
		out.println("			<td>Dano</td>");
		out.println("			<td>Alcance</td>");
		out.println("			<td>Compra/Venda</td>");
		out.println("			<td>Peso</td>");
		out.println("			<td>Descrição</td>");
		//out.println("			<td>Raridade</td>");
		out.println("		</tr>");

		Item item1 = null;
		HashMap<String,String> item2 = null;
		String strTmwbr;
		String strContrib;
		for( ItemInfo item : grupo.getItems() ){
			item1 = txtItens.get(item.getId());
			if( item1==null ) {
				log.println("# Item "+item.getId()+" '"+item.getName()+"' não encontrado em item_db.txt!");
				continue;
			}
			item2 = xmlItens.getItem(item.getId());
			if( item2==null ) {
				log.println("# Item "+item.getId()+" '"+item.getName()+"' não encontrado em items.xml!");
				continue;
			}

			strTmwbr = "";
			if( item.getTmwbr()!=null && item.getTmwbr()==Boolean.TRUE ) {
				strTmwbr = "<br/><img src=\"../icos/tmwbr.png\" title=\"Item exclusido do TMW-BR\"/>";
			}
			strContrib = "";
			for(ItemInfoContrib contrib : item.getContribs()) {
				if( strContrib.length()>0 )
					strContrib += ", ";
				strContrib += contrib.getName();
			}
			if( strContrib.length()>0 ) {
				strContrib = "<img src=\"../icos/autor.png\" title=\""+strContrib+"\"/>";
				if(strTmwbr.length()==0)
					strTmwbr = "<br/>";
			}

			out.println("		<tr>");
			out.println("			<td><img src=\"../itens/"+item1.getId()+".png\" width=\"32\" height=\"32\"/></td>");
			out.println("			<td>"+item2.get("name")+"</td>");
			out.println("			<td>"+item1.getId()+strTmwbr+strContrib+"</td>");
			out.println("			<td align=\"center\">"+item1.getAtaque()+"</td>");
			out.println("			<td align=\"center\">"+item1.getAlcance()+"</td>");
			out.println("			<td align=\"right\">"+item1.getCompra()+" GP<br/>"+item1.getVenda()+" GP</td>");
			out.println("			<td align=\"right\">"+item1.getPeso()+" g</td>");
			out.println("			<td>"+item2.get("description")+"<br/><font color=\"#8080A0\">"+item2.get("effect")+"</font></td>");
			//out.println("			<td>quest</td>");
			out.println("		</tr>");

			File img1;
			File img2;
			File dyecmd = new File("./dyecmd");
			String[] image = item2.get("image").split("\\|");
			if(image!=null) {
				img1 = new File("../../../tmwdata/graphics/items/"+image[0]);
				img2 = new File("wiki/itens/"+item.getId()+".png");
				if(img2.exists()==false && img1.exists()) {
					if(image.length>1 && dyecmd.exists()) {
						//- Faz uma cópia colorizada da imagem.
						try {
							String line = "./dyecmd "+img1.getPath()+" "+img2.getPath()+" "+image[1];
							//log.println("---\n"+line);
							//Process exec =
							Runtime.getRuntime().exec(line);
							//BufferedReader input = new BufferedReader(new InputStreamReader(exec.getInputStream()));
							//while ((line = input.readLine()) != null) {
							//	log.println(line);
							//}
							//input.close();
						} catch ( FileNotFoundException e ) {
							System.out.println("Arquivo não encontrado.");
						} catch ( IOException e ) {
							System.out.println("Entrada inválida.");
						} catch ( Exception e ) {
							e.printStackTrace();
						}
					} else {
						//- Apenas faz uma cópia da imagem.
						try {
							copy(img1, img2);
						} catch (IOException e) {
							log.println("# Imagem '"+img1+"' não pôde ser copiada para '"+img2+"'!");
							e.printStackTrace();
						}
					}
				} else if(img2.exists()==false && img1.exists()==false) {
					log.println("# Imagem '"+img1+"' para o item "+item.getId()+" '"+item.getName()+"' não pôde ser encontrada!");
				}
			}

		}
		out.println("	</table>");
		out.println("</body>");
		out.println("</html>");

		out.flush();
		out.close();
	}

	private static void copy(File origem, File destino) throws IOException{
		FileInputStream fisOrigem = new FileInputStream(origem);
		FileOutputStream fisDestino = new FileOutputStream(destino);
		FileChannel fcOrigem = fisOrigem.getChannel();
		FileChannel fcDestino = fisDestino.getChannel();
		fcOrigem.transferTo(0, fcOrigem.size(), fcDestino);
		fisOrigem.close();
		fisDestino.close();
	}	

}
