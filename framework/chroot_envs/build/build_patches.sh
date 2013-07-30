#!/bin/bash

CHROOT_JAILS=(Debian5 Debian7)
FOLDERS=(apache2 php5)

for JAIL  in ${CHROOT_JAILS[@]}
do
    if [ -e $JAIL.patch ]; then
	rm $JAIL.patch
    fi
	
    for FOLDER in ${FOLDERS[@]}
    do
	diff -ra $JAIL/etc/$FOLDER ~/vulncorpus/framework/chroot_envs/$JAIL/etc/$FOLDER >> $JAIL.patch
    done
    
done