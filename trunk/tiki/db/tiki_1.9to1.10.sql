# $Header: /cvsroot/tikiwiki/tiki/db/tiki_1.9to1.10.sql,v 1.27 2004-12-07 09:26:49 gunnarre Exp $
                                                                                               
# The following script will update a tiki database from verion 1.9 to 1.10
#
# To execute this file do the following:
#
# $ mysql -f dbname < tiki_1.9to1.10.sql
#
# where dbname is the name of your tiki database.
#
# For example, if your tiki database is named tiki (not a bad choice), type:
#
# $ mysql -f tiki < tiki_1.9to1.10.sql
#
# You may execute this command as often as you like,
# and may safely ignore any error messages that appear.

INSERT IGNORE INTO tiki_preferences(name,value) VALUES ('feature_wiki_pageid','n');
INSERT IGNORE INTO tiki_preferences(name,value) VALUES ('feature_wiki_page_footer','n');
INSERT IGNORE INTO tiki_preferences(name,value) VALUES ('wiki_page_footer_content','URL: {url}');
INSERT IGNORE INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_view_wiki_history', 'Can view wiki page history', 'registered', 'wiki');
INSERT IGNORE INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_wiki_view_author', 'Can view wiki page authors', 'basic', 'wiki');
INSERT IGNORE INTO users_grouppermissions (groupName, permName) VALUES ('Anonymous', 'tiki_p_wiki_view_author');
INSERT IGNORE INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_wiki_view_header', 'Can view page wiki page headers, like pagename, description, wiki bar, etc.', 'basic', 'wiki');
INSERT IGNORE INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_view_user_information', 'Can view user info on tiki-user_information.php', 'registered', 'tiki');
INSERT IGNORE INTO users_grouppermissions (groupName, permName) VALUES ('Anonymous', 'tiki_p_wiki_view_header');


#
# Tables of the Opinion-Network
#


CREATE TABLE tiki_opnet_question (
	id INT( 10 ) NOT NULL AUTO_INCREMENT ,
	formtype INT(10) NOT NULL,
	question VARCHAR( 100 ) NOT NULL ,
PRIMARY KEY ( id ) 
);


CREATE TABLE tiki_opnet_formtype (
	id INT( 10 ) NOT NULL AUTO_INCREMENT ,
	name VARCHAR( 30 ) NOT NULL ,
	timestamp INT( 14 ) NOT NULL,
PRIMARY KEY ( id ) 
);


CREATE TABLE tiki_opnet_answer (
	id INT( 10 ) NOT NULL AUTO_INCREMENT ,
	question_id INT( 10 ) NOT NULL ,
	filledform_id INT( 10 ) NOT NULL ,
	value TEXT NOT NULL ,
PRIMARY KEY ( id ) 
);


CREATE TABLE tiki_opnet_filledform (
	id INT( 10 ) NOT NULL AUTO_INCREMENT ,
	who VARCHAR( 40 ) NOT NULL ,
	about_who VARCHAR( 40 ) NOT NULL ,
	formtype INT( 10 ) NOT NULL ,
	timestamp INT( 14 ) NOT NULL,
PRIMARY KEY ( id ) 
);

#
# Opinion-Network tables END
#

# added on 2004-9-2 sylvie
ALTER TABLE tiki_mailin_accounts add column (discard_after varchar(255) default NULL);

# added on 2004-12-7 gunnarre
ALTER TABLE tiki_mailin_accounts add column (attachments char(1) NOT NULL default 'n');

# added on 2004-09-14
# polls enhancement
alter table tiki_polls add column ( 
	description text,
	releaseDate int(14) default NULL,
	KEY pubdate (publishDate),
	KEY reldate (releaseDate),
	KEY active (active)
);

INSERT IGNORE INTO tiki_preferences(name,value) VALUES ('feature_poll_item_comments','n');
INSERT IGNORE INTO tiki_preferences(name,value) VALUES ('feature_poll_submissions','n');
update users_permissions set type='polls' where permName='tiki_p_vote_poll'; 

INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_admin', 'Admin has all polls perms', 'admin', 'polls');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_edit', 'Can modify polls', 'editors', 'polls');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_create', 'Can create new polls', 'editors', 'polls');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_submit', 'Can propose new polls', 'registered', 'polls');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_item_submit', 'Can propose new poll items', 'editors', 'polls');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_view_submissions', 'Can browse polls submissions', 'registered', 'polls');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_view', 'Can view polls', 'basic', 'polls');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_view_comments', 'Can view polls', 'basic', 'polls');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_add_comment', 'Can add coments to polls', 'registered', 'polls');
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES ('tiki_p_poll_add_item_comment', 'Can add comments to poll items', 'registered', 'polls');

# added on 2004-9-24 sylvie
INSERT INTO users_permissions (permName, permDesc, level, type) VALUES('tiki_p_admin_users', 'Can admin users', 'admin', 'user');
UPDATE tiki_menu_options set perm='tiki_p_admin_users' where menuId=42 && name='Users' && perm='tiki_p_admin';
