<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: func.php,v 1.26.6 2005/01/10 04:18:46 miko Exp $
//
// General functions

function is_interwiki($str)
{
	global $InterWikiName;
	return preg_match('/^' . $InterWikiName . '$/', $str);
}

function is_pagename($str)
{
	global $BracketName;

	$is_pagename = (! is_interwiki($str) &&
		  preg_match('/^(?!\/)' . $BracketName . '$(?<!\/$)/', $str) &&
		! preg_match('#(^|/)\.{1,2}(/|$)#', $str));

	if (defined('SOURCE_ENCODING')) {
		switch(SOURCE_ENCODING){
		case 'UTF-8': $pattern =
			'/^(?:[\x00-\x7F]|(?:[\xC0-\xDF][\x80-\xBF])|(?:[\xE0-\xEF][\x80-\xBF][\x80-\xBF]))+$/';
			break;
		case 'EUC-JP': $pattern =
			'/^(?:[\x00-\x7F]|(?:[\x8E\xA1-\xFE][\xA1-\xFE])|(?:\x8F[\xA1-\xFE][\xA1-\xFE]))+$/';
			break;
		}
		if (isset($pattern) && $pattern != '')
			$is_pagename = ($is_pagename && preg_match($pattern, $str));
	}

	return $is_pagename;
}

function is_url($str, $only_http = FALSE)
{
	$scheme = $only_http ? 'https?' : 'https?|ftp|news';
	return preg_match('/^(' . $scheme . ')(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]*)$/', $str);
}

// If the page exists
function is_page($page, $clearcache = FALSE)
{
	if ($clearcache) clearstatcache();
	return file_exists(get_filename($page));
}

function is_editable($page)
{
	global $cantedit;
	static $is_editable = array();

	if (! isset($is_editable[$page])) {
		$is_editable[$page] = (
			is_pagename($page) &&
			! is_freeze($page) &&
			! in_array($page, $cantedit)
		);
	}

	return $is_editable[$page];
}

function is_freeze($page, $clearcache = FALSE)
{
	global $function_freeze;
	static $is_freeze = array();

	if ($clearcache === TRUE) $is_freeze = array();
	if (isset($is_freeze[$page])) return $is_freeze[$page];

	if (! $function_freeze || ! is_page($page)) {
		$is_freeze[$page] = FALSE;
		return FALSE;
	} else {
		$fp     = fopen(get_filename($page), 'rb');
		flock($fp, LOCK_SH);
		rewind($fp);
		$buffer = fgets($fp, 8);
		flock($fp, LOCK_UN);
		fclose($fp);
		$is_freeze[$page] = ($buffer != FALSE && rtrim($buffer, "\r\n") == '#freeze');
		return $is_freeze[$page];
	}
}

// 自動テンプレート
function auto_template($page)
{
	global $auto_template_func, $auto_template_rules;

	if (! $auto_template_func) return '';

	$body = '';
	$matches = array();
	foreach ($auto_template_rules as $rule => $template) {
		$rule_pattrn = '/' . $rule . '/';

		if (! preg_match($rule_pattrn, $page, $matches)) continue;

		$template_page = preg_replace($rule_pattrn, $template, $page);
		if (! is_page($template_page)) continue;

		$body = join('', get_source($template_page));

		// Remove fixed-heading anchors
		$body = preg_replace('/^(\*{1,3}.*)\[#[A-Za-z][\w-]+\](.*)$/m', '$1$2', $body);

		// Remove '#freeze'
		$body = preg_replace('/^#freeze\s*$/m', '', $body);

		$count = count($matches);
		for ($i = 0; $i < $count; $i++)
			$body = str_replace('$' . $i, $matches[$i], $body);

		break;
	}
	return $body;
}

