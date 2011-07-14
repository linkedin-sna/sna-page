#!/bin/bash

if [ $# -lt 1 ]; then
   echo $0 location-of-javadoc
   exit 1
fi

echo Pushing $1...

rsync -r --progress --cvs-exclude --delete --archive $1 snateam@sna-projects.com:/home/empathybox/sna-projects.com/voldemort/javadoc
