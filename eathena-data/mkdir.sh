#!/bin/bash

# Scripts para criar um link simbólico em $HOME/tmwserver
# @autor Diogo_RBG - http://diogorbg.blogspot.com

echo "rm $HOME/tmwserver"
rm $HOME/tmwserver

echo "ln -s $PWD $HOME/tmwserver"
ln -s $PWD $HOME/tmwserver
