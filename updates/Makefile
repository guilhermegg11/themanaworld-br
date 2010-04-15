# Makefile

# adler32 é um utilitário para gerar hash de pacotes de atualização.
# xmlAdler32 é um utilitário para adicoinar uma tag formatada no final do arquivo... precisa de correção manual.
# http://wiki.themanaworld.org/index.php/How_to_release_an_update

all: adler32 xmlAdler32

adler32: adler32.c
	gcc adler32.c -o adler32 -lz

xmlAdler32: xmlAdler32.c
	gcc xmlAdler32.c -o xmlAdler32 -lz
