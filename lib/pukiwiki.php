<?php
// PukiWiki - Yet another WikiWikiWeb clone.
//
// PukiWiki 1.4.*
//  Copyright (C) 2002 by PukiWiki Developers Team
//  http://pukiwiki.org/
//
// PukiWiki 1.3.*
//  Copyright (C) 2002 by PukiWiki Developers Team
//  http://pukiwiki.org/
//
// PukiWiki 1.3 (Base)
//  Copyright (C) 2001,2002 by sng.
//  <sng@factage.com>
//  http://factage.com/sng/pukiwiki/
//
// Special thanks
//  YukiWiki by Hiroshi Yuki
//  <hyuki@hyuki.com>
//  http://www.hyuki.com/yukiwiki/
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// $Id: pukiwiki.php,v 1.4.4 2004/10/14 12:58:30 miko Exp $
/////////////////////////////////////////////////

/////////////////////////////////////////////////
// �ǡ������Ǽ����ǥ��쥯�ȥ������ե�������֤��ǥ��쥯�ȥ�

if (! defined('DATA_HOME')) define('DATA_HOME', '');

/////////////////////////////////////////////////
// ���֥롼����γ�Ǽ��ǥ��쥯�ȥ� (¾�� *.php�ե�����)

if (! defined('LIB_DIR')) define('LIB_DIR', '');

/////////////////////////////////////////////////
// Include subroutines

require(LIB_DIR . 'func.php');
require(LIB_DIR . 'file.php');
require(LIB_DIR . 'funcplus.php');
require(LIB_DIR . 'plugin.php');
require(LIB_DIR . 'html.php');
require(LIB_DIR . 'backup.php');

require(LIB_DIR . 'convert_html.php');
require(LIB_DIR . 'make_link.php');
require(LIB_DIR . 'diff.php');
require(LIB_DIR . 'config.php');
require(LIB_DIR . 'link.php');
require(LIB_DIR . 'trackback.php');
require(LIB_DIR . 'auth.php');
require(LIB_DIR . 'proxy.php');
require(LIB_DIR . 'mail.php');
require(LIB_DIR . 'public_holiday.php');

if (! extension_loaded('mbstring')) {
	require(LIB_DIR . 'mbstring.php');
}

// �����: ����ե�������ɤ߹���
require(LIB_DIR . 'init.php');

/////////////////////////////////////////////////
// Main

$base    = $defaultpage;
$retvars = array();

if (isset($vars['plugin'])) {
	// Plug-in action
	if (! exist_plugin_action($vars['plugin'])) {
		$s_plugin = htmlspecialchars($vars['plugin']);
		$msg      = "plugin=$s_plugin is not implemented.";
		$retvars  = array('msg'=>$msg,'body'=>$msg);
	} else {
		$retvars  = do_plugin_action($vars['plugin']);
		if ($retvars !== FALSE)
			$base = isset($vars['refer']) ? $vars['refer'] : '';
	}

} else if (isset($vars['cmd'])) {
	// Command action
	if (! exist_plugin_action($vars['cmd'])) {
		$s_cmd   = htmlspecialchars($vars['cmd']);
		$msg     = "cmd=$s_cmd is not implemented.";
		$retvars = array('msg'=>$msg,'body'=>$msg);
	} else {
		$retvars = do_plugin_action($vars['cmd']);
		$base    = $vars['page'];
	}
}

if ($retvars !== FALSE) {
	$title = htmlspecialchars(strip_bracket($base));
	$page  = make_search($base);

	if (isset($retvars['msg']) && $retvars['msg'] != '') {
		$title = str_replace('$1', $title, $retvars['msg']);
		$page  = str_replace('$1', $page,  $retvars['msg']);
	}

	if (isset($retvars['body']) && $retvars['body'] != '') {
		$body = $retvars['body'];
	} else {
		if ($base == '' || ! is_page($base)) {
			$base  = $defaultpage;
			$title = htmlspecialchars(strip_bracket($base));
			$page  = make_search($base);
		}

		$vars['cmd']  = 'read';
		$vars['page'] = $base;
//		$body = convert_html(get_source($base));
//miko
		global $fixed_heading_edited;
		$source = get_source($base);
		// ���Ф��Խ���ưŪ�˹Ԥ�����ν���
		// convert_html �Ϻ����ػߤΤ��ᵼ���ץ饰����Ȥ���
		// (����Ȱ㤤����ʸ�������������ʤ�)
		$lines = $source;
		while (! empty($lines)) {
			$line = array_shift($lines);
			if (preg_match("/^\#(partedit)(?:\((.*)\))?/", $line, $matches)) {
				if ( !isset($matches[2]) || $matches[2] == '') {
					$fixed_heading_edited = ($fixed_heading_edited ? 0:1);
				} else if ( $matches[2] == 'on') {
					$fixed_heading_edited = 1;
				} else if ( $matches[2] == 'off') {
					$fixed_heading_edited = 0;
				}
			}
		}

		$body = convert_html($source);
//miko
		$body .= tb_get_rdf($vars['page']);
		ref_save($vars['page']);
	}

	// Output
	catbody($title, $page, $body);
}
// End
?>