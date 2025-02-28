<?php
/**
 *
 * hideposts4guest. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace sebo\hidepost4guest\migrations;

class install_acp_module extends \phpbb\db\migration\migration
{

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function update_data()
	{
		return [
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_HIDEPOST4GUEST_TITLE'
			]],
			['module.add', [
				'acp',
				'ACP_HIDEPOST4GUEST_TITLE',
				[
					'module_basename'	=> '\sebo\hidepost4guest\acp\main_module',
					'modes'				=> ['settings'],
				],
			]],
		];
	}
}
