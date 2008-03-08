<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-list_file_gallery.php,v 1.50.2.10 2008-03-08 19:37:47 nyloth Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once('tiki-setup.php');

if ( $prefs['feature_file_galleries'] != 'y' ) {
	$smarty->assign('msg', tra('This feature is disabled').': feature_file_galleries');
	$smarty->display('error.tpl');
	die;
}

include_once ('lib/filegals/filegallib.php');
include_once ('lib/stats/statslib.php');
if ( $prefs['feature_categories'] == 'y' ) {
	global $categlib; include_once('lib/categories/categlib.php');
}

$auto_query_args = array('galleryId','fileId','offset','find','sort_mode','edit_mode','page','filegals_manager','maxRecords','show_fgalexplorer','dup_mode');

$gal_info = '';
if ( ! isset($_REQUEST['galleryId']) || $_REQUEST['galleryId'] == 0 ) {
	$tikilib->get_perm_object('', 'file gallery');
	$_REQUEST['galleryId'] = 0;

	if ( ! isset($_REQUEST['edit']) && ! isset($_REQUEST['edit_mode']) && ! isset($_REQUEST['duplicate']) ) {
		// Initialize listing fields with default values (used for the main gallery listing)
		$gal_info = array(
			'name' => 'File Galleries',
			'show_id' => $prefs['fgal_list_id'],
			'show_icon' => $prefs['fgal_list_type'],
			'show_name' => 'f', //$prefs['fgal_list_name'],
			'show_description' => $prefs['fgal_list_description'],
			'show_size' => $prefs['fgal_list_size'],
			'show_created' => $prefs['fgal_list_created'],
			'show_modified' => $prefs['fgal_list_lastmodif'],
			'show_creator' => $prefs['fgal_list_creator'],
			'show_author' => $prefs['fgal_list_author'],
			'show_last_user' => $prefs['fgal_list_last_user'],
			'show_comment' => $prefs['fgal_list_comment'],
			'show_files' => $prefs['fgal_list_files'],
			'show_hits' => $prefs['fgal_list_hits'],
			'show_lockedby' => $prefs['fgal_list_lockedby'],
			'show_checked' => 'y',
			'show_userlink' => 'y'
		);
	}

} elseif ( $gal_info = $tikilib->get_file_gallery($_REQUEST['galleryId']) ) {
	$tikilib->get_perm_object($_REQUEST['galleryId'], 'file gallery', $gal_info);
	$podCastGallery = $filegallib->isPodCastGallery($_REQUEST['galleryId'], $gal_info);

} else {
	$smarty->assign('msg', tra('Non-existent gallery'));
	$smarty->display('error.tpl');
	die;
}

$galleryId = $_REQUEST['galleryId'];

if ( ( $galleryId != 0 || $tiki_p_list_file_galleries != 'y' ) && $tiki_p_view_file_gallery != 'y' ) {
	$smarty->assign('msg', tra('Permission denied you cannot view this section'));
	$smarty->display('error.tpl');
	die;
}

// Init smarty variables to blank values
$smarty->assign('name', '');
$smarty->assign('fname', '');
$smarty->assign('description', '');
$smarty->assign('fdescription', '');
$smarty->assign('max_desc', 1024);
$smarty->assign('maxRows', 10);
$smarty->assign('public', 'n');
$smarty->assign('lockable', 'n');
$smarty->assign('archives', -1);
$smarty->assign('edited', 'n');
$smarty->assign('edit_mode', 'n');
$smarty->assign('dup_mode', 'n');
$smarty->assign('visible', 'y');
$smarty->assign('fgal_type', 'default');
$smarty->assign('parentId', -1);
$smarty->assign('creator', $user);
$smarty->assign('sortorder', 'created');
$smarty->assign('sortdirection', 'desc');

$smarty->assign_by_ref('gal_info', $gal_info);
$smarty->assign_by_ref('galleryId', $_REQUEST['galleryId']);
$smarty->assign_by_ref('name', $gal_info['name']);
$smarty->assign_by_ref('description', $gal_info['description']);

