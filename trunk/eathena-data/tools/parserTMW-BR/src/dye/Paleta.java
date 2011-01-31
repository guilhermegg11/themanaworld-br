package dye;

import java.awt.Color;
import java.util.ArrayList;
import java.util.List;

public class Paleta {

	private List<Color> pal = new ArrayList<Color>();

	public boolean gerarPaleta(String desc) {
		return false;
	}

	/*
	public void gera(String description) {
	    int size = description.length();
	    if (size == 0) return;
	    if (description[0] != '#')
	    {
	        std::cout << "Missing # in the palette description "
	        << "in the third parameter." << std::endl;
	        return;
	    }

	    int pos = 1;
	    for (;;)
	    {
	        if (pos + 6 > size) break;
	        int v = 0;
	        for (int i = 0; i < 6; ++i)
	        {
	            char c = description[pos + i];
	            int n;
	            if ('0' <= c && c <= '9')
	                n = c - '0';
	            else if ('A' <= c && c <= 'F')
	                n = c - 'A' + 10;
	            else if ('a' <= c && c <= 'f')
	                n = c - 'a' + 10;
	            else
	            {
	                std::cout << "invalid Hexadecimal description: "
	                << description << std::endl;
	                return;
	            }

	            v = (v << 4) | n;
	        }
	        Color c = { { v >> 16, v >> 8, v } };
	        mColors.push_back(c);
	        pos += 6;
	        if (pos == size)
	        {
	            mLoaded = true;
	            return;
	        }
	        if (description[pos] != ',')
	            break;

	        ++pos;
	    }

	    mLoaded = true;
	}
	*/

}
