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

	'HIDE_CONTENT'			=> 'Contenuto nascosto',
	'HIDE_POST_MESSAGE'		=> 'Questo contenuto è un contenuto random non disponibile per i semplici visitatori.<br>Il contenuto completo della discussione è disponibile per i soli utenti registrati.<br>Registrati ed effettua l\'accesso al nostro forum per visualizzare questo contenuto.',
	'ALERT_HIDE'			=> '<b>Attenzione.</b> La visualizzazione di questo argomento non è completa. Alcuni contenuti sono stati oscurati.<br>Solo gli utenti registrati hanno il pieno accesso all\'argomento del forum.<br>Registrati ed effettua il login per visualizzare tutte le risposte di questo argomento.',

]);
