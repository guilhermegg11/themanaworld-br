/**
 * 
 * @date 10/05/2010
 * @author Diogo_RBG - http://diogorbg.blogspot.com/
 * 
 */

package parserTXT;

import java.util.ArrayList;
import java.util.Iterator;

public class Script {

	ArrayList<Comando> cmds = new ArrayList<Comando>();
	Iterator<Comando> it = null;

	public Script() {
		// constructor
	}

	public int cont(){
		return cmds.size();
	}

	public boolean addComando(Comando cmd){
		return cmds.add(cmd);
	}

	/**
	 * Apenas retorna o iterator interno do Script.
	 */
	public Iterator<Comando> getIterator() {
		return it;
	}

	/**
	 * Apenas seta o iterator interno do Script.
	 */
	public void setIterator(Iterator<Comando> it) {
		this.it = it;
	}

	/**
	 * Prepara o iterator interno do Script para que ele liste toda a lista de Comandos.
	 */
	public void initIterator(){
		it = cmds.iterator();
	}

	/**
	 * Retorna a existência ou não de um próximo comando. Além de não mover o iterator.
	 * @return Retorna null caso o iterator interno não tenha sido inicializado.
	 */
	public boolean seProx(){
		if(it==null)
			return false;
		return it.hasNext();
	}

	/**
	 * Retorna o próximo comando ao mesmo tempo que move o iterator interno para o próxmo comando.
	 * @return Retorna null caso o iterator interno não tenha sido inicializado.
	 */
	public Comando getProx(){
		if(it==null)
			return null;
		return it.next();
	}

}
