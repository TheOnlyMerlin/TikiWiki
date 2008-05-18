# $Header: /cvsroot/tikiwiki/tiki/setup.sh,v 1.42.2.4 2008/03/04 16:43:35 chriscramer Exp $

# Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
# All Rights Reserved. See copyright.txt for details and a complete list of authors.
# Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

# List of directories affected by this script:
DIRS="backups db dump files img/wiki img/wiki_up img/trackers installer modules/cache temp temp/cache templates_c templates styles maps whelp mods"

if [ -d 'lib/Galaxia' ]; then
	DIRS=$DIRS" lib/Galaxia/processes"
fi

if [ -d 'lib/equation' ]; then
	DIRS=$DIRS" lib/equation/tmp"
	DIRS=$DIRS" lib/equation/pictures"
fi


AUSER=nobody
AGROUP=nobody
RIGHTS=02777
VIRTUALS=""

UNAME=`uname | cut -c 1-6`

if [ -f /etc/debian_version ]; then
	AUSER=www-data
	AGROUP=www-data
fi

if [ -f /etc/redhat-release ]; then
	AUSER=apache
	AGROUP=apache
fi

if [ -f /etc/gentoo-release ]; then
	AUSER=apache
	AGROUP=apache
fi

if [ -f /etc/SuSE-release ]; then
        AUSER=wwwrun
        AGROUP=www
fi

if [ "$UNAME" = "CYGWIN" ]; then
	AUSER=SYSTEM
	AGROUP=SYSTEM
fi

AUSER=$USER

# Create directories as needed
for dir in $DIRS; do
	if [ ! -d $dir ]; then
		echo Creating directory "$dir"
		mkdir -p $dir
	fi
	for vdir in $VIRTUALS; do
		if [ ! -d "$dir/$vdir" ]; then
			echo Creating directory "$dir/$vdir"
			mkdir -p "$dir/$vdir"
		fi
		echo $vdir >> db/virtuals.inc
		cat db/virtuals.inc | sort | uniq > db/virtuals.inc_new
		rm -f db/virtuals.inc && mv db/virtuals.inc_new db/virtuals.inc
	done
done

# Create htaccees files as needed
if [ ! -e "templates_c/_htaccess" ]; then
	if [ ! -e "templates_c/.htaccess" ]; then
		echo '<FilesMatch "*">
	order deny,allow
	deny from all
</FilesMatch>' > templates_c/_htaccess
	fi
fi

# Create index.php files as needed
if [ ! -e "templates_c/index.php" ]; then
	echo '<?php

// This file was generated by setup.sh script

// Copyright (c) 2002-2006, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// This redirects to the site root to prevent directory browsing

header ("location: ../tiki-index.php");
die;

?>' > templates_c/index.php
fi

# Set ownerships of the directories
chown -R $AUSER *

if [ -n "$AGROUP" ]; then
	chgrp -R $AGROUP $DIRS
	chgrp $AGROUP robots.txt
fi

chmod -R $RIGHTS $DIRS
chmod $RIGHTS robots.txt
chmod $RIGHTS tiki-install.php

chown $AUSER robots.txt

# by setting the rights to tiki-install.php file, tiki-installer can be used in most cases to disable execution of the script
chown $AUSER tiki-install.php

exit 0

