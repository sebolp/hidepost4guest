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
	'ACP_VIEWALERT_TITLE'	=> 'Visualizza messaggio di allerta',
	'ACP_HIDEPOST4GUEST_EXPLAIN'	=> 'Puoi impostare alcune opzioni.<br><ul><li>Devi selezionare i forum dove applicare l\'oscuramento dei messaggi.</li><li>Puoi selezionare se desideri visualizzare il messaggio di alert nei forum che scegli</li><li>Impostare la percentuale di oscuramento delle risposte.</li></ul><b>Ricorda che le impostazioni fornite in precedenza, se non sovrascritte, vengono conservate. Se vuoi cancellare tutte le precedenti impostazioni, seleziona tutti i forum, imposta la percentuale a 0 e disabilita il messaggio di allerta.</b><br><br>Le impostazioni vengono sovrascritte. Per modificare una impostazione, basta fornire nuovi valori.',
	'ACP_HIDEPOST4GUEST'			=> 'HidePosts4Guest Settings',
	'LOOK_UP_PERC_EXPLAIN'			=> 'Seleziona la frequenza di oscuramento dei messaggi:<br>- <b>100</b> oscuramento completo (tranne il primo messaggio)<br>- <b>0</b> oscuramento disattivato',
	'LOOK_UP_PERC'			=> 'Seleziona frequenza oscuramento',
	'HP4G_AGGIORNATO'		=> 'Impostazioni di oscuramento aggiornate',
	'RETURN_ACP'			=> 'Torna al pannello di controllo',
	'HP4G_NON_AGGIORNATO'	=> 'Impostazioni di oscuramento <strong>non</strong> aggiornate. Il campo di selezione forum non puo\' essere vuoto',
	'ACP_HIDEPOST4GUEST_VIEW_ALERT'			=> 'Visualizzare messaggio di allerta negli argomenti?',
	'FPVIEW'		=> 'Oscura primo messaggio',
	'ALERT'		=> 'Visualizza messaggio di alert negli argomenti',
	'LEGEND'		=> 'Leggenda',
	'LEGEND_TITLE'		=> 'mantieni il mouse sul nome del forum nella lista per maggiori informazioni',
	
	'PP_ME'					=> 'Offrimi una birra per questa estensione',
	'PP_ME_EXT'				=> '<label>Fai una donazione per questa estensione:</label><br><span>Questa estensione è completamente gratuita. E\' un progetto su cui ho speso del tempo per imparare e condividere con la community phpBB. Se ti piace questa estensione, o ha migliorato il tuo forum, prendi in considerazione l\'idea di <a href="https://www.paypal.com/donate/?hosted_button_id=GS3T9MFDJJGT4" target="_blank" rel="noreferrer noopener">offrirmi una birra</a>. Grazie mille anche solo per aver scaricato HidePost4Guest!</span>',
	'PP_ME_EXT_ALT'			=> 'Effettua una donazione con PayPal',
	
	'PERC_EXPLAIN_TITLE'	=> 'Percentuale di oscuramento:',

	'LOG_ACP_HIDEPOST4GUEST_SETTINGS'		=> '<strong>Impostazioni HidePost4Guest aggiornate</strong>',
	'ACP_HIDE_1_POST'		=> 'Nascondi il primo messaggio',
	'ACP_HIDE_1_POST_EXPLAIN'		=> 'Vuoi oscurare anche il primo post del topic?',
]);
