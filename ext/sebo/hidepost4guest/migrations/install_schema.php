<?php

/**
 *
 * hidepost4guest. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace sebo\hidepost4guest\migrations;

class install_sample_schema extends \phpbb\db\migration\migration
{

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function update_schema()
	{
		return [
			'add_tables'    => [
				$this->table_prefix . 'hp4g_settings' => [
					'COLUMNS' => [
						'forum_ids'         => ['UINT:4', '0'],
						'view_alert'        => ['UINT:4', '0'],
						'perc'         		=> ['UINT:3', '0'],
						'view_first'		=> ['UINT:1', '0'],
					],
					'PRIMARY_KEY' => 'forum_ids',
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables'    => [
				$this->table_prefix . 'hp4g_settings',
			],
		];
	}
}
