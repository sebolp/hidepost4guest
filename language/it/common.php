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

	'HIDE_CONTENT'			=> 'Contenuto nascosto',
	'HIDE_POST_MESSAGE'		=> 'Questo contenuto è un contenuto random non disponibile per i semplici visitatori.<br>Il contenuto completo della discussione è disponibile per i soli utenti registrati.<br>Registrati ed effettua l\'accesso al nostro forum per visualizzare questo contenuto.',
	'ALERT_HIDE'			=> '<b>Attenzione.</b> La visualizzazione di questo argomento non è completa. Alcuni contenuti sono stati oscurati.<br>Solo gli utenti registrati hanno il pieno accesso all\'argomento del forum.<br>Registrati ed effettua il login per visualizzare tutte le risposte di questo argomento.',

]);
