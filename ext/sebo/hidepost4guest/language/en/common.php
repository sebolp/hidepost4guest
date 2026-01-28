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

$lang = array_merge($lang, [

	'HIDE_CONTENT'			=> 'Hidden Content',
	'HIDE_POST_MESSAGE'		=> 'This content is a random content not available to simple visitors.<br>The complete content of the discussion is available only to registered users.<br>Register and log in to our forum to view this content.',
	'ALERT_HIDE'			=> '<b>Attention.</b> The display of this topic is incomplete. Some content has been obscured.<br>Only registered users have full access to the forum topic.<br>Register and log in to view all responses to this topic.',

]);