$smarty->assign('reindex_file_id', -1);

// Execute batch actions
if ( $tiki_p_admin_file_galleries == 'y' ) {
	if ( isset($_REQUEST['delsel_x']) ) {
		check_ticket('fgal');
		foreach ( array_values($_REQUEST['file']) as $file ) {
			if ( $_REQUEST['file'] > 0 ) {
				$info = $filegallib->get_file_info($file);
				$smarty->assign('fileId', $file);
				$smarty->assign_by_ref('filename', $info['filename']);
				$smarty->assign_by_ref('fname', $info['name']);
				$smarty->assign_by_ref('fdescription', $info['description']);
			}
			$filegallib->remove_file($info, $user, $gal_info);
		}
		foreach ( array_values($_REQUEST['subgal']) as $subgal ) {
			$filegallib->remove_file_gallery($subgal, $galleryId);
		}
	}

	if ( isset($_REQUEST['movesel']) ) {
		check_ticket('fgal');
		foreach ( array_values($_REQUEST['file']) as $file ) {
			// To move a topic you just have to change the object
			$filegallib->set_file_gallery($file, $_REQUEST['moveto']);
		}
		foreach ( array_values($_REQUEST['subgal']) as $subgal ) {
			$filegallib->move_file_gallery($subgal, $_REQUEST['moveto']);
		}
	}
}

// Lock a file
if ( isset($_REQUEST['lock']) && isset($_REQUEST['fileId']) && $_REQUEST['fileId'] > 0 ) {
	if (!$fileInfo = $filegallib->get_file_info($_REQUEST['fileId'])) {
		$smarty->assign('msg', tra('Incorrect param'));
		$smarty->display("error.tpl");
		die;
	}

	$error_msg = '';
	if ( $_REQUEST['lock'] == 'n' && ! empty($fileInfo['lockedby']) ) {
		if ( $fileInfo['lockedby'] != $user && $tiki_p_admin_file_galleries != 'y' ) {
			$error_msg = tra('You do not have permission to do that');
		} else {
			$filegallib->unlock_file($_REQUEST['fileId']);
		}
	} elseif ( $_REQUEST['lock'] == 'y' ) {
		if ( ! empty($fileInfo['lockedby']) ) {
			$error_msg = sprintf(tra('The file is already locked by %s'), $fileInfo['lockedby']);
		} elseif ( $tiki_p_edit_gallery_file != 'y' ) {
			$error_msg = tra('You do not have permission to do that');
		} else {
			$filegallib->lock_file($_REQUEST['fileId'], $user);
		}
	}
	if ( $error_msg != '' ) {
		$smarty->assign('msg', $error_msg);
		$smarty->display('error.tpl');
		die;
	}
}

// Delete a file
if ( ! empty($_REQUEST['remove']) ) {

	// To remove an image the user must be the owner or the file or the gallery or admin
	if ( ! $info = $filegallib->get_file_info($_REQUEST['remove']) ) {
		$smarty->assign('msg', tra('Incorrect param'));
		$smarty->display('error.tpl');
		die;
	}

	if ( $tiki_p_admin_file_galleries != 'y'  && ( ! $user || $user != $gal_info['user'] ) ) {
		if ( $user != $info['user'] ) {
			$smarty->assign('msg', tra('Permission denied you cannot remove files from this gallery'));
			$smarty->display('error.tpl');
			die;
		}
	}

	$area = 'delfile';
	if ( $prefs['feature_ticketlib2'] != 'y' or ( isset($_POST['daconfirm'] ) and isset($_SESSION["ticket_$area"])) ) {
		key_check($area);

		//Watches
		$smarty->assign('fileId', $_REQUEST['remove']);
		$smarty->assign_by_ref('filename', $info['filename']);
		$smarty->assign_by_ref('fname', $info['name']);
		$smarty->assign_by_ref('fdescription', $info['description']);

		$filegallib->remove_file($info, $user, $gal_info);

	} else {
		key_get($area, tra('Remove file: ').(!empty($info['name'])?$info['name'].' - ':'').$info['filename']);
	}
}

