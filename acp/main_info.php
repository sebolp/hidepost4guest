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
 * hideposts4guest ACP module info.
 */
class main_info
{
	public function module()
	{
		return [
			'filename'	=> '\sebo\hidepost4guest\acp\main_module',
			'title'		=> 'ACP_HIDEPOST4GUEST_TITLE',
			'modes'		=> [
				'settings'	=> [
					'title'	=> 'ACP_HIDEPOST4GUEST',
					'auth'	=> 'ext_sebo/hidepost4guest && acl_a_board',
					'cat'	=> ['ACP_HIDEPOST4GUEST_TITLE'],
				],
			],
		];
	}
}
