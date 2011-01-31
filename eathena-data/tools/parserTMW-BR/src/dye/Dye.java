package dye;

import java.awt.Canvas;
import java.awt.Graphics;
import java.awt.Image;
import java.awt.image.RenderedImage;
import java.io.File;
import java.io.IOException;

import javax.imageio.ImageIO;

public class Dye {

	public void gravarImagem() {
		try{
			Canvas c = new Canvas();
			Image img = c.createImage(32, 32);
			Graphics g=img.getGraphics();
			c.paint(g);
			String name = "file.png";
			File f=new File(name);
			f.createNewFile();

			ImageIO.write((RenderedImage) img,"png", f);
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

}