$foo = parse_url($_SERVER['REQUEST_URI']);
$smarty->assign('url', $tikilib->httpPrefix(). $foo['path']);

// Edit mode
if ( isset($_REQUEST['edit_mode']) and $_REQUEST['edit_mode'] ) {
	$smarty->assign('edit_mode', 'y');
	$smarty->assign('edited', 'y');

	if ( $prefs['feature_categories'] == 'y' ) {
		$cat_type = 'file gallery';
		$cat_objid = $galleryId;
		include_once('categorize_list.php');
	}
	if ( $tiki_p_admin_file_galleries == 'y' ) {
		$users = $tikilib->list_users(0, -1, 'login_asc', '', false);
		$smarty->assign_by_ref('users', $users['data']);
	}

	// Edit a file
	if ( $_REQUEST['fileId'] > 0 ) {
		$info = $filegallib->get_file_info($_REQUEST['fileId']);

		$smarty->assign('fileId', $_REQUEST['fileId']);
		$smarty->assign_by_ref('filename', $info['filename']);
		$smarty->assign_by_ref('fname', $info['name']);
		$smarty->assign_by_ref('fdescription', $info['description']);
	}

	// Edit a gallery
	elseif ( $galleryId > 0 ) {
		$smarty->assign_by_ref('maxRows', $gal_info['maxRows']);
		$smarty->assign_by_ref('public', $gal_info['public']);
		$smarty->assign_by_ref('lockable', $gal_info['lockable']);
		$smarty->assign_by_ref('archives', $gal_info['archives']);
		$smarty->assign_by_ref('visible', $gal_info['visible']);
		$smarty->assign_by_ref('parentId', $gal_info['parentId']);
		$smarty->assign_by_ref('creator', $gal_info['user']);
		$smarty->assign('max_desc', $gal_info['max_desc']);
		$smarty->assign('fgal_type', $gal_info['type']);

		if ( isset($gal_info['sort_mode']) && preg_match('/(.*)_(asc|desc)/', $gal_info['sort_mode'], $matches) ) {
			$smarty->assign('sortorder', $matches[1]);
			$smarty->assign('sortdirection', $matches[2]);
		} else {
			$smarty->assign('sortorder', 'created');
			$smarty->assign('sortdirection', 'desc');
		}
	}

// Duplicate mode
} elseif ( ! empty($_REQUEST['dup_mode']) ) {
	$smarty->assign('dup_mode', 'y');
}

