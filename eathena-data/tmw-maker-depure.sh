#!/bin/bash

cd $HOME/tmwserver
./login-server &
./char-server &
./map-server &