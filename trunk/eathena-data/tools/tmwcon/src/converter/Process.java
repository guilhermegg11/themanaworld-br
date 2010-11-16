/*
 * TMWServ to eAthena Converter (c) 2008 Jared Adams
 * License: GPL, v2 or later
 */

package converter;

import java.awt.*;
import java.io.*;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Properties;
import java.util.ArrayList;
import java.util.List;
import java.util.Collections;
import java.util.TreeSet;

import tiled.core.*;
//import tiled.plugins.tmw.*;

public class Process {
    private static final String baseFolder = "server-data/";
    private static final File _baseFolder = new File(baseFolder);
    private static final String scriptDirectory = "npc/";
    private static final String mobFile = "_mobs.txt";
    private static final String warpFile = "_warps.txt";
    private static final String checkFile = "_checks.txt";
    private static final String importFile = "_import.txt";

    private static WLKInterface wlk = null;

    public static void prepWLK(File folder) {
        try {
            wlk = new WLKInterface(folder);
        } catch (NoClassDefFoundError ncdfe) {}
    }

    private static String getProp(Properties props, String name, String def) {
        if (name == null) return def;
        for (java.util.Map.Entry<Object, Object> entry : props.entrySet()) {
            if (name.equalsIgnoreCase(entry.getKey().toString())) {
                return entry.getValue().toString();
            }
        }
        return def;
    }

    private static int getProp(Properties props, String name, int def) {
        if (name == null) return def;
        try {
            return Integer.parseInt(getProp(props, name, "?"));
        } catch (Exception e) {}
        return def;
    }

    private static int[] resolveBounds(Rectangle in, boolean warp) {
        int x = in.x / 32;
        int y = in.y / 32;
        int width = in.width / 32;
        int height = in.height / 32;
        if (!warp) {
            if (width > 0) --width;
            if (height > 0) --height;
        }
        x += (width>0?width:1) / 2;
        y += (height>0?height:1) / 2;
        if (warp) {
            width -= 2;
            height -= 2;
        }
        return new int[]{x, y, width, height};
    }

	private static void handleWarp(PrintWriter out, String map, String name, Rectangle bounds, Properties props) {
		if (out == null) return;
		String dest = getProp(props, "dest_map", null);
		if (dest == null) return;
		int x = getProp(props, "dest_x", -1);
		int x32 = getProp(props, "dest_x32", -1);
		if( x>=0 ) x /= 32;
		else if( x32>=0 ) x = x32;
		else return;
		int y = getProp(props, "dest_y", -1);
		int y32 = getProp(props, "dest_y32", -1);
		if( y>=0 ) y /= 32;
		else if( y32>=0 ) y = y32;
		else return;
		int[] shape = resolveBounds(bounds, true);
		System.out.printf("Usable warp found: %s\n", name);
		out.printf("%s.gat,%d,%d\twarp\t%s\t%d,%d,%s.gat,%d,%d\n", map, shape[0], shape[1], name, shape[2], shape[3], dest, x, y);
	}

	private static void handleCheck(PrintWriter out, String map, String name, Rectangle bounds, Properties props) {
		if (out == null)
			return;
		String end = getProp(props, "end", null);
		String nameScript = getProp(props, "name", null);
		String script = getProp(props, "script", null);
		String script1 = getProp(props, "script1", null);
		String script2 = getProp(props, "script2", null);
		String script3 = getProp(props, "script3", null);
		int[] shape = resolveBounds(bounds, false);
		if(nameScript==null)
			nameScript = "#"+map+"_"+shape[0]+"x"+shape[1];
		System.out.printf("Check Point: %s\n", name);
		out.printf("\n// Ponto de checagem: %s\n", name);
		out.printf("%s.gat,%d,%d,0\tscript\t%s\t0,%d,%d,{\n", map, shape[0], shape[1], nameScript, shape[2]/2, shape[3]/2);
		out.printf("\tset @map$, \"%s\";\n", map);
		out.printf("\tset @x, %d;\n", shape[0]);
		out.printf("\tset @y, %d;\n", shape[1]);
		if(script!=null)
			out.printf("\t%s\n", script);
		if(script1!=null)
			out.printf("\t%s\n", script1);
		if(script2!=null)
			out.printf("\t%s\n", script2);
		if(script3!=null)
			out.printf("\t%s\n", script3);
		if(end!=null)
			out.printf("\t%s;\n}\n", end);
		else
			out.printf("\tend;\n}\n");
	}