// 検索語を展開する
function get_search_words($words, $special = FALSE)
{
	$retval = array();

	// Perlメモ - 正しくパターンマッチさせる
	// http://www.din.or.jp/~ohzaki/perl.htm#JP_Match

	$eucpre = $eucpost = '';
	if (SOURCE_ENCODING == 'EUC-JP') {
		$eucpre = '(?<!\x8F)';
		// # JIS X 0208 が 0文字以上続いて # ASCII, SS2, SS3 または終端
		$eucpost = '(?=(?:[\xA1-\xFE][\xA1-\xFE])*(?:[\x00-\x7F\x8E\x8F]|\z))';
	}
	$quote_func = create_function('$str', 'return preg_quote($str, \'/\');');

	// LANG == 'ja'で、mb_convert_kanaが使える場合はmb_convert_kanaを使用
	$convert_kana = create_function('$str, $option',
		(LANG == 'ja' && function_exists('mb_convert_kana')) ?
			'return mb_convert_kana($str, $option);' : 'return $str;');

	foreach ($words as $word) {
		// 英数字は半角,カタカナは全角,ひらがなはカタカナに
		$word_zk = $convert_kana($word, 'aKCV');
		$chars = array();
		for ($pos = 0; $pos < mb_strlen($word_zk); $pos++) {
			$char = mb_substr($word_zk, $pos, 1);
			// $special : htmlspecialchars()を通すか
			$arr = array($quote_func($special ? htmlspecialchars($char) : $char));
			if (strlen($char) == 1) {
				// 英数字
				foreach (array(strtoupper($char), strtolower($char)) as $_char) {
					if ($char != '&')
						$arr[] = $quote_func($_char);
					$ord = ord($_char);
					$arr[] = sprintf('&#(?:%d|x%x);', $ord, $ord); // 実体参照
					$arr[] = $quote_func($convert_kana($_char, 'A')); // 全角
				}
			} else {
				// マルチバイト文字
				$arr[] = $quote_func($convert_kana($char, 'c')); // ひらがな
				$arr[] = $quote_func($convert_kana($char, 'k')); // 半角カタカナ
			}
			$chars[] = '(?:' . join('|', array_unique($arr)) . ')';
		}
		$retval[$word] = $eucpre.join('', $chars) . $eucpost;
	}
	return $retval;
}

