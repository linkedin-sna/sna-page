#!/bin/bash

rsync -r --progress --cvs-exclude --archive --no-perms --no-times --exclude .svn --exclude "*.sh" * snateam@sna-projects.com:/home/empathybox/sna-projects.com/