// Process the insertion or modification request
if ( isset($_REQUEST['edit']) ) {
	check_ticket('fgal');

	// Saving information

	// Handle files
	if ( isset($_REQUEST['fileId']) ) {
		if ( $tiki_p_admin_file_galleries != 'y' ) {

			// Check file upload rights
			if ( $tiki_p_upload_files != 'y' ) {
				$smarty->assign('msg', tra("Permission denied you can't upload files so you can't edit them"));

				$smarty->display('error.tpl');
				die;
			}

			// Check THIS file edit rights
			if ( $_REQUEST['fileId'] > 0 ) {
				$info = $filegallib->get_file_info($_REQUEST["fileId"]);

				if (!$user || $info['user'] != $user) {
					$smarty->assign('msg', tra('Permission denied you cannot edit this file'));

					$smarty->display('error.tpl');
					die;
				}
			}
		}
	}

	// Handle galleries
	else {
		if ( $tiki_p_admin_file_galleries != 'y' ) {

			// Check gallery creation rights
			if ( $tiki_p_create_file_galleries != 'y' ) {
				$smarty->assign('msg', tra('Permission denied you cannot create galleries and so you cant edit them'));
				$smarty->display('error.tpl');
				die;
			}

			// Check THIS gallery modification rights
			if ( $galleryId > 0 ) {
				if ( ! $user || $gal_info['user'] != $user ) {
					$smarty->assign('msg', tra('Permission denied you cannot edit this gallery'));
					$smarty->display('error.tpl');
					die;
				}
			}
		}
	}

	// Everything is ok so we proceed to edit the file or gallery

	$request_vars = array('name', 'fname', 'description', 'fdescription', 'max_desc', 'fgal_type', 'maxRows', 'rowImages', 'thumbSizeX', 'thumbSizeY', 'parentId', 'creator');
	foreach ( $request_vars as $v ) {
		if ( isset($_REQUEST[$v]) ) {
			$smarty->assign_by_ref($v, $_REQUEST[$v]);
		}
	}

	$request_toggles = array('visible', 'public', 'lockable');
	foreach ( $request_toggles as $t ) {
		$$t = ( isset($_REQUEST[$t]) && $_REQUEST[$t] == 'on' ) ? 'y' : 'n';
		$smarty->assign($t, $$t);
	}

	$_REQUEST['archives'] = isset($_REQUEST['archives']) ? $_REQUEST['archives'] : -1;
	$_REQUEST['user'] = isset($_REQUEST['user']) ? $_REQUEST['user'] : ( isset($gal_info['user']) ? $gal_info['user'] : $user );
	$_REQUEST['sortorder'] = isset($_REQUEST['sortorder']) ? $_REQUEST['sortorder'] : 'created';
	$_REQUEST['sortdirection'] = isset($_REQUEST['sortdirection']) && $_REQUEST['sortdirection'] == 'asc' ? 'asc' : 'desc';

	if ( isset($_REQUEST['fileId']) ) {
		$fid = $filegallib->replace_file(
			$_REQUEST['fileId'],
			$_REQUEST['fname'],
			$_REQUEST['fdescription'],
			$info['filename'],
			$info['data'],
			$info['filesize'],
			$info['filetype'],
			$info['user'],
			$info['path'],
			$info['galleryId']
		);
	} else {
		$fgid = $filegallib->replace_file_gallery(
			$galleryId,
			$_REQUEST['name'],
			$_REQUEST['description'],
			$_REQUEST['user'],
			$_REQUEST['maxRows'],
			$public,
			$visible,
			$_REQUEST['fgal_list_id'],
			$_REQUEST['fgal_list_type'],
			$_REQUEST['fgal_list_name'],
			$_REQUEST['fgal_list_size'],
			$_REQUEST['fgal_list_description'],
			$_REQUEST['fgal_list_created'],
			$_REQUEST['fgal_list_hits'],
			$_REQUEST['max_desc'],
			$_REQUEST['fgal_type'],
			$_REQUEST['parentId'],
			$lockable,
			$_REQUEST['fgal_list_lockedby'],
			$_REQUEST['archives'],
			$_REQUEST['sortorder'].'_'.$_REQUEST['sortdirection'],
			$_REQUEST['fgal_list_lastmodif'],
			$_REQUEST['fgal_list_creator'],
			$_REQUEST['fgal_list_author'],
			$_REQUEST['subgal_conf'],
			$_REQUEST['fgal_list_user'],
			$_REQUEST['fgal_list_comment'],
			$_REQUEST['fgal_list_files']
		);

		if ( $prefs['feature_categories'] == 'y' ) {
			$cat_type = 'file gallery';
			$cat_objid = $fgid;
			$cat_desc = substr($_REQUEST['description'], 0, $_REQUEST['max_desc']);
			$cat_name = $_REQUEST['name'];
			$cat_href = 'tiki-list_file_gallery.php?galleryId='.$cat_objid;
			include_once('categorize.php');
			$categlib->build_cache();
		}

		if ( isset($_REQUEST['viewitem']) ) {
			header('Location: tiki-list_file_gallery.php?galleryId='.$fgid);
			die;
		}
	}

	$smarty->assign('edit_mode', 'n');
}