// 'Search' main function
function do_search($word, $type = 'AND', $non_format = FALSE, $non_fuzzy = FALSE)
{
	global $script, $whatsnew, $non_list, $search_non_list;
	global $_msg_andresult, $_msg_orresult, $_msg_notfoundresult;
 	global $search_auth, $search_fuzzy;

	static $fuzzypattern = array(
		'ヴァ' => 'バ',	'ヴィ' => 'ビ',	'ヴェ' => 'ベ',	'ヴォ' => 'ボ',
		'ヴ' => 'ブ',	'ヰ' => 'イ',	'ヱ' => 'エ',	'ヵ' => 'カ',
		'ァ' => 'ア',	'ィ' => 'イ',	'ゥ' => 'ウ',	'ェ' => 'エ',
		'ォ' => 'オ',	'ャ' => 'ヤ',	'ュ' => 'ユ',	'ョ' => 'ヨ');

	$retval = array();

	$b_type = ($type == 'AND'); // AND:TRUE OR:FALSE
	$keys = get_search_words(preg_split('/\s+/', $word, -1, PREG_SPLIT_NO_EMPTY));

	$_pages = get_existpages();
	$pages = array();

	$non_list_pattern = '/' . $non_list . '/';
	foreach ($_pages as $page) {
		if ($page == $whatsnew || (! $search_non_list && preg_match($non_list_pattern, $page)))
			continue;

		// 検索対象ページの制限をかけるかどうか (ページ名は制限外)
		if ($search_auth && ! check_readable($page, false, false)) {
			$source = get_source(); // 検索対象ページ内容を空に。
		} else {
			$source = get_source($page);
		}
		if (! $non_format)
			array_unshift($source, $page); // ページ名も検索対象に

		$b_match = FALSE;
//miko modified
		if (!$search_fuzzy || $non_fuzzy) {
			foreach ($keys as $key) {
				$tmp     = preg_grep('/' . $key . '/', $source);
				$b_match = ! empty($tmp);
				if ($b_match xor $b_type) break;
			}
			if ($b_match) $pages[$page] = get_filetime($page);
		} else {
			$fuzzy_from = array_keys($fuzzypattern);
			$fuzzy_to = array_values($fuzzypattern);
			$words = preg_split('/\s+/', $word, -1, PREG_SPLIT_NO_EMPTY);
			$_source = mb_strtolower(mb_convert_kana(join("\n",$source), 'KVCas'));
			for ($i=0; $i<count($fuzzy_from); $i++) {
				$_source = mb_ereg_replace($fuzzy_from[$i], $fuzzy_to[$i], $_source);
			}
			$_source = mb_ereg_replace('[ッー・゛゜、。]', '', $_source);
			foreach ($keys as $key) {
				$_keyword = mb_strtolower(mb_convert_kana($word, 'KVCas'));
				for ($i=0; $i<count($fuzzy_from); $i++) {
					$_keyword = mb_ereg_replace($fuzzy_from[$i], $fuzzy_to[$i], $_keyword);
				}
				$_keyword = mb_ereg_replace('[ッー・゛゜、。]', '', $_keyword);
				$b_match = mb_ereg(mb_ereg_quote($_keyword), $_source);
			}
			if ($b_match) $pages[$page] = get_filetime($page);
		}
//miko modified
	}
	if ($non_format) return array_keys($pages);

	$r_word = rawurlencode($word);
	$s_word = htmlspecialchars($word);
	if (empty($pages))
		return str_replace('$1', $s_word, $_msg_notfoundresult);

	ksort($pages);
	$retval = '<ul>' . "\n";
	foreach ($pages as $page=>$time) {
		$r_page  = rawurlencode($page);
		$s_page  = htmlspecialchars($page);
		$passage = get_passage($time);
		$retval .= ' <li><a href="' . $script . '?cmd=read&amp;page=' .
			$r_page . '&amp;word=' . $r_word . '">' . $s_page .
			'</a>' . $passage . '</li>' . "\n";
	}
	$retval .= '</ul>' . "\n";

	$retval .= str_replace('$1', $s_word, str_replace('$2', count($pages),
		str_replace('$3', count($_pages), $b_type ? $_msg_andresult : $_msg_orresult)));

	return $retval;
}

// プログラムへの引数のチェック
function arg_check($str)
{
	global $vars;
	return isset($vars['cmd']) && (strpos($vars['cmd'], $str) === 0);
}

// ページ名のエンコード
function encode($key)
{
	return ($key == '') ? '' : strtoupper(bin2hex($key));
	// Equal to strtoupper(join('', unpack('H*0', $key)));
	// But PHP 4.3.10 says 'Warning: unpack(): Type H: outside of string in ...'
}

// ページ名のデコード
function decode($key)
{
	// Warning: pack(): Type H: illegal hex digit ...
	return preg_match('/^[0-9a-f]+$/i', $key) ? pack('H*', $key) : $key;
}

// [[ ]] を取り除く
function strip_bracket($str)
{
	$match = array();
	if (preg_match('/^\[\[(.*)\]\]$/', $str, $match)) {
		return $match[1];
	} else {
		return $str;
	}
}

