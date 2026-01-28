<?php

/**
 *
 * hideposts4guest. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace sebo\hidepost4guest\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * hideposts4guest Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var string Table prefix */
	protected $table_prefix;

	/** @var string Settings table name */
	protected $hp4g_table;

	/** @var array|null Cache for settings to avoid repeated DB queries */
	protected $settings_cache;

	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 *
	 * @return array The event names to listen to
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'                => 'load_language_on_setup',
			'core.viewtopic_modify_post_row' => 'edit_postrow',
		];
	}

	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language $language Language object
	 * @param \phpbb\db\driver\driver_interface $db Database object
	 * @param \phpbb\user $user User object
	 * @param string $table_prefix Table prefix
	 * @param \phpbb\template\template $template Template object
	 */
	public function __construct(\phpbb\language\language $language, \phpbb\db\driver\driver_interface $db, \phpbb\user $user, $table_prefix, \phpbb\template\template $template)
	{
		$this->language     = $language;
		$this->db           = $db;
		$this->user         = $user;
		$this->template     = $template;
		$this->table_prefix = $table_prefix;
		$this->hp4g_table   = $table_prefix . 'hp4g_settings';

		// Initialize cache
		$this->settings_cache = null;
	}

	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data $event Event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'sebo/hidepost4guest',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Modify post row
	 *
	 * @param \phpbb\event\data $event Event object
	 */
	public function edit_postrow($event)
	{
		// Use a static variable to persist the "deck of cards" across multiple method calls
		// This ensures the queue is NOT reset for every post on the page
		static $decision_queue = [];

		// 1. PERFORMANCE OPTIMIZATION (Load settings once)
		if ($this->settings_cache === null)
		{
			$this->settings_cache = [];

			$sql_array = [
				'SELECT' => '*',
				'FROM'   => [
					$this->hp4g_table => 't',
				],
			];
			$sql = $this->db->sql_build_query('SELECT', $sql_array);
			$result = $this->db->sql_query($sql);

			while ($row_db = $this->db->sql_fetchrow($result))
			{
				$this->settings_cache[(int) $row_db['forum_ids']] = [
					'view_alert' => $row_db['view_alert'],
					'perc'       => $row_db['perc'],
					'view_first' => $row_db['view_first'],
				];
			}
			$this->db->sql_freeresult($result);
		}

		// 2. ROBUST FORUM ID RETRIEVAL
		// Try multiple sources to ensure we don't fail silently
		$forum_id = 0;

		// Primary source: Topic Data (Most reliable for viewtopic)
		if (isset($event['topic_data']['forum_id']))
		{
			$forum_id = (int) $event['topic_data']['forum_id'];
		}
		// Fallback: Row data (Validator suggestion)
		elseif (isset($event['row']['forum_id']))
		{
			$forum_id = (int) $event['row']['forum_id'];
		}
		// Last resort: Event root
		elseif (isset($event['forum_id']))
		{
			$forum_id = (int) $event['forum_id'];
		}

		// 3. CHECK SETTINGS
		if ($forum_id === 0 || !isset($this->settings_cache[$forum_id]))
		{
			return;
		}

		$forum_data = $this->settings_cache[$forum_id];
		$perc = (int) $forum_data['perc'];
		$view_alert = $forum_data['view_alert'];
		$view_first = $forum_data['view_first'];

		// Apply global template variables (Alerts)
		$this->template->assign_vars(array(
			'FORUM_ID_VIEWALERT' => $view_alert,
			'HIDE_POST_MESSAGE'  => '',
			'HIDE_CONTENT'       => '',
		));

		// 4. LOGIC FOR GUESTS ONLY
		if ($this->user->data['is_registered'] == 0)
		{
			$post_row = $event['post_row'];
			$numero_post = (int) $post_row['POST_NUMBER'];
			$should_hide = false;

			// --- DECK OF CARDS LOGIC (Block of 10) ---

			// If the queue is empty, build a new shuffled deck of 10 decisions
			if (empty($decision_queue))
			{
				$block_size = 10;
				// Calculate number of posts to hide (e.g., 75% of 10 = 8)
				$count_hidden = (int) round(($block_size * $perc) / 100);
				// Ensure we don't exceed block size
				$count_hidden = max(0, min($block_size, $count_hidden));

				$count_visible = $block_size - $count_hidden;

				// Create the deck: TRUE = Hide, FALSE = Show
				$deck = array_merge(
					array_fill(0, $count_hidden, true),
					array_fill(0, $count_visible, false)
				);

				// Shuffle to randomize positions within the block
				shuffle($deck);
				$decision_queue = $deck;
			}

			// Pick the next card
			$random_decision = array_shift($decision_queue);

			// --- APPLY RULES ---

			// Rule 1: First Post Logic
			if ($numero_post == 1)
			{
				if ($view_first == 1)
				{
					$should_hide = true;
				}
				// If view_first is 0, we show it (overriding the card),
				// but the card is consumed to maintain the sequence flow.
			}
			else
			{
				// Rule 2: Use the card from the deck
				$should_hide = $random_decision;
			}

			// Execute Hiding
			if ($should_hide)
			{
				$event['post_row'] = array_merge($event['post_row'], array(
					'MESSAGE' => '',
				));

				$this->template->assign_vars(array(
					'HIDE_POST_MESSAGE' => $this->language->lang('HIDE_POST_MESSAGE'),
					'HIDE_CONTENT'      => $this->language->lang('HIDE_CONTENT'),
				));
			}
		}
	}
}
