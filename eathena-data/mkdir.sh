#!/bin/bash

echo "rm $HOME/tmwserver"
rm $HOME/tmwserver

echo "ln -s $PWD $HOME/tmwserver"
ln -s $PWD $HOME/tmwserver