// ページ一覧の作成
function page_list($pages, $cmd = 'read', $withfilename = FALSE)
{
	global $script, $list_index;
	global $_msg_symbol, $_msg_other;
	global $pagereading_enable;

	// ソートキーを決定する。 ' ' < '[a-zA-Z]' < 'zz'という前提。
	$symbol = ' ';
	$other = 'zz';

	$retval = '';

	if($pagereading_enable) {
		mb_regex_encoding(SOURCE_ENCODING);
		$readings = get_readings($pages);
	}

	$list = $matches = array();
	foreach($pages as $file=>$page) {
		$r_page  = rawurlencode($page);
		$s_page  = htmlspecialchars($page, ENT_QUOTES);
		$passage = get_pg_passage($page);

		$str = '   <li><a href="' .
			$script . '?cmd=' . $cmd . '&amp;page=' . $r_page .
			'">' . $s_page . '</a>' . $passage;

		if ($withfilename) {
			$s_file = htmlspecialchars($file);
			$str .= "\n" . '    <ul><li>' . $s_file . '</li></ul>' .
				"\n" . '   ';
		}
		$str .= '</li>';

		// WARNING: Japanese code hard-wired
		if($pagereading_enable) {
			if(mb_ereg('^([A-Za-z])', mb_convert_kana($page, 'a'), $matches)) {
				$head = $matches[1];
			} elseif(isset($readings[$page]) && mb_ereg('^([ァ-ヶ])', $readings[$page], $matches)) { // here
				$head = $matches[1];
			} elseif (mb_ereg('^[ -~]|[^ぁ-ん亜-熙]', $page)) { // and here
				$head = $symbol;
			} else {
				$head = $other;
			}
		} else {
			$head = (preg_match('/^([A-Za-z])/', $page, $matches)) ? $matches[1] :
				(preg_match('/^([ -~])/', $page, $matches) ? $symbol : $other);
		}

		$list[$head][$page] = $str;
	}
	ksort($list);

	$cnt = 0;
	$arr_index = array();
	$retval .= '<ul>' . "\n";
	foreach ($list as $head=>$pages) {
		if ($head === $symbol) {
			$head = $_msg_symbol;
		} else if ($head === $other) {
			$head = $_msg_other;
		}

		if ($list_index) {
			++$cnt;
			$arr_index[] = '<a id="top_' . $cnt .
				'" href="#head_' . $cnt . '"><strong>' .
				$head . '</strong></a>';
			$retval .= ' <li><a id="head_' . $cnt . '" href="#top_' . $cnt .
				'"><strong>' . $head . '</strong></a>' . "\n" .
				'  <ul>' . "\n";
		}
		ksort($pages);
		$retval .= join("\n", $pages);
		if ($list_index)
			$retval .= "\n  </ul>\n </li>\n";
	}
	$retval .= '</ul>' . "\n";
	if ($list_index && $cnt > 0) {
		$top = array();
		while (! empty($arr_index))
			$top[] = join(' | ' . "\n", array_splice($arr_index, 0, 16)) . "\n";

		$retval = '<div id="top" style="text-align:center">' . "\n" .
			join('<br />', $top) . '</div>' . "\n" . $retval;
	}
	return $retval;
}

// Show text formatting rules
function catrule()
{
	global $rule_page;

	if (! is_page($rule_page)) {
		return '<p>Sorry, page \'' . htmlspecialchars($rule_page) .
			'\' unavailable.</p>';
	} else {
		return convert_html(get_source($rule_page));
	}
}

// Show (critical) error message
function die_message($msg)
{
	global $skin_file;
	$title = $page = 'Runtime error';
	$body = <<<EOD
<h3>Runtime error</h3>
<strong>Error message : $msg</strong>
EOD;

	pkwk_common_headers();
	if(defined('SKIN_FILE') && file_exists(SKIN_FILE) && is_readable(SKIN_FILE)) {
		catbody($title, $page, $body);
	} elseif ($skin_file != '' && file_exists($skin_file) && is_readable($skin_file)) {
		define(SKIN_FILE, $skin_file);
		catbody($title, $page, $body);
	} else {
		header('Content-Type: text/html; charset=euc-jp');
		print <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>$title</title>
  <meta http-equiv="content-type" content="text/html; charset=euc-jp">
 </head>
 <body>
 $body
 </body>
</html>
EOD;
	}
	exit;
}

// 現在時刻をマイクロ秒で取得
function getmicrotime()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$sec + (float)$usec);
}

