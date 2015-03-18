<?php 
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

//This the default icon set, it associates icon names to icon fonts. It is used as fallback for all other icon sets.

// This script may only be included - so its better to die if called directly.
if (strpos($_SERVER['SCRIPT_NAME'], basename(__FILE__)) !== false) {
	header('location: index.php');
	exit;
}

function iconset_default()
{
	return array(
		'name' => tr('Default (Font-awesome)'), // Mandatory, will be displayed as Icon set option in the Look&Feel admin UI
		'description' => tr('The default system icon set using Font-awesome fonts'), // TODO display as Icon set description in the Look&Feel admin UI
		'tag' => 'span', // The default html tag for the icons in the icon set.
		'prepend' => 'fa fa-',
		'append' => ' fa-fw',
		'icons' => array(
			/* This is the definition of an icon in the icon set if it's an "alias" to one of the default icons.
			 * The key must be unique, it is the "name" parameter at the icon function,
			 * so eg: {icon name="actions"}
			 * will find 'save' in the array and apply the specified configuration */

			'actions' => array( 
				'id' => 'play-circle',    // id to match the defaults defined below
			),
			'add' => array(
				'id' => 'plus-circle',
			),
			'admin_ads' => array(
				'id' => 'film',
			),
			'admin_articles' => array(
				'id' => 'font',
			),
			'admin_blogs' => array(
				'id' => 'bold',
			),
			'admin_calendar' => array(
				'id' => 'calendar',
			),
			'admin_category' => array(
				'id' => 'sitemap',
			),
			'admin_comments' => array(
				'id' => 'comment',
			),
			'admin_community' => array(
				'id' => 'group',
			),
			'admin_connect' => array(
				'id' => 'link',
			),
			'admin_copyright' => array(
				'id' => 'copyright',
			),
			'admin_directory' => array(
				'id' => 'folder-o',
			),
			'admin_faqs' => array(
				'id' => 'question',
			),
			'admin_features' => array(
				'id' => 'power-off',
			),
			'admin_fgal' => array(
				'id' => 'folder-open',
			),
			'admin_forums' => array(
				'id' => 'comments',
			),
			'admin_freetags' => array(
				'id' => 'tags',
			),
			'admin_gal' => array(
				'id' => 'file-image-o',
			),
			'admin_general' => array(
				'id' => 'cog',
			),
			'admin_i18n' => array(
				'id' => 'globe',
			),
			'admin_intertiki' => array(
				'id' => 'exchange',
			),
			'admin_login' => array(
				'id' => 'sign-in',
			),
			'admin_look' => array(
				'id' => 'image',
			),
			'admin_maps' => array(
				'id' => 'map-marker',
			),
			'admin_messages' => array(
				'id' => 'envelope-o',
			),
			'admin_metatags' => array(
				'id' => 'tag',
			),
			'admin_module' => array(
				'id' => 'cogs',
			),
			'admin_payment' => array(
				'id' => 'credit-card',
			),
			'admin_performance' => array(
				'id' => 'tachometer',
			),
			'admin_polls' => array(
				'id' => 'tasks',
			),
			'admin_profiles' => array(
				'id' => 'cubes',
			),
			'admin_rating' => array(
				'id' => 'check-square',
			),
			'admin_rss' => array(
				'id' => 'rss',
			),
			'admin_score' => array(
				'id' => 'trophy',
			),
			'admin_search' => array(
				'id' => 'search',
			),
			'admin_semantic' => array(
				'id' => 'arrows-h',
			),
			'admin_security' => array(
				'id' => 'lock',
			),
			'admin_sefurl' => array(
				'id' => 'search-plus',
			),
			'admin_share' => array(
				'id' => 'share-alt',
			),
			'admin_socialnetworks' => array(
				'id' => 'thumbs-up',
			),
			'admin_textarea' => array(
				'id' => 'edit',
			),
			'admin_trackers' => array(
				'id' => 'database',
			),
			'admin_userfiles' => array(
				'id' => 'cog',
			),
			'admin_video' => array(
				'id' => 'video-camera',
			),
			'admin_webmail' => array(
				'id' => 'inbox',
			),
			'admin_webservices' => array(
				'id' => 'cog',
			),
			'admin_wiki' => array(
				'id' => 'file-text-o',
			),
			'admin_workspace' => array(
				'id' => 'desktop',
			),
			'admin_wysiwyg' => array(
				'id' => 'align-center',
			),
			'administer' => array(
				'id' => 'cog',
			),
			'attach' => array(
				'id' => 'paperclip',
			),
			'audio' => array(
				'id' => 'file-audio-o',
			),
			'back' => array(
				'id' => 'arrow-left',
			),
			'backlink' => array(
				'id' => 'reply',
			),
			'backward_step' => array(
				'id' => 'step-backward',
			),
			'cache' => array(
				'id' => 'trash-o',
			),
			'change' => array(
				'id' => 'edit',
			),
			'chart' => array(
				'id' => 'area-chart',
			),
			'check' => array(
				'id' => 'check-square-o',
			),
			'clone' => array(
				'id' => 'clipboard',
			),
			'code_file' => array(
				'id' => 'file',
			),
			'comments' => array(
				'id' => 'comments-o',
			),
			'create' => array(
				'id' => 'plus',
			),
			'delete' => array(
				'id' => 'times',
			),
			'disable' => array(
				'id' => 'minus-square',
			),
			'documentation' => array(
				'id' => 'book',
			),
			'down' => array(
				'id' => 'sort-desc',
			),
			'ellipsis' => array(
				'id' => 'ellipsis-h',
			),
			'enable' => array(
				'id' => 'check-square',
			),
			'envelope' => array(
				'id' => 'envelope-o',
			),
			'error' => array(
				'id' => 'exclamation-circle',
			),
			'events' => array(
				'id' => 'clock-o',
			),
			'export' => array(
				'id' => 'upload',
			),
			'excel' => array(
				'id' => 'file-excel-o',
			),
			'file' => array(
				'id' => 'file-o',
			),
			'forward_step' => array(
				'id' => 'step-forward',
			),
			'file-archive' => array(
				'id' => 'folder',
			),
			'file-archive-open' => array(
				'id' => 'folder-open',
			),
			'floppy' => array(
				'id' => 'floppy-o',
			),
			'help' => array(
				'id' => 'question-circle',
			),
			'image' => array(
				'id' => 'file-image-o',
			),
			'import' => array(
				'id' => 'download',
			),
			'index' => array(
				'id' => 'spinner',
			),
			'information' => array(
				'id' => 'info-circle',
			),
			'link-external' => array(
				'id' => 'external-link',
			),
			//same fa icon used for admin_security, but not the same in other icon sets
			'log' => array(
				'id' => 'history',
			),
			'menu' => array(
				'id' => 'bars',
			),
			'menuitem' => array(
				'id' => 'angle-right',
			),
			'merge' => array(
				'id' => 'random',
			),
			'module' => array(
				'id' => 'cogs',
			),
			'move' => array(
				'id' => 'exchange',
			),
			'next' => array(
				'id' => 'arrow-right',
			),
			'notepad' => array(
				'id' => 'file-text-o',
			),
			'notification' => array(
				'id' => 'bell-o',
			),
			'off' => array(
				'id' => 'power-off',
			),
			'ok' => array(
				'id' => 'check-circle',
			),
			'pdf' => array(
				'id' => 'file-pdf-o',
			),
			'permission' => array(
				'id' => 'key',
			),
			'plugin' => array(
				'id' => 'puzzle-piece',
			),
			'popup' => array(
				'id' => 'list-alt',
			),
			'post' => array(
				'id' => 'pencil',
			),
			'powerpoint' => array(
				'id' => 'file-powerpoint-o',
			),
			'previous' => array(
				'id' => 'arrow-left',
			),
			'ranking' => array(
				'id' => 'sort-numeric-asc',
			),
			'redo' => array(
				'id' => 'repeat',
			),
			'remove' => array(
				'id' => 'times',
			),
			'screencapture' => array(
				'id' => 'camera',
			),
			'settings' => array(
				'id' => 'wrench',
			),
			'sharethis' => array(
				'id' => 'share-alt',
			),
			'sort-down' => array(
				'id' => 'sort-desc',
			),
			'sort-up' => array(
				'id' => 'sort-asc',
			),
			'stop-watching' => array(
				'id' => 'eye-slash',
			),
			'structure' => array(
				'id' => 'sitemap',
			),
			'success' => array(
				'id' => 'check',
			),
			'textfile' => array(
				'id' => 'file-text-o',
			),
			'theme' => array(
				'id' => 'image',
			),
			'themegenerator' => array(
				'id' => 'paint-brush',
			),
			'three-d' => array(
				'id' => 'cube',
			),
			'toggle-down' => array(
				'id' => 'toggle-down',
			),
			'toggle-on' => array(
				'id' => 'toggle-on',
			),
			'toggle-off' => array(
				'id' => 'toggle-off',
			),
			'toggle-right' => array(
				'id' => 'toggle-right',
			),
			'trackers' => array(
				'id' => 'database',
			),
			'trackerfields' => array(
				'id' => 'th-list',
			),
			'translate' => array(
				'id' => 'globe',
			),
			'trash' => array(
				'id' => 'trash-o',
			),
			'up' => array(
				'id' => 'sort-asc',
			),
			'video' => array(
				'id' => 'file-video-o',
			),
			'view' => array(
				'id' => 'search-plus',
			),
			'warning' => array(
				'id' => 'exclamation-triangle',
			),
			'watch' => array(
				'id' => 'eye',
			),
			'watch-group' => array(
				'id' => 'group',
			),
			'wizard' => array(
				'id' => 'magic',
			),
			'word' => array(
				'id' => 'file-word-o',
			),
			'zip' => array(
				'id' => 'file-zip-o',
			),
		),
		/*
		 * All the available icons in this set (font-awsome in this case, from http://fortawesome.github.io/Font-Awesome/cheatsheet/)
		 */
		'defaults' => array(
			'adjust',
			'adn',
			'align-center',
			'align-justify',
			'align-left',
			'align-right',
			'ambulance',
			'anchor',
			'android',
			'angellist',
			'angle-double-down',
			'angle-double-left',
			'angle-double-right',
			'angle-double-up',
			'angle-down',
			'angle-left',
			'angle-right',
			'angle-up',
			'apple',
			'archive',
			'area-chart',
			'arrow-circle-down',
			'arrow-circle-left',
			'arrow-circle-o-down',
			'arrow-circle-o-left',
			'arrow-circle-o-right',
			'arrow-circle-o-up',
			'arrow-circle-right',
			'arrow-circle-up',
			'arrow-down',
			'arrow-left',
			'arrow-right',
			'arrow-up',
			'arrows',
			'arrows-alt',
			'arrows-h',
			'arrows-v',
			'asterisk',
			'at',
			'automobile',
			'backward',
			'ban',
			'bank',
			'bar-chart',
			'bar-chart-o',
			'barcode',
			'bars',
			'beer',
			'behance',
			'behance-square',
			'bell',
			'bell-o',
			'bell-slash',
			'bell-slash-o',
			'bicycle',
			'binoculars',
			'birthday-cake',
			'bitbucket',
			'bitbucket-square',
			'bitcoin',
			'bold',
			'bolt',
			'bomb',
			'book',
			'bookmark',
			'bookmark-o',
			'briefcase',
			'btc',
			'bug',
			'building',
			'building-o',
			'bullhorn',
			'bullseye',
			'bus',
			'cab',
			'calculator',
			'calendar',
			'calendar-o',
			'camera',
			'camera-retro',
			'car',
			'caret-down',
			'caret-left',
			'caret-right',
			'caret-square-o-down',
			'caret-square-o-left',
			'caret-square-o-right',
			'caret-square-o-up',
			'caret-up',
			'cc',
			'cc-amex',
			'cc-discover',
			'cc-mastercard',
			'cc-paypal',
			'cc-stripe',
			'cc-visa',
			'certificate',
			'chain',
			'chain-broken',
			'check',
			'check-circle',
			'check-circle-o',
			'check-square',
			'check-square-o',
			'chevron-circle-down',
			'chevron-circle-left',
			'chevron-circle-right',
			'chevron-circle-up',
			'chevron-down',
			'chevron-left',
			'chevron-right',
			'chevron-up',
			'child',
			'circle',
			'circle-o',
			'circle-o-notch',
			'circle-thin',
			'clipboard',
			'clock-o',
			'close',
			'cloud',
			'cloud-download',
			'cloud-upload',
			'cny',
			'code',
			'code-fork',
			'codepen',
			'coffee',
			'cog',
			'cogs',
			'columns',
			'comment',
			'comment-o',
			'comments',
			'comments-o',
			'compass',
			'compress',
			'copy',
			'copyright',
			'credit-card',
			'crop',
			'crosshairs',
			'css3',
			'cube',
			'cubes',
			'cut',
			'cutlery',
			'dashboard',
			'database',
			'dedent',
			'delicious',
			'desktop',
			'deviantart',
			'digg',
			'dollar',
			'dot-circle-o',
			'download',
			'dribbble',
			'dropbox',
			'drupal',
			'edit',
			'eject',
			'ellipsis-h',
			'ellipsis-v',
			'empire',
			'envelope',
			'envelope-o',
			'envelope-square',
			'eraser',
			'eur',
			'euro',
			'exchange',
			'exclamation',
			'exclamation-circle',
			'exclamation-triangle',
			'expand',
			'external-link',
			'external-link-square',
			'eye',
			'eye-slash',
			'eyedropper',
			'facebook',
			'facebook-square',
			'fast-backward',
			'fast-forward',
			'fax',
			'female',
			'fighter-jet',
			'file',
			'file-archive-o',
			'file-audio-o',
			'file-code-o',
			'file-excel-o',
			'file-image-o',
			'file-movie-o',
			'file-o',
			'file-pdf-o',
			'file-photo-o',
			'file-picture-o',
			'file-powerpoint-o',
			'file-sound-o',
			'file-text',
			'file-text-o',
			'file-video-o',
			'file-word-o',
			'file-zip-o',
			'files-o',
			'film',
			'filter',
			'fire',
			'fire-extinguisher',
			'flag',
			'flag-checkered',
			'flag-o',
			'flash',
			'flask',
			'flickr',
			'floppy-o',
			'folder',
			'folder-o',
			'folder-open',
			'folder-open-o',
			'font',
			'forward',
			'foursquare',
			'frown-o',
			'futbol-o',
			'gamepad',
			'gavel',
			'gbp',
			'ge',
			'gear',
			'gears',
			'gift',
			'git',
			'git-square',
			'github',
			'github-alt',
			'github-square',
			'gittip',
			'glass',
			'globe',
			'google',
			'google-plus',
			'google-plus-square',
			'google-wallet',
			'graduation-cap',
			'group',
			'h-square',
			'hacker-news',
			'hand-o-down',
			'hand-o-left',
			'hand-o-right',
			'hand-o-up',
			'hdd-o',
			'header',
			'headphones',
			'heart',
			'heart-o',
			'history',
			'home',
			'hospital-o',
			'html5',
			'ils',
			'image',
			'inbox',
			'indent',
			'info',
			'info-circle',
			'inr',
			'instagram',
			'institution',
			'ioxhost',
			'italic',
			'joomla',
			'jpy',
			'jsfiddle',
			'key',
			'keyboard-o',
			'krw',
			'language',
			'laptop',
			'lastfm',
			'lastfm-square',
			'leaf',
			'legal',
			'lemon-o',
			'level-down',
			'level-up',
			'life-bouy',
			'life-buoy',
			'life-ring',
			'life-saver',
			'lightbulb-o',
			'line-chart',
			'link',
			'linkedin',
			'linkedin-square',
			'linux',
			'list',
			'list-alt',
			'list-ol',
			'list-ul',
			'location-arrow',
			'lock',
			'long-arrow-down',
			'long-arrow-left',
			'long-arrow-right',
			'long-arrow-up',
			'magic',
			'magnet',
			'mail-forward',
			'mail-reply',
			'mail-reply-all',
			'male',
			'map-marker',
			'maxcdn',
			'meanpath',
			'medkit',
			'meh-o',
			'microphone',
			'microphone-slash',
			'minus',
			'minus-circle',
			'minus-square',
			'minus-square-o',
			'mobile',
			'mobile-phone',
			'money',
			'moon-o',
			'mortar-board',
			'music',
			'navicon',
			'newspaper-o',
			'openid',
			'outdent',
			'pagelines',
			'paint-brush',
			'paper-plane',
			'paper-plane-o',
			'paperclip',
			'paragraph',
			'paste',
			'pause',
			'paw',
			'paypal',
			'pencil',
			'pencil-square',
			'pencil-square-o',
			'phone',
			'phone-square',
			'photo',
			'picture-o',
			'pie-chart',
			'pied-piper',
			'pied-piper-alt',
			'pinterest',
			'pinterest-square',
			'plane',
			'play',
			'play-circle',
			'play-circle-o',
			'plug',
			'plus',
			'plus-circle',
			'plus-square',
			'plus-square-o',
			'power-off',
			'print',
			'puzzle-piece',
			'qq',
			'qrcode',
			'question',
			'question-circle',
			'quote-left',
			'quote-right',
			'ra',
			'random',
			'rebel',
			'recycle',
			'reddit',
			'reddit-square',
			'refresh',
			'remove',
			'renren',
			'reorder',
			'repeat',
			'reply',
			'reply-all',
			'retweet',
			'rmb',
			'road',
			'rocket',
			'rotate-left',
			'rotate-right',
			'rouble',
			'rss',
			'rss-square',
			'rub',
			'ruble',
			'rupee',
			'save',
			'scissors',
			'search',
			'search-minus',
			'search-plus',
			'send',
			'send-o',
			'share',
			'share-alt',
			'share-alt-square',
			'share-square',
			'share-square-o',
			'shekel',
			'sheqel',
			'shield',
			'shopping-cart',
			'sign-in',
			'sign-out',
			'signal',
			'sitemap',
			'skype',
			'slack',
			'sliders',
			'slideshare',
			'smile-o',
			'soccer-ball-o',
			'sort',
			'sort-alpha-asc',
			'sort-alpha-desc',
			'sort-amount-asc',
			'sort-amount-desc',
			'sort-asc',
			'sort-desc',
			'sort-down',
			'sort-numeric-asc',
			'sort-numeric-desc',
			'sort-up',
			'soundcloud',
			'space-shuttle',
			'spinner',
			'spoon',
			'spotify',
			'square',
			'square-o',
			'stack-exchange',
			'stack-overflow',
			'star',
			'star-half',
			'star-half-empty',
			'star-half-full',
			'star-half-o',
			'star-o',
			'steam',
			'steam-square',
			'step-backward',
			'step-forward',
			'stethoscope',
			'stop',
			'strikethrough',
			'stumbleupon',
			'stumbleupon-circle',
			'subscript',
			'suitcase',
			'sun-o',
			'superscript',
			'support',
			'table',
			'tablet',
			'tachometer',
			'tag',
			'tags',
			'tasks',
			'taxi',
			'tencent-weibo',
			'terminal',
			'text-height',
			'text-width',
			'th',
			'th-large',
			'th-list',
			'thumb-tack',
			'thumbs-down',
			'thumbs-o-down',
			'thumbs-o-up',
			'thumbs-up',
			'ticket',
			'times',
			'times-circle',
			'times-circle-o',
			'tint',
			'toggle-down',
			'toggle-left',
			'toggle-off',
			'toggle-on',
			'toggle-right',
			'toggle-up',
			'trash',
			'trash-o',
			'tree',
			'trello',
			'trophy',
			'truck',
			'try',
			'tty',
			'tumblr',
			'tumblr-square',
			'turkish-lira',
			'twitch',
			'twitter',
			'twitter-square',
			'umbrella',
			'underline',
			'undo',
			'university',
			'unlink',
			'unlock',
			'unlock-alt',
			'unsorted',
			'upload',
			'usd',
			'user',
			'user-md',
			'users',
			'video-camera',
			'vimeo-square',
			'vine',
			'vk',
			'volume-down',
			'volume-off',
			'volume-up',
			'warning',
			'wechat',
			'weibo',
			'weixin',
			'wheelchair',
			'wifi',
			'windows',
			'won',
			'wordpress',
			'wrench',
			'xing',
			'xing-square',
			'yahoo',
			'yelp',
			'yen',
			'youtube',
			'youtube-play',
			'youtube-square',
		),
	);

}