// Process duplication of a gallery
if ( ! empty($_REQUEST['duplicate']) && ! empty($_REQUEST['name']) && ! empty($_REQUEST['galleryId']) ) {
	check_ticket('fgal');

	$newGalleryId = $filegallib->duplicate_file_gallery(
		$galleryId,
		$_REQUEST['name'],
		isset($_REQUEST['description']) ? $_REQUEST['description'] : ''
	);

	if ( isset($_REQUEST['dupCateg']) && $_REQUEST['dupCateg'] == 'on' && $prefs['feature_categories'] == 'y' ) {
		global $categlib; include_once('lib/categories/categlib.php');
		$cats = $categlib->get_object_categories('file gallery', $galleryId);
		$catObjectId = $categlib->add_categorized_object(
			'file gallery',
			$newGalleryId,
			( isset($_REQUEST['description']) ? $_REQUEST['description'] : '' ),
			$_REQUEST['name'],
			'tiki-list_file_gallery.php?galleryId='.$newGalleryId
		);
		foreach ( $cats as $cat ) {
			$categlib->categorize($catObjectId, $cat);
		}
	}

	if ( isset($_REQUEST['dupPerms']) && $_REQUEST['dupPerms'] == 'on' ) {
		global $userlib; include_once('lib/userslib.php');
		$userlib->copy_object_permissions($galleryId, $newGalleryId, 'file gallery');
	}

	$_REQUEST['galleryId'] = $newGalleryId;
}

// Process removal of a gallery
if ( ! empty($_REQUEST['removegal']) && ! empty($galleryId) ) {
	check_ticket('fgal');

	if ( ! ( $gal_info = $filegallib->get_file_gallery_info($_REQUEST['removegal']) ) ) {
		$smarty->assign('msg', tra('Incorrect param'));
		$smarty->display('error.tpl');
		die;
	}

	if ( $tiki_p_admin_file_galleries != 'y' && ( ! $user || $gal_info['user'] != $user ) ) {
		$smarty->assign('msg', tra('Permission denied you cannot remove this gallery'));
		$smarty->display('error.tpl');
		die;
	}

	$area = 'delfilegal';
	if ( $prefs['feature_ticketlib2'] != 'y' or ( isset($_POST['daconfirm']) and isset($_SESSION["ticket_$area"]) ) ) {
		key_check($area);
		$filegallib->remove_file_gallery($_REQUEST['removegal'], $galleryId);
	} else {
		key_get($area, tra('Remove file gallery: ').' '.$gal_info['name']);
	}

}

