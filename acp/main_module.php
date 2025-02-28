<?php
/**
 *
 * hideposts4guest. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace sebo\hidepost4guest\acp;

/**
 * hideposts4guest ACP module.
 */
class main_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	/**
	 * Main ACP module
	 *
	 * @param int    $id   The module ID
	 * @param string $mode The module mode (for example: manage or settings)
	 * @throws \Exception
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		/** @var \sebo\hidepost4guest\controller\acp_controller $acp_controller */
		$acp_controller = $phpbb_container->get('sebo.hidepost4guest.controller.acp');

		// Load a template from adm/style for our ACP page
		$this->tpl_name = 'acp_hidepost4guest_body';

		// Set the page title for our ACP page
		$this->page_title = 'ACP_HIDEPOST4GUEST_TITLE';

		// Make the $u_action url available in our ACP controller
		$acp_controller->set_page_url($this->u_action);

		// Load the display options handle in our ACP controller
		$acp_controller->display_options();
	}
}
