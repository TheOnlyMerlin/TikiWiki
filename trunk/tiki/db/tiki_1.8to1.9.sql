# $Header: /cvsroot/tikiwiki/tiki/db/tiki_1.8to1.9.sql,v 1.3 2004-01-04 09:09:10 mose Exp $

# The following script will update a tiki database from verion 1.7 to 1.8
# 
# To execute this file do the following:
#
# $ mysql -f dbname < tiki_1.8to1.9.sql
#
# where dbname is the name of your tiki database.
#
# For example, if your tiki database is named tiki (not a bad choice), type:
#
# $ mysql -f tiki < tiki_1.8to1.9.sql
# 
# You may execute this command as often as you like, 
# and may safely ignore any error messages that appear.


ALTER TABLE tiki_mailin_accounts ADD anonymous char(1) NOT NULL default 'y';

INSERT INTO tiki_preferences(name,value) VALUES ('feature_mantis','n');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_mantis_admin', 'Can admin Mantis configuration', 'admin', 'mantis');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_mantis_view', 'Can view Mantis bugs', 'registered', 'mantis');

INSERT INTO tiki_menu_options (menuId,type,name,url,position,section,perm,groupname) VALUES (42,'s','Mantis','tiki-mantis-main.php',190,'feature_mantis','tiki_p_mantis_view','');
INSERT INTO tiki_menu_options (menuId,type,name,url,position,section,perm,groupname) VALUES (42,'o','View Bugs','tiki-mantis-view_bugs.php',192,'feature_mantis','tiki_p_mantis_view','');
INSERT INTO tiki_menu_options (menuId,type,name,url,position,section,perm,groupname) VALUES (42,'o','Admin','tiki-mantis-admin.php',198,'feature_mantis','tiki_p_mantis_admin','');

ALTER TABLE tiki_tracker_fields ADD position int(4) default NULL;

ALTER TABLE tiki_tracker_item_attachments CHANGE itemId itemId int(12) NOT NULL default 0;
ALTER TABLE tiki_tracker_item_attachments ADD longdesc blob;
ALTER TABLE tiki_tracker_item_attachments ADD version varchar(40) default NULL;
ALTER TABLE tiki_trackers ADD showComments char(1) default NULL;
ALTER TABLE tiki_trackers ADD orderAttachments varchar(255) NOT NULL default 'filename,created,filesize,downloads,desc';

# added on 2004-01-01 by mose (user and groups dedicated trackers pref)
INSERT IGNORE INTO tiki_preferences(name,value) VALUES ('groupTracker','n');
INSERT IGNORE INTO tiki_preferences(name,value) VALUES ('userTracker','n');

ALTER TABLE `users_groups` ADD `usersTrackerId` INT(11) ;
ALTER TABLE `users_groups` ADD `groupTrackerId` INT(11) ;

# added on 2004-01-03 by mose 
ALTER TABLE `tiki_tracker_fields` ADD `isSearchable` CHAR(1) NOT NULL default 'y';