// Process upload of a file version
if ( ! empty($_FILES) ) {
	check_ticket('fgal');

	if ( $tiki_p_upload_files != 'y' && $tiki_p_admin_file_galleries != 'y' ) {
		$smarty->assign('msg', tra('Permission denied you can upload files but not to this file gallery'));
		$smarty->display('error.tpl');
		die;
	}

	$savedir = $prefs['fgal_use_dir'];
	foreach ( $_FILES as $k => $v ) {
		$reg = array();
		if ( ! empty($v['tmp_name']) && is_uploaded_file($v['tmp_name']) ) {
			$tmp_dest = $prefs['tmpDir'].'/'.$v['name'].'.tmp';
			$msg = '';
			if ( ! $v['size'] ) {
				$msg = tra('Warning: Empty file:').'  '.htmlentities($v['name']).'. '.tra('Please re-upload your file');
			} elseif (
				empty($v['name'])
				|| ! preg_match('/^upfile(\d+)$/', $k, $regs)
				|| ! ( $fileInfo = $filegallib->get_file_info($regs[1]) )
			) {
				$msg = tra('Could not upload the file').': '.htmlentities($v['name']);
			} elseif (
				( ! empty($prefs['fgal_match_regex']) && ( ! preg_match('/'.$prefs['fgal_match_regex'].'/', $v['name']) ) )
				|| ( ! empty($prefs['fgal_nmatch_regex']) && ( preg_match('/'.$prefs['fgal_nmatch_regex'].'/', $v['name']) ) )
			) {
				$msg = tra('Invalid filename (using filters for filenames)').': '.htmlentities($v['name']);
			} elseif ( $_REQUEST['galleryId'] != $fileInfo['galleryId'] ) {
				$msg = tra('Could not find the file requested');
			} elseif ( ! empty($fileInfo['lockedby']) && $fileInfo['lockedby'] != $user && $tiki_p_admin_file_galleries != 'y' ) {
				// if locked, user must be the locker
				$msg = tra(sprintf('The file is locked by %s', $fileInfo['lockedby']));
			} elseif ( ! (
				$tiki_p_edit_gallery_file == 'y'
				|| ( ! empty($user) && ( $user == $fileInfo['user'] || $user == $fileInfo['lockedby']) )
			) ) {
				// must be the owner or the locker or have the perms
				$msg = tra('Permission denied you can edit this file');
			} elseif ( ! move_uploaded_file($v['tmp_name'], $tmp_dest) ) {
				$msg = tra('Errors detected');
			} elseif ( ! ($fp = fopen($tmp_dest, 'rb')) ) {
				$msg = tra('Cannot read file:').' '.$tmp_dest;
                        }

			if ( $msg != '' ) {
				$smarty->assign('msg', $msg);
				$smarty->display('error.tpl');
				die;
			}

			$data = '';
			$fhash = '';

			if ( $prefs['fgal_use_db'] == 'n' ) {
				$fhash = md5(uniqid(md5($v['name'])));
				@$fw = fopen($savedir.$fhash, 'wb');
				if ( ! $fw ) {
					$smarty->assign('msg', tra('Cannot write to this file:').$savedir.$fhash);
					$smarty->display('error.tpl');
					die;
				}
			}

			while ( ! feof($fp) ) {
				if ( $prefs['fgal_use_db'] == 'y' ) {
					$data .= fread($fp, 8192 * 16);
				} else {
					if (($data = fread($fp, 8192 * 16)) === false) {
						$smarty->assign('msg', tra('Cannot read the file:').$tmp_dest);
						$smarty->display('error.tpl');
						die;
					}
					fwrite($fw, $data);
				}
			}

			fclose($fp);
			// remove file after copying it to the right location or database
			@unlink($tmp_dest);
			
			if ( $prefs['fgal_use_db'] == 'n' ) {
				fclose($fw);
				$data = '';
			}

			if ( preg_match('/.flv$/', $v['name']) ) $type = 'video/x-flv';

			if ( $prefs['fgal_use_db'] == 'y' && ( ! isset($data) || strlen($data) < 1) ) {
				$smarty->assign('msg', tra('Warning: Empty file:').' '.$v['name'].'. '.tra('Please re-upload your file'));
				$smarty->display('error.tpl');
				die;
			}

			$fileInfo['filename'] = $v['name'];

			$fileId = $filegallib->replace_file(
				$fileInfo['fileId'],
				$fileInto['name'],
				$fileInfo['description'],
				$v['name'],
				$data,
				$v['size'],
				$v['type'],
				$user,
				$fhash,
				$fileInfo['comment'],
				$gal_info,
				true, // replace file
				$fileInfo['author'],
				$fileInfo['lastModif'],
				$fileInfo['lockedby']
			);

			if ( ! $fileId ) {
				if ( $prefs['fgal_use_db'] == 'n' ) {
					@unlink($savedir.$fhash);
				}
				$smarty->assign('msg', tra('Upload was not successful. Duplicate file content').': '.$v['name']);
				$smarty->display('error.tpl');
				die;
			}

			$smarty->assign('fileId', $_REQUEST['fileId']);
			$smarty->assign('fileChangedMessage', tra('File update was successful').': '.$v['name']);

			if ( isset($_REQUEST['fast']) && $prefs['fgal_asynchronous_indexing'] == 'y' ) {
				$smarty->assign('reindex_file_id', $fileId);
			}

		} elseif ( $v['error'] != 0 ) {
			$smarty->assign('msg', tra('Upload was not successful').': '.$tikilib->uploaded_file_error($v['error']));
			$smarty->display('error.tpl');
			die;
		}
	}
}