// 日時を得る
function get_date($format, $timestamp = NULL)
{
	$format = preg_replace('/(?<!\\\)T/',
		preg_replace('/(.)/', '\\\$1', ZONE), $format);

	$time = ZONETIME + (($timestamp !== NULL) ? $timestamp : UTIME);

	return date($format, $time);
}

// 日時文字列を作る
function format_date($val, $paren = FALSE)
{
	global $date_format, $time_format, $weeklabels;

	$val += ZONETIME;

	$date = date($date_format . ' ' . $time_format, $val) .
		' ' . '(' . $weeklabels[date('w', $val)] . ')';

	return $paren ? '(' . $date . ')' : $date;
}

// 経過時刻文字列を作る
function get_passage($time, $paren = TRUE)
{
	static $units = array('m'=>60, 'h'=>24, 'd'=>1);

	$time = max(0, (UTIME - $time) / 60); // minutes

	foreach ($units as $unit=>$card) {
		if ($time < $card) break;
		$time /= $card;
	}
	$time = floor($time) . $unit;

	return $paren ? '(' . $time . ')' : $time;
}

// Hide <input type="(submit|button|image)"...>
function drop_submit($str)
{
	return preg_replace('/<input([^>]+)type="(submit|button|image)"/i',
		'<input$1type="hidden"', $str);
}

function get_glossary_pattern()
{
	global $WikiName, $autoglossary;

	$config = &new Config('Glossary');
	$config->read();
	$ignorepages = $config->get('IgnoreList');
	$forceignorepages = $config->get('ForceIgnoreList');
	unset($config);
	$auto_pages = array_merge($ignorepages, $forceignorepages);

	// ここでキーワードを調べる
	$source = get_source('Glossary');
	foreach ( $source as $line ){
		if ( preg_match('/^[:|]([^|]+)\|([^|]+)\|?$/', $line, $match)) {
			$dt = trim($match[1]);
			if (mb_strlen($dt) >= $autoglossary) {
				$auto_pages[] = $dt;
			}
		}
	}

	if (count($auto_pages) == 0) {
		return array('(?!)','PukiWiki','');
	}

	$auto_pages = array_unique($auto_pages);
	sort($auto_pages, SORT_STRING);

	$auto_pages_a = array_values(preg_grep('/^[A-Z]+$/i', $auto_pages));
	$auto_pages   = array_values(array_diff($auto_pages, $auto_pages_a));

	$result   = get_autolink_pattern_sub($auto_pages,   0, count($auto_pages),   0);
	$result_a = get_autolink_pattern_sub($auto_pages_a, 0, count($auto_pages_a), 0);

	return array($result, $result_a, $forceignorepages);
}

// AutoAliasのパターンを生成する
function get_autoalias_pattern(& $pages)
{
	global $WikiName, $autoalias, $nowikiname;

	$config = &new Config('AutoLink');
	$config->read();
	$ignorepages      = $config->get('IgnoreList');
	$forceignorepages = $config->get('ForceIgnoreList');
	unset($config);
	$auto_pages = array_merge($ignorepages, $forceignorepages);

	foreach ($pages as $page) {
		if (preg_match("/^$WikiName$/", $page) ?
		    $nowikiname : mb_strlen($page) >= $autoalias)
			$auto_pages[] = $page;
	}

	if (empty($auto_pages))
		return $nowikiname ? array('(?!)','PukiWiki','') : $WikiName;

	$auto_pages = array_unique($auto_pages);
	sort($auto_pages, SORT_STRING);

	$auto_pages_a = array_values(preg_grep('/^[A-Z]+$/i', $auto_pages));
	$auto_pages   = array_values(array_diff($auto_pages,  $auto_pages_a));

	$result   = get_autolink_pattern_sub($auto_pages,   0, count($auto_pages),   0);
	$result_a = get_autolink_pattern_sub($auto_pages_a, 0, count($auto_pages_a), 0);

	return array($result, $result_a, $forceignorepages);
}

