#!/bin/bash

dir=$1

for file in `find $dir -iname '*.php'`
do
    sed -i "s/\bFraixede\b/Fraixedes/g" $file
    echo $file
done
 

