<?php
/**
 *
 * hideposts4guest. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace sebo\hidepost4guest\controller;

/**
 * hideposts4guest ACP controller.
 */
class acp_controller
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string Custom form action */
	protected $u_action;
	
	/** @var string Custom string for your table */
    protected $your_table;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\config\config		$config		Config object
	 * @param \phpbb\language\language	$language	Language object
	 * @param \phpbb\log\log			$log		Log object
	 * @param \phpbb\request\request	$request	Request object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\user				$user		User object
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\language\language $language, \phpbb\log\log $log, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $your_table)
	{
		$this->db		= $db;
		$this->language	= $language;
		$this->log		= $log;
		$this->request	= $request;
		$this->template	= $template;
		$this->user		= $user;
		$this->your_table 	= $your_table;
		
	}

	/**
	 * Display the options a user can configure for this extension.
	 *
	 * @return void
	 */
	public function display_options()
	{
		// Add our common language file
		$this->language->add_lang('common', 'sebo/hidepost4guest');

		// Create a form key for preventing CSRF attacks
		add_form_key('sebo_hidepost4guest_acp');

		// Create an array to collect errors that will be output to the user
		$errors = [];

		$sql_4_id = '
			SELECT f.forum_id, f.forum_name, f.parent_id, f.forum_type, s.perc , s.view_alert, s.view_first
			FROM '.FORUMS_TABLE.' f
			LEFT JOIN ' . $this->your_table . ' s
			ON f.forum_id = s.forum_ids
			ORDER BY f.left_id ASC
		';
		$result_4_id = $this->db->sql_query($sql_4_id);
		$forums_data = array();
		while ($row = $this->db->sql_fetchrow($result_4_id)) {
			// Assegna il valore della colonna user_id alla variabile
			$forum_data = array(
				'forum_id' => $row['forum_id'],
				'forum_name' => $row['forum_name'],
				'parent_id' => $row['parent_id'],
				'forum_type' => $row['forum_type'],
				'perc' => isset($row['perc']) ? $row['perc'] : null,
				'first' => isset($row['view_first']) ? ($row['view_first'] == 1 ? 'Y' : 'N') : null,
				'alert' => isset($row['view_alert']) ? ($row['view_alert'] == 1 ? 'Y' : 'N') : null,
			);
			$forums_data[] = $forum_data;
		}

		$this->db->sql_freeresult($result_4_id);
						
		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			// Test if the submitted form is valid
			if (!check_form_key('sebo_hidepost4guest_acp'))
			{
				$errors[] = $this->language->lang('FORM_INVALID');
			}

			// If no errors, process the form data
			if (empty($errors))
			{
				// request variables
				$forum_id = $this->request->variable('forum_id', [0]);
				$all_forums = $this->request->variable('all_forums' , '');
				$perc = $this->request->variable('percent' , '');
				$view_alert = $this->request->variable('view_alert' , '');
				$view_first = $this->request->variable('view_first' , '');
						
				if ((!empty($forum_id))||(!empty($all_forums))){
					
					if (isset($all_forums) && $all_forums == 1) {
						
						foreach ($forums_data as $forum_data) {
							if ($forum_data['forum_type'] == 1) {
								$forum_ids = $forum_data['forum_id'];
								// start SQL array to prevent injection
								$sql_ary = [
									'forum_ids'   => $forum_ids,
									'perc'        => $perc,
									'view_alert'  => $view_alert,
									'view_first'  => $view_first
								];
							
							$sql = 'INSERT INTO ' . $this->your_table . ' ' . 
									$this->db->sql_build_array('INSERT', $sql_ary) . 
									' ON DUPLICATE KEY UPDATE ' . 
									$this->db->sql_build_array('UPDATE', $sql_ary);
							
							$this->db->sql_query($sql);
							}
						}
						
					} else {
						
						foreach ($forum_id as $forum_ids) {
							$sql = "INSERT INTO " . $this->your_table . " (forum_ids, perc, view_alert, view_first) 
									VALUES ('$forum_ids', '$perc', '$view_alert', '$view_first') 
									ON DUPLICATE KEY UPDATE perc = '$perc', view_alert = '$view_alert', view_first = '$view_first'";
							
							$this->db->sql_query($sql);
						}
					}
						
						// Option settings have been updated
						// Confirm this to the user and provide (automated) link back to previous page
						meta_refresh(3, $this->u_action);
						$message = $this->language->lang('HP4G_AGGIORNATO') . '<br /><br />' . $this->language->lang('RETURN_ACP', '<a href="' . $this->u_action . '">', '</a>');
						trigger_error($message);
						
				}
				else {
					// no forum selected
					$message = $this->language->lang('HP4G_NON_AGGIORNATO');
					$errors[] = $message;
				}

				// Add option settings change action to the admin log
				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACP_HIDEPOST4GUEST_SETTINGS');

			}
		}

		$s_errors = !empty($errors);

		// Set output variables for display in the template
		$this->template->assign_vars([
			'S_ERROR'		=> $s_errors,
			'ERROR_MSG'		=> $s_errors ? implode('<br />', $errors) : '',

			'U_ACTION'		=> $this->u_action,

			'FORUMS_LIST'	=> $forums_data,
		]);
	}

	/**
	 * Set custom form action.
	 *
	 * @param string	$u_action	Custom form action
	 * @return void
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