// AutoLinkのパターンを生成する
// thx for hirofummy
function get_autolink_pattern(& $pages)
{
	global $WikiName, $autolink, $nowikiname;

	$config = &new Config('AutoLink');
	$config->read();
	$ignorepages      = $config->get('IgnoreList');
	$forceignorepages = $config->get('ForceIgnoreList');
	unset($config);
	$auto_pages = array_merge($ignorepages, $forceignorepages);

	foreach ($pages as $page) {
		if (preg_match('/^' . $WikiName . '$/', $page) ?
		    $nowikiname : strlen($page) >= $autolink)
			$auto_pages[] = $page;
	}

	if (empty($auto_pages))
		return $nowikiname ? '(?!)' : $WikiName;

	$auto_pages = array_unique($auto_pages);
	sort($auto_pages, SORT_STRING);

	$auto_pages_a = array_values(preg_grep('/^[A-Z]+$/i', $auto_pages));
	$auto_pages   = array_values(array_diff($auto_pages,  $auto_pages_a));

	$result   = get_autolink_pattern_sub($auto_pages,   0, count($auto_pages),   0);
	$result_a = get_autolink_pattern_sub($auto_pages_a, 0, count($auto_pages_a), 0);

	return array($result, $result_a, $forceignorepages);
}

function get_autolink_pattern_sub(& $pages, $start, $end, $pos)
{
	if ($end == 0) return '(?!)';

	$result = '';
	$count = $i = $j = 0;
	$x = (mb_strlen($pages[$start]) <= $pos);
	if ($x) ++$start;

	for ($i = $start; $i < $end; $i = $j)
	{
		$char = mb_substr($pages[$i], $pos, 1);
		for ($j = $i; $j < $end; $j++) {
			if (mb_substr($pages[$j], $pos, 1) != $char) break;
		}
		if ($i != $start) $result .= '|';
		if ($i >= ($j - 1)) {
			$result .= str_replace(' ', '\\ ', preg_quote(mb_substr($pages[$i], $pos), '/'));
		} else {
			$result .= str_replace(' ', '\\ ', preg_quote($char, '/')) .
				get_autolink_pattern_sub($pages, $i, $j, $pos + 1);
		}
		++$count;
	}
	if ($x || $count > 1) $result = '(?:' . $result . ')';
	if ($x)               $result .= '?';

	return $result;
}

