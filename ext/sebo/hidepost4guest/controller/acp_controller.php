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

	/** @var string Table prefix */
	protected $table_prefix;

	/** @var string Settings table name */
	protected $hp4g_table;

	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\language\language $language, \phpbb\log\log $log, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $table_prefix)
	{
		$this->db           = $db;
		$this->language     = $language;
		$this->log          = $log;
		$this->request      = $request;
		$this->template     = $template;
		$this->user         = $user;
		$this->table_prefix = $table_prefix;
		$this->hp4g_table   = $table_prefix . 'hp4g_settings';
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

		$errors = [];

		// Recuperiamo l'azione dalla barra degli indirizzi (GET)
		$action = $this->request->variable('action', '');

		// --- LOGICA RESET (Adattata per link GET) ---
		if ($action === 'reset')
		{
			// Svuota completamente la tabella
			$sql = 'DELETE FROM ' . $this->hp4g_table;
			$this->db->sql_query($sql);

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACP_HIDEPOST4GUEST_RESET');

			// Ricarica la pagina base (senza action=reset per evitare loop)
			meta_refresh(3, $this->u_action);
			trigger_error($this->language->lang('HP4G_RESET_SUCCESS') . adm_back_link($this->u_action));
		}

		// --- START LOGIC: CHECK GUEST PERMISSIONS ---
		// Vogliamo sapere in quali forum i Guest hanno il permesso 'f_read'

		// 1. Recupera ID del gruppo GUESTS
		$sql = "SELECT group_id FROM " . GROUPS_TABLE . " WHERE group_name = 'GUESTS'";
		$result = $this->db->sql_query($sql);
		$guest_group_id = (int) $this->db->sql_fetchfield('group_id');
		$this->db->sql_freeresult($result);

		// 2. Recupera ID dell'opzione 'f_read'
		$sql = "SELECT auth_option_id FROM " . ACL_OPTIONS_TABLE . " WHERE auth_option = 'f_read'";
		$result = $this->db->sql_query($sql);
		$f_read_id = (int) $this->db->sql_fetchfield('auth_option_id');
		$this->db->sql_freeresult($result);

		// 3. Recupera i Ruoli che hanno 'f_read' attivo (auth_setting = 1)
		$f_read_roles = [];
		if ($f_read_id)
		{
			$sql = "SELECT role_id FROM " . ACL_ROLES_DATA_TABLE . " WHERE auth_option_id = $f_read_id AND auth_setting = 1";
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$f_read_roles[] = (int) $row['role_id'];
			}
			$this->db->sql_freeresult($result);
		}

		// 4. Trova i Forum dove il gruppo Guest ha accesso (tramite opzione diretta O tramite ruolo)
		$guest_visible_forums = [];
		if ($guest_group_id && $f_read_id)
		{
			// Costruiamo la condizione: O ha l'opzione settata a 1, O ha un ruolo che la contiene
			$sql_where = "group_id = $guest_group_id AND (
                (auth_option_id = $f_read_id AND auth_setting = 1)";

			if (!empty($f_read_roles))
			{
				$roles_str = implode(',', $f_read_roles);
				$sql_where .= " OR auth_role_id IN ($roles_str)";
			}
			$sql_where .= ")";

			$sql = "SELECT forum_id FROM " . ACL_GROUPS_TABLE . " WHERE $sql_where";
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$guest_visible_forums[] = (int) $row['forum_id'];
			}
			$this->db->sql_freeresult($result);
		}
		// --- END LOGIC ---

		// Main Query (La tua query originale)
		$sql_array = [
			'SELECT'    => 'f.forum_id, f.forum_name, f.parent_id, f.forum_type, s.perc, s.view_alert, s.view_first',
			'FROM'      => [
				FORUMS_TABLE => 'f'
			],
			'LEFT_JOIN' => [
				[
					'FROM' => [$this->hp4g_table => 's'],
					'ON'   => 'f.forum_id = s.forum_ids'
				]
			],
			'ORDER_BY'  => 'f.left_id ASC'
		];

		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);

		$forums_data = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			// Verifica se questo forum è nella lista dei visibili
			$is_visible = in_array($row['forum_id'], $guest_visible_forums);

			$forums_data[] = [
				'forum_id'   => (int) $row['forum_id'],
				'forum_name' => $row['forum_name'],
				'parent_id'  => (int) $row['parent_id'],
				'forum_type' => (int) $row['forum_type'],
				'perc'       => isset($row['perc']) ? $row['perc'] : null,
				'first'      => isset($row['view_first']) ? ($row['view_first'] == 1 ? 'Y' : 'N') : null,
				'alert'      => isset($row['view_alert']) ? ($row['view_alert'] == 1 ? 'Y' : 'N') : null,
				'guest_visible' => $is_visible,
			];
		}
		$this->db->sql_freeresult($result);

		// reset entire table
		if ($this->request->is_set_post('reset'))
		{
			if (!check_form_key('sebo_hidepost4guest_acp'))
			{
				$errors[] = $this->language->lang('FORM_INVALID');
			}

			if (empty($errors))
			{
				// prune table
				$sql = 'DELETE FROM ' . $this->hp4g_table;
				$this->db->sql_query($sql);

				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACP_HIDEPOST4GUEST_RESET');

				meta_refresh(3, $this->u_action);
				trigger_error($this->language->lang('HP4G_RESET_SUCCESS') . adm_back_link($this->u_action));
			}
		}

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('sebo_hidepost4guest_acp'))
			{
				$errors[] = $this->language->lang('FORM_INVALID');
			}

			if (empty($errors))
			{
				$forum_id_list = $this->request->variable('forum_id', [0]);
				$all_forums    = $this->request->variable('all_forums', 0);
				$perc          = $this->request->variable('percent', 0);
				$view_alert    = $this->request->variable('view_alert', 0);
				$view_first    = $this->request->variable('view_first', 0);

				if (!empty($forum_id_list) || $all_forums == 1)
				{
					$ids_to_process = [];

					if ($all_forums == 1)
					{
						foreach ($forums_data as $f)
						{
							if ($f['forum_type'] == 1) // FORUM_POST
							{
								$ids_to_process[] = $f['forum_id'];
							}
						}
					}
					else
					{
						$ids_to_process = $forum_id_list;
					}

					foreach ($ids_to_process as $fid)
					{
						$sql_ary = [
							'forum_ids'  => (int) $fid,
							'perc'       => (int) $perc,
							'view_alert' => (int) $view_alert,
							'view_first' => (int) $view_first
						];

						$sql = 'INSERT INTO ' . $this->hp4g_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary) . '
                            ON DUPLICATE KEY UPDATE ' . $this->db->sql_build_array('UPDATE', $sql_ary);

						$this->db->sql_query($sql);
					}

					$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACP_HIDEPOST4GUEST_SETTINGS');

					meta_refresh(3, $this->u_action);
					trigger_error($this->language->lang('HP4G_AGGIORNATO') . adm_back_link($this->u_action));
				}
				else
				{
					$errors[] = $this->language->lang('HP4G_NON_AGGIORNATO');
				}
			}
		}

		$this->template->assign_vars([
			'S_ERROR'     => !empty($errors),
			'ERROR_MSG'   => implode('<br />', $errors),
			'U_ACTION'    => $this->u_action,
			'FORUMS_LIST' => $forums_data,
			'LINK_DONATE' => 'https://www.paypal.com/donate/?hosted_button_id=GS3T9MFDJJGT4',
			'U_RESET'         => $this->u_action . '&amp;action=reset',
			'L_RESET_CONFIRM' => $this->language->lang('HP4G_RESET_CONFIRM'),
		]);
	}

	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