	private static Mob handleMob(PrintWriter out, String map, String name, Rectangle bounds, Properties props, ReadMobScript mobScript) {
		if (out == null) return new Mob(-1);
		int mob = getProp(props, "monster_id", -1);
		int jazida = getProp(props, "jazida", -1);
		if (mob<0 && jazida<0) return new Mob(-1);
		if(mob>=0)
			mob += 1002;
		else
			mob = jazida+1324;

		Integer tSpawn = null;
		Integer tMorte = null;
		Mob retMob = new Mob(mob);
		retMob.setGrupo( getProp(props, "grupo", "") );

		Mob mob2 = mobScript.getMobs().get(mob);
		if(mob2!=null){
			for( MobSpawn spawn : mob2.getSpawns() ){
				if(spawn.getGrupos().contains( retMob.getGrupo() )){
					if(tSpawn==null)
						tSpawn = spawn.gettSpawn();
					if(tMorte==null)
						tMorte = spawn.gettMorte();
				}
			}
		}

		int max = getProp(props, "max_beings", 1);
		int time1 = getProp(props, "eA_spawn", (tSpawn!=null?tSpawn*1000:0) );
		int time2 = getProp(props, "eA_death", (tMorte!=null?tMorte*1000:0) );
		int[] shape = resolveBounds(bounds, false);
		System.out.printf("Usable mob found: %s (%d)%s\n", name, mob, retMob.getGrupo().length()>0?" grupo:"+retMob.getGrupo():"");
		out.printf("%s.gat,%d,%d,%d,%d\tmonster\t%s\t%d,%d,%d,%d,Mob%s::On%s\n", map, shape[0], shape[1], shape[2], shape[3], name, mob, max, time1, time2, map, retMob.getIdGrupo());

		retMob.setX(shape[0]);
		retMob.setY(shape[1]);
		String script = getProp(props, "script", null);
		if(script!=null) {
			retMob.getScripts().add(script);
		}
		return retMob;
	}

    private static void processObject(MapObject mo, String map, PrintWriter warpOut, PrintWriter checkOut, PrintWriter mobOut, TreeSet<Mob> mobs, ReadMobScript mobScript) {
        if (mo == null) return;
        String name = mo.getName();
        String type = mo.getType();
        Rectangle bounds = new Rectangle(mo.getBounds());
        Properties props = mo.getProperties();

        if (type.equalsIgnoreCase("warp")) {
            handleWarp(warpOut, map, name, bounds, props);
        } else if (type.equalsIgnoreCase("check") && checkOut!=null) {
            handleCheck(checkOut, map, name, bounds, props);
        } else if (type.equalsIgnoreCase("spawn")) {
            mobs.add(handleMob(mobOut, map, name, bounds, props, mobScript));
        }
    }

	private static void processObjects(Iterator<MapObject> objs, String map, PrintWriter warpOut, PrintWriter checkOut, PrintWriter mobOut, TreeSet<Mob> mobs, ReadMobScript mobScript) {
        MapObject mo;
        while (objs.hasNext()) {
            mo = objs.next();
            if (mo == null) continue;
            processObject(mo, map, warpOut, checkOut, mobOut, mobs, mobScript);
        }
    }

    private static void processFiles(File folder, List<String> out) {
        for (File f : folder.listFiles()) {
            if (f.isDirectory()) {
            	if (!f.getName().equals(".svn"))
            		processFiles(folder, out);
            } else if (!f.getName().equals(importFile)) {
            	if (f.getName().getBytes()[0]=='-')
                    out.add("//npc: " + f.getPath().substring(_baseFolder.getPath().length() + 1));
            	else
            		out.add("npc: " + f.getPath().substring(_baseFolder.getPath().length() + 1));
            }
        }
    }

