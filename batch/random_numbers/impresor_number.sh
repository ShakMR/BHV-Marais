#!/bin/bash

letras=(A B C D E F G H I J K L M N)

init=1
limit=20000
fin=20000

for l in ${letras[@]}; do
	seq -f%06g $init $fin > $l.txt;
	init=`echo $init+$limit | bc`
	fin=`echo $fin+$limit | bc`
done

seq -w 280001 288000 > O.txt

for l in ${letras[@]} O; do
	sed 's/^/'"$l"'/' $l.txt 
done
