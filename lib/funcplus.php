<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: funcplus.php,v 0.1.4 2004/10/13 13:17:36 miko Exp $
//

// ���󥯥롼�ɤ�;�פʤ�Τϥ���������������
function convert_filter($str)
{
	global $filter_rules;
	static $patternf,$replacef;

	if (!isset($patternf))
	{
		$patternf = array_map(create_function('$a','return "/$a/";'),array_keys($filter_rules));
		$replacef = array_values($filter_rules);
		unset($filter_rules);
	}
	return preg_replace($patternf,$replacef,$str);
}

function get_fancy_uri()
{
        $script  = (SERVER_PORT == 443 ? 'https://' : 'http://');       // scheme
        $script .= SERVER_NAME; // host
        $script .= (SERVER_PORT == 80 ? '' : ':' . SERVER_PORT); // port

        // SCRIPT_NAME ��'/'�ǻϤޤäƤ��ʤ����(cgi�ʤ�) REQUEST_URI��ȤäƤߤ�
        $path    = SCRIPT_NAME;
        $script .= $path;       // path

        return $script;
}

function mb_ereg_quote($str)
{
	return mb_ereg_replace('([.\\+*?\[^\]\$(){}=!<>|:])', '\\\1', $str);
}

// �������ɲ�
function open_uri_in_new_window($anchor, $which)
{
	global $use_open_uri_in_new_window,		// ���δؿ���Ȥ����ݤ�
	       $open_uri_in_new_window_opis,		// Ʊ�쥵���С�(Farm?)
	       $open_uri_in_new_window_opisi,		// Ʊ�쥵���С�(Farm?)��InterWiki
	       $open_uri_in_new_window_opos,		// ���������С�
	       $open_uri_in_new_window_oposi;		// ���������С���InterWiki
	global $_symbol_extanchor, $_symbol_innanchor;	// ����������ɥ��򳫤���������
	
	// ���δؿ���Ȥ�ʤ� OR �ƤӽФ����������ʾ��ϥ��롼����
	if (!$use_open_uri_in_new_window || !$which || !$_symbol_extanchor || !$_symbol_innanchor) {
		return $anchor;
	}

	// ���������Υ�󥯤�ɤ����뤫
	$frame = '';
	if ($which == 'link_interwikiname') {
		$frame = (is_inside_uri($anchor) ? $open_uri_in_new_window_opisi:$open_uri_in_new_window_oposi);
		$symbol = (is_inside_uri($anchor) ? $_symbol_innanchor:$_symbol_extanchor);
		$aclass = (is_inside_uri($anchor) ? 'class="inn" ':'class="ext" ');
	} elseif ($which == 'link_url') {
		$frame = (is_inside_uri($anchor) ? $open_uri_in_new_window_opis:$open_uri_in_new_window_opos);
		$symbol = (is_inside_uri($anchor) ? $_symbol_innanchor:$_symbol_extanchor);
		$aclass = (is_inside_uri($anchor) ? 'class="inn" ':'class="ext" ');
	}

	if ($frame == '')
		return $anchor;

	// ���� $anchor �� a ��������˥��饹�Ϥʤ�
	$aclasspos = mb_strpos($anchor, '<a ', mb_detect_encoding($anchor)) + 3; // 3 is strlen('<a ')
	$insertpos = mb_strpos($anchor, '</a>', mb_detect_encoding($anchor));
	preg_match('#href="([^"]+)"#', $anchor, $href);

	return (mb_substr($anchor, 0, $aclasspos) . $aclass .
		mb_substr($anchor, $aclasspos, $insertpos-$aclasspos)
	        . str_replace('$1', $href[1], str_replace('$2', $frame, $symbol)) . mb_substr($anchor, $insertpos));
}

function is_inside_uri($anchor)
{
	global $open_uri_in_new_window_servername;

	foreach ($open_uri_in_new_window_servername as $servername) {
		if (stristr($anchor, $servername)) {
			return true;
		}
	}
	return false;
}
?>