// Set display config
if ( ! isset($_REQUEST['maxRecords']) || $_REQUEST['maxRecords'] <= 0 ) {
	if ( isset($gal_info['maxRows']) && $gal_info['maxRows'] > 0 ) {
		$_REQUEST['maxRecords'] = $gal_info['maxRows'];
	} else {
		$_REQUEST['maxRecords'] = $prefs['maxRecords'];
	}
}
$smarty->assign_by_ref('maxRecords', $_REQUEST['maxRecords']);

if ( ! isset($_REQUEST['offset']) ) $_REQUEST['offset'] = 0;
$smarty->assign_by_ref('offset', $_REQUEST['offset']);

if ( ! isset($_REQUEST['sort_mode']) ) {
	$_REQUEST['sort_mode'] = ( $gal_info['show_name'] == 'f' ? 'filename_asc' : 'name_asc' );
}
$smarty->assign_by_ref('sort_mode', $_REQUEST['sort_mode']);

if ( ! isset($_REQUEST['find']) ) $_REQUEST['find'] = '';
$smarty->assign_by_ref('find', $_REQUEST['find']);

// Get list of files in the gallery
$files = $tikilib->get_files($_REQUEST['offset'], $_REQUEST['maxRecords'], $_REQUEST['sort_mode'], $_REQUEST['find'], $_REQUEST['galleryId'], true, true);
$smarty->assign_by_ref('files', $files['data']);
$smarty->assign('cant', $files['cant']);

// Set comments config
if ( $prefs['feature_file_galleries_comments'] == 'y' ) {
	$comments_per_page = $prefs['file_galleries_comments_per_page'];

	$thread_sort_mode = $prefs['file_galleries_comments_default_ordering'];
	$comments_vars = array('galleryId', 'offset', 'sort_mode', 'find');

	$comments_prefix_var = 'file gallery:';
	$comments_object_var = 'galleryId';
	include_once ('comments.php');
}

/*

if ( $tiki_p_admin_file_galleries == 'y' || $tiki_p_assign_perm_file_gallery == 'y' ) {
	if ( ! isset($perms) ) {
		$perms = $userlib->get_permissions(0, -1, 'permName_desc', '', 'file galleries');
	}
	$smarty->assign_by_ref('perms', $perms['data']);
	$groups = $userlib->get_groups(0, -1, 'groupName_asc', '', '', 'n');
	$smarty->assign_by_ref('groups', $groups['data']);
}

$options_sortorder = array(
	tra('Creation Date') => 'created',
	tra('Name') => 'name',
	tra('Filename') => 'filename',
	tra('Size') => 'filesize',
	tra('Owner') => 'user',
	tra('Hits') => 'hits',
	tra('ID') => 'fileId'
);
$smarty->assign_by_ref('options_sortorder', $options_sortorder);

*/

// Set section config
$section = 'file_galleries';
include_once('tiki-section_options.php');

// Theme control
if ( $prefs['feature_theme_control'] == 'y' ) {
	$cat_type = 'file gallery';
	$cat_objid = $_REQUEST['galleryId'];
	include('tiki-tc.php');
}

// Watches
if ( $prefs['feature_user_watches'] == 'y' ) {

	if ( $user && isset($_REQUEST['watch_event']) ) {
		check_ticket('index');
		if ( $_REQUEST['watch_action'] == 'add' ) {
			$tikilib->add_user_watch(
				$user,
				$_REQUEST['watch_event'],
				$_REQUEST['watch_object'],
				'File Gallery',
				( isset($_REQUEST['galleryName']) ? $_REQUEST['galleryName'] : '' ),
				"tiki-list_file_gallery.php?galleryId=$galleryId"
			);
		} else {
			$tikilib->remove_user_watch($user, $_REQUEST['watch_event'], $_REQUEST['watch_object']);
		}   
	}

	$smarty->assign('user_watching_file_gallery', 'n');
	if ( $user && $tikilib->user_watches($user, 'file_gallery_changed', $galleryId, 'File Gallery') ) {
		$smarty->assign('user_watching_file_gallery', 'y');
	}

	// Check, if the user is watching this file gallery by a category.    
	if ( $prefs['feature_categories'] == 'y' ) {
		$watching_categories_temp = $categlib->get_watching_categories($galleryId, 'file gallery', $user);	    
		$smarty->assign('category_watched', 'n');
		if ( count($watching_categories_temp) > 0 ) {
			$smarty->assign('category_watched', 'y');
			$watching_categories = array();
			foreach ( $watching_categories_temp as $wct ) {
				$watching_categories[] = array('categId' => $wct, 'name' => $categlib->get_category_name($wct));
			}
			$smarty->assign('watching_categories', $watching_categories);
		}
	}
}