// pukiwiki.phpスクリプトのabsolute-uriを生成
function get_script_uri($init_uri = '')
{
	global $script_directory_index;
	static $script;

	if ($init_uri == '') {
		// Get
		if (isset($script)) return $script;

		// Set automatically
		$msg     = 'get_script_uri() failed: Please set $script at INI_FILE manually';

		$script  = (SERVER_PORT == 443 ? 'https://' : 'http://'); // scheme
		$script .= SERVER_NAME;	// host
		$script .= (SERVER_PORT == 80 ? '' : ':' . SERVER_PORT);  // port

		// SCRIPT_NAME が'/'で始まっていない場合(cgiなど) REQUEST_URIを使ってみる
		$path    = SCRIPT_NAME;
		if ($path{0} != '/') {
			if (! isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI']{0} != '/')
				die_message($msg);

			// REQUEST_URIをパースし、path部分だけを取り出す
			$parse_url = parse_url($script . $_SERVER['REQUEST_URI']);
			if (! isset($parse_url['path']) || $parse_url['path']{0} != '/')
				die_message($msg);

			$path = $parse_url['path'];
		}
		$script .= $path;

		if (! is_url($script, TRUE) && php_sapi_name() == 'cgi')
			die_message($msg);
		unset($msg);

	} else {
		// Set manually
		if (isset($script)) die_message('$script: Already init');
		if (! is_url($init_uri, TRUE)) die_message('$script: Invalid URI');
		$script = $init_uri;
	}

	// Cut filename or not
	if (isset($script_directory_index)) {
		if (! file_exists($script_directory_index))
			die_message('Directory index file not found: ' .
				htmlspecialchars($script_directory_index));
		$matches = array();
		if (preg_match('#^(.+/)' . preg_quote($script_directory_index, '#') . '$#',
			$script, $matches)) $script = $matches[1];
	}

	return $script;
}

/*
変数内のnull(\0)バイトを削除する
PHPはfopen("hoge.php\0.txt")で"hoge.php"を開いてしまうなどの問題あり

http://ns1.php.gr.jp/pipermail/php-users/2003-January/012742.html
[PHP-users 12736] null byte attack

2003-05-16: magic quotes gpcの復元処理を統合
2003-05-21: 連想配列のキーはbinary safe
*/
function input_filter($param)
{
	static $magic_quotes_gpc = NULL;
	if ($magic_quotes_gpc === NULL)
	    $magic_quotes_gpc = get_magic_quotes_gpc();

	if (is_array($param)) {
		return array_map('input_filter', $param);
	} else {
		$result = str_replace("\0", '', $param);
		if ($magic_quotes_gpc) $result = stripslashes($result);
		return $result;
	}
}

// Compat for 3rd party plugins. Remove this later
function sanitize($param) {
	return input_filter($param);
}

// CSV形式の文字列を配列に
function csv_explode($separator, $string)
{
	$retval = $matches = array();

	$_separator = preg_quote($separator, '/');
	if (! preg_match_all('/("[^"]*(?:""[^"]*)*"|[^' . $_separator . ']*)' .
	    $_separator . '/', $string . $separator, $matches))
		return array();

	foreach ($matches[1] as $str) {
		$len = strlen($str);
		if ($len > 1 && $str{0} == '"' && $str{$len - 1} == '"')
			$str = str_replace('""', '"', substr($str, 1, -1));
		$retval[] = $str;
	}
	return $retval;
}

// Implode an array with CSV data format (escape double quotes)
function csv_implode($glue, $pieces)
{
	$_glue = ($glue != '') ? '\\' . $glue{0} : '';
	$arr = array();
	foreach ($pieces as $str) {
		if (ereg('[' . $_glue . '"' . "\n\r" . ']', $str))
			$str = '"' . str_replace('"', '""', $str) . '"';
		$arr[] = $str;
	}
	return join($glue, $arr);
}

function pkwk_login($pass = '')
{
	global $adminpass;

	if (! PKWK_READONLY && $pass != '' && md5($pass) == $adminpass) {
		return TRUE;
	} else {
		sleep(2);	// Blocking brute force attack
		return FALSE;
	}
}


//// Compat ////

// is_a --  Returns TRUE if the object is of this class or has this class as one of its parents
// (PHP 4 >= 4.2.0)
if (! function_exists('is_a')) {

	function is_a($class, $match)
	{
		if (empty($class)) return FALSE; 

		$class = is_object($class) ? get_class($class) : $class;
		if (strtolower($class) == strtolower($match)) {
			return TRUE;
		} else {
			return is_a(get_parent_class($class), $match);	// Recurse
		}
	}
}

// array_fill -- Fill an array with values
// (PHP 4 >= 4.2.0)
if (! function_exists('array_fill')) {

	function array_fill($start_index, $num, $value)
	{
		$ret = array();
		while ($num-- > 0) $ret[$start_index++] = $value;
		return $ret;
	}
}

// md5_file -- Calculates the md5 hash of a given filename
// (PHP 4 >= 4.2.0)
if (! function_exists('md5_file')) {

	function md5_file($filename)
	{
		if (! file_exists($filename)) return FALSE;

		$fd = fopen($filename, 'rb');
		if ($fd === FALSE ) return FALSE;
		$data = fread($fd, filesize($filename));
		fclose($fd);
		return md5($data);
	}
}
?>
