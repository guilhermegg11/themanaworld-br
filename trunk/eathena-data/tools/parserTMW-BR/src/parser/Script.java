/**
 * 
 * @data 10/05/2010
 * @author Diogo_RBG - http://diogorbg.blogspot.com/
 * 
 */

package parser;

import java.util.ArrayList;

public class Script {

	ArrayList <Comando> cmds = new ArrayList<Comando>();

	public Script() {
		// constructor
	}

	boolean addCoando(Comando cmd){
		return cmds.add(cmd);
	}

}