$all_galleries = $filegallib->list_file_galleries(0, -1, 'name_asc', $user);
$smarty->assign_by_ref('all_galleries', $all_galleries['data']);

// Build galleries browsing tree and current gallery path array
//
function add2tree(&$tree, &$galleries, &$gallery_id, &$gallery_path, &$expanded, $cur_id = -1) {
	static $total = 1;
	$i = 0;
	$current_path = array();
	$path_found = false;
	foreach ( $galleries as $gk => $gv ) {
		if ( $gv['parentId'] == $cur_id && $gv['id'] != $cur_id ) {
			$tree[$i] = &$galleries[$gk];
			$tree[$i]['link_var'] = 'galleryId';
			$tree[$i]['link_id'] = $gv['id'];
			$tree[$i]['pos'] = $total++;
			add2tree($tree[$i]['data'], $galleries, $gallery_id, $gallery_path, $expanded, $gv['id']);
			if ( ! $path_found && $gv['id'] == $gallery_id ) {
				if ( $_REQUEST['galleryId'] == $gv['id'] ) $tree[$i]['current'] = 1;
				array_unshift($gallery_path, array($gallery_id, $gv['name']));
				$expanded[] = $tree[$i]['pos'] + 1;
				$gallery_id = $cur_id;
				$path_found = true;
			}
			$i++;
		}
	}
}

if ( is_array($all_galleries) && count($all_galleries) > 0 ) {
	$tree = array('name' => tra('File Galleries'), 'data' => array());
	$gallery_path = array();
	$expanded = array('1');

	add2tree($tree['data'], $all_galleries['data'], $galleryId, $gallery_path, $expanded);

	array_unshift($gallery_path, array(0, $tree['name']));
	$gallery_path_str = '';
	foreach ( $gallery_path as $dir_id ) {
		if ( $gallery_path_str != '' ) $gallery_path_str .= ' &nbsp;&gt;&nbsp;';
		$gallery_path_str .= '<a href="tiki-list_file_gallery.php?galleryId='.$dir_id[0].( isset($_REQUEST['filegals_manager']) ? '&amp;filegals_manager' : '').'">'.$dir_id[1].'</a>';
	}
}

$smarty->assign('gallery_path', $gallery_path_str);
$smarty->assign_by_ref('tree', $tree);
$smarty->assign_by_ref('expanded', $expanded);

ask_ticket('fgal');

if ( $_REQUEST['galleryId'] != 0 ) {

	$smarty->assign('download_path', ( $podCastGallery ? $prefs['fgal_podcast_dir'] : $prefs['fgal_use_dir'] ) );

	// Add a file hit
	$statslib->stats_hit($gal_info['name'], 'file gallery', $galleryId);
	if ( $prefs['feature_actionlog'] == 'y' ) {
		include_once('lib/logs/logslib.php');
		$logslib->add_action('Viewed', $galleryId, 'file gallery');
	}

} else {
	// Add a gallery hit
	$tikilib->add_file_gallery_hit($_REQUEST['galleryId']);
}

// Get listing display config
include_once('fgal_listing_conf.php');

$smarty->assign('mid', 'tiki-list_file_gallery.tpl');

// Display the template
if ( isset($_REQUEST['filegals_manager']) ) {
	$smarty->assign('filegals_manager','y');
	$smarty->display('tiki_full.tpl');
} else {
	$smarty->display('tiki.tpl');
}
?>
