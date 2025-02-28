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

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * hideposts4guest Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'							=> 'load_language_on_setup',
			'core.viewtopic_before_f_read_check'		=> 'know_forum',
			'core.viewtopic_highlight_modify'			=> 'number_posts',
			'core.viewtopic_modify_post_row'			=> 'edit_postrow',
		];
	}

	/* @var \phpbb\language\language */
	protected $language;
	
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	
	protected $user;
	
	/** @var string Custom string for your table */
    protected $your_table;
	
	/** @var \phpbb\template\template */
	protected $template;
	
	/** @var int Forum ID */
	protected $forum_id;

	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language	$language	Language object
	 */
	public function __construct(\phpbb\language\language $language, \phpbb\db\driver\driver_interface $db, \phpbb\user $user, $your_table, \phpbb\template\template $template)
	{
		$this->language		= $language;
		$this->db			= $db;
		$this->user			= $user;
		$this->your_table 	= $your_table;
		$this->template		= $template;
		$this->forum_id		= null;
		
		
	}

	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data	$event	Event object
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

	public function know_forum($event)
	{
		if (isset($event['forum_id'])) { 
			$this->forum_id = $event['forum_id'];
		}
	}
	
	public function number_posts($event)
	{
		$total_post = $event['total_posts'];
	}

	public function edit_postrow($event)
	{
		$forum_id = $this->forum_id;
		
			// select forums
			$sql = 'SELECT * FROM ' . $this->your_table ;
			$result = $this->db->sql_query($sql);
			$forums_data = array();
			while ($row = $this->db->sql_fetchrow($result)) {
			
				$forum_data = array(
					'forum_ids' => $row['forum_ids'],
					'view_alert' => $row['view_alert'],
					'perc' => $row['perc'],
					'view_first' => $row['view_first'],
				);
				$forums_data[] = $forum_data;
			}
			$this->db->sql_freeresult($result);
			
			
			$found = false;
			$perc = null;
			$view_alert = null;
			$view_first = null;

			foreach ($forums_data as $forum_data) {
				if ($forum_data['forum_ids'] == $forum_id) {
					// forum_id founded in the array
					$found = true;
					$perc = $forum_data['perc'];
					$view_alert = $forum_data['view_alert'];
					$view_first = $forum_data['view_first'];
					break; 
				}
			}
			
			$post_row = $event['post_row'];
			$numero_post = $post_row['POST_NUMBER'];
			
			if ($found) {
				// forum_id founded in the array
				$this->template->assign_vars(array(
					'FORUM_ID_VIEWALERT' => $view_alert,
					'HIDE_POST_MESSAGE'=> '',
					'HIDE_CONTENT'	=> '',
					
				));
												
				$perc_views = $perc;
				$visibile = mt_rand(1, 100);
				
				if (($view_first == 1) && ($numero_post == 1)&&($this->user->data['is_registered'] == 0)){
					$event['post_row'] = array_merge($event['post_row'], array(
				
						'MESSAGE'			=> '',
					
					));
					$this->template->assign_vars(array(
						'HIDE_POST_MESSAGE' => $this->language->lang('HIDE_POST_MESSAGE'),
						'HIDE_CONTENT' 		=> $this->language->lang('HIDE_CONTENT'),
						));
				}
				else if (($visibile <= $perc_views)&&($numero_post != 1)&&($this->user->data['is_registered'] == 0)) {
					// hide 4 guests
					$event['post_row'] = array_merge($event['post_row'], array(
				
						'MESSAGE'			=> '',

					));
					$this->template->assign_vars(array(
						'HIDE_POST_MESSAGE' => $this->language->lang('HIDE_POST_MESSAGE'),
						'HIDE_CONTENT' 		=> $this->language->lang('HIDE_CONTENT'),
						));
					
				}
			}			
	}
	
}