    static public String processMap(String name, Map map, PrintWriter summary, ReadMobScript readMobScript) {
        if (name == null) return null;
        if (map == null) return null;

        Properties props = map.getProperties();
        String title = getProp(props, "name", "");
        String pasta = getProp(props, "pasta", "");
        if (summary != null) {
            summary.printf("\tName: '%s'\n", title);
            summary.printf("\tMusic: '%s'\n", getProp(props, "music", ""));
            summary.printf("\tMinimap: '%s'\n", getProp(props, "minimap", ""));
        }
        String folderName =  scriptDirectory + name + (pasta.length()==0 ? "" : "_"+pasta);
        if (title.length() > 0) {
            //folderName += "_" + title.replaceAll("\\s", "_").replaceAll("[^A-Za-z0-9\\-_]", "");
            title = name + " " + title;
        } else {
            title = name;
        }

        System.out.println(title);

        if (wlk != null) wlk.write(name, map);

        File folder = new File(baseFolder + folderName);
        folder.mkdirs();
        PrintWriter warpOut = Main.getWriter(new File(folder, warpFile));
        PrintWriter checkOut = null;
        PrintWriter mobOut = Main.getWriter(new File(folder, mobFile));

		warpOut.printf("// %s warps\n\n", title);

		File fCheck = new File(folder, checkFile);
		// Só cria o arquivo _checks.txt se ele já existir.
		if( fCheck.exists() ){
			checkOut = Main.getWriter(fCheck);
			checkOut.printf("//\n");
			checkOut.printf("// Pontos de checagem do mapa: %s\n", title);
			checkOut.printf("// Script gerado automaticamente pela ferramenta TMW Converter...\n");
			checkOut.printf("//\n");
			System.out.println("* Arquivo _checks.txt criado.");
		}

		mobOut.printf("//\n");
        mobOut.printf("// Monstros do mapa: %s\n", title);
		mobOut.printf("// Script gerado automaticamente pela ferramenta TMW Converter...\n");
		mobOut.printf("//\n\n");

        TreeSet<Mob> mobs = new TreeSet<Mob>();
        processObjects(map.getObjects(), name, warpOut, checkOut, mobOut, mobs, readMobScript);
        for (MapLayer layer : map) {
            if (layer instanceof ObjectGroup) {
                processObjects(((ObjectGroup) layer).getObjects(), name, warpOut, checkOut, mobOut, mobs, readMobScript);
            }
        }

        warpOut.flush();
        warpOut.close();
		if( checkOut!=null ){
			checkOut.flush();
			checkOut.close();
		}

        HashMap<Integer, Mob> hash = readMobScript.getMobs();
        TreeSet<Object> lCont = new TreeSet<Object>();
		MobCallsub sub;
		MobScript script;
		String str;

		System.out.println("Starting mob points");
		mobOut.printf("\n%s.gat,0,0,0\tscript\tMob%1$s\t-1,{\n\n", name);
		for( Mob mob : mobs ) {
			if( mob.getId()==-1) continue;
			mobOut.printf("On%s:\n\tset @mobID, %d;\n\tcallfunc \"MobPoints\";\n", mob.getIdGrupo(), mob.getId());

			// Stripts genéricos para um determinado id de monstro...
			Mob mob2 = hash.get(mob.getId());
			if(mob2!=null) {
				for( Object obj : mob2.getScripts() ) {
					if( (script=Mob.paraMobScript(obj))!=null ){
						if( script.contemGrupo(mob.getGrupo()) || script.getGrupos().size()==0){
							str = script.getScript().replace("%GRUPO%", mob.getGrupo());
							str = str.replaceAll("%X%", String.valueOf(mob.getX()) );
							str = str.replaceAll("%Y%", String.valueOf(mob.getY()) );
							mobOut.printf("\t%s\n", str);
						}
					} else if( (sub=Mob.paraMobCallsub(obj))!=null ){
						if( sub.getArgs()!=null && !sub.getArgs().equals("") )
							mobOut.printf("\tcallsub %s, %s;\n", sub.getCallsub(), sub.getArgs());
						else
							mobOut.printf("\tcallsub %s;\n", sub.getCallsub());
						//if(lCont.contains(sub)==false) //< Não precisa deste teste. TreeSet não permite inserir objetos iguais.
						lCont.add(sub);
					}
				}
			}

			for( Object obj : mob.getScripts() ) {
				if( (script=Mob.paraMobScript(obj))!=null ){
					// não precisa conferir se script pertence ao grupo
					mobOut.printf("\t%s\n", script.getScript());
				}
			}
			mobOut.printf("\tbreak;\n\n");
		}
		if(lCont.size()>0)
			mobOut.printf("\n//= Callsubs ==========================================================\n\n");
		for( Object obj : lCont ) {
			if( (sub=Mob.paraMobCallsub(obj))!=null ){
				str = readMobScript.getCallsubs().get(sub.getCallsub());
				if(str!=null)
					mobOut.printf("%s\n\n", str);
			}
		}
		mobOut.printf("\tend;\n}\n");
		System.out.println("Finished mob points");

        mobOut.flush();
        mobOut.close();

        //- Gera o arquivo _import.txt
        File _import = new File(folder, importFile);
        List<String> output_elements = new ArrayList<String>();
        processFiles(folder, output_elements);
        PrintWriter importOut = Main.getWriter(_import);
        importOut.printf("map: %s.gat\n", name);
        Collections.sort(output_elements);
        for (String s : output_elements)
        	importOut.println(s);
        importOut.flush();
        importOut.close();

        return folderName;
    }

    public static void writeMasterImport(String[] folders) {
        File master = new File(baseFolder + scriptDirectory + "_import.txt");
        PrintWriter out = Main.getWriter(master);
        if (out == null) return;

        List<String> output_elements = new ArrayList<String>();

        for (String folder : folders) {
            if (folder == null) continue;
            output_elements.add("import: " + folder + "/_import.txt");
        }

        Collections.sort(output_elements);
        for (String s : output_elements)
                out.println(s);

        out.flush();
        out.close();
    }

}
