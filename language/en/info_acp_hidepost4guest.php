<?php
/**
 *
 * hideposts4guest. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, [
	'ACP_HIDEPOST4GUEST_TITLE'	=> 'Hide Posts 4 Guest',
	'ACP_VIEWALERT_TITLE'	=> 'View Alert Message',
	'ACP_HIDEPOST4GUEST_EXPLAIN'	=> 'You can set some options.<br><ul><li>You must select the forums where to apply message hiding.</li><li>You can choose whether to display the alert message in the selected forums.</li><li>Set the percentage of message hiding.</li></ul><strong>Remember that the settings provided previously, if not overridden, are retained. If you want to clear all previous settings, select all forums, set the percentage to 0, and disable the alert message.</strong><br><br>The settings are overridden. To change a setting, simply provide new values.',
	'ACP_HIDEPOST4GUEST'			=> 'HidePosts4Guest Settings',
	'LOOK_UP_PERC_EXPLAIN'			=> 'Select the frequency of message hiding:<br>- <strong>100</strong> complete hiding (except the first message)<br>- <strong>0</strong> hiding disabled',
	'LOOK_UP_PERC'			=> 'Select hiding frequency',
	'HP4G_AGGIORNATO'		=> 'Hiding settings updated',
	'RETURN_ACP'			=> 'Return to control panel',
	'HP4G_NON_AGGIORNATO'	=> 'Hiding settings <strong>not</strong> updated. Forum selection field cannot be empty',
	'ACP_HIDEPOST4GUEST_VIEW_ALERT'			=> 'Display alert message in topics?',
	'FPVIEW'		=> 'Hide first post',
	'ALERT'		=> 'Display alert message in topics',
	'LEGEND'		=> 'Legend:',
	'LEGEND_TITLE'		=> 'hold your mouse on forums\'s name in forumlist for more info',
	
	'PP_ME'					=> 'Buy me a beer for creating this extension',
	'PP_ME_EXT'				=> '<label>Make a donation for this extension:</label><br><span>This extension is completely free. It is a project that I spend my time on for the enjoyment and use of the phpBB community. If you enjoy using this extension, or if it has benefited your forum, please consider <a href="https://www.paypal.com/donate/?hosted_button_id=GS3T9MFDJJGT4" target="_blank" rel="noreferrer noopener">buying me a beer</a>. It would be greatly appreciated. Thank you for downloading HidePost4Guest!</span>',
	'PP_ME_EXT_ALT'			=> 'Donate via PayPal',
		
	'PERC_EXPLAIN_TITLE'	=> 'Hiding percentage',

	'LOG_ACP_HIDEPOST4GUEST_SETTINGS'		=> '<strong>HidePost4Guest settings updated</strong>',
	'ACP_HIDE_1_POST'		=> 'Hide first post',
	'ACP_HIDE_1_POST_EXPLAIN'		=> 'Do you want to hide the first post of the topic?',

]);
