<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: pukiwiki.ini.php,v 1.99.11 2004/12/16 11:29:47 miko Exp $
//
// PukiWiki setting file

/////////////////////////////////////////////////
// Init 

if (! defined('PKWK_SAFE_MODE')) 
	define('PKWK_SAFE_MODE', FALSE);        // FALSE or TRUE 

if (! defined('PKWK_OPTIMISE')) 
	define('PKWK_OPTIMISE', FALSE); // FALSE or TRUE 

///////////////////////////////////////////////// 
// ������� (ʸ�����󥳡��ɡ�����)

// Internal Language ('en' or 'ja')
define('LANG', 'ja');	// For internal message encoding

// UI Language (Language for buttons, menus,  etc)
if (! defined('UI_LANG'))
	define('UI_LANG', 'ja');	// 'en' or 'ja'

/////////////////////////////////////////////////
// �ǥ��쥯�ȥ���� �Ǹ�� / ��ɬ�� °���� 777

// �ǡ�����Ǽ�ǥ��쥯�ȥ�
define('DATA_DIR',      DATA_HOME . 'wiki/');	// �ǿ��Υǡ���
define('DIFF_DIR',      DATA_HOME . 'diff/');	// ��ʬ�ե�����
define('BACKUP_DIR',    DATA_HOME . 'backup/');	// �Хå����å�
define('CACHE_DIR',     DATA_HOME . 'cache/');	// ����å���
define('UPLOAD_DIR',    DATA_HOME . 'attach/');	// ź�եե�����
define('COUNTER_DIR',   DATA_HOME . 'counter/');	// ������
define('TRACKBACK_DIR', DATA_HOME . 'trackback/');	// TrackBack
define('PLUGIN_DIR',    DATA_HOME . 'plugin/'); // �ץ饰����ե�����

/////////////////////////////////////////////////
// �ǥ��쥯�ȥ���� �Ǹ�� / ��ɬ��
//
//  PukiWiki���Τ�Web�֥饦�����饢�������Ǥ��ʤ�
//  �������֤���Ȥ��ϡ��ʲ��Υǥ��쥯�ȥ�ˤ���
//  �ե�����ΰ����� Web�֥饦�����饢�������Ǥ���
//  �������֤���ɬ�פ�����ޤ���
//  (̵���Ȥ�ư��Ϥ��ޤ���������̣���ʤ��ʤ�Ǥ��礦)

// ������/�������륷���ȥե������Ǽ�ǥ��쥯�ȥ�
define('SKIN_DIR', 'skin/');
//  ���Υǥ��쥯�ȥ�ʲ��Υ�����ե����� (*.php) ��
//  PukiWiki����¦(DATA_HOME/SKIN_DIR) ��ɬ�פǤ�����
//  CSS�ե�����(*.css) �����JavaScript�ե�����( *.js)
//  ��Web�֥饦�����鸫������(./SKIN_DIR)������
//  ���Ʋ�����

// �����ե������Ǽ�ǥ��쥯�ȥ�
define('IMAGE_DIR', 'image/');
//  ���Υǥ��쥯�ȥ�ʲ������ƤΥե������
//  Web�֥饦�����鸫������(./IMAGE_DIR)������
//  ���Ʋ�����

/////////////////////////////////////////////////
// ������ʤɤθ���URI(for Fancy URL)
define('ROOT_URI', '');
define('SKIN_URI', ROOT_URI . SKIN_DIR);
define('IMAGE_URI', ROOT_URI . IMAGE_DIR);

/////////////////////////////////////////////////
// �����������
define('ZONE','JST');
define('ZONETIME',9 * 3600); // JST = GMT+9

/////////////////////////////////////////////////
// �ۡ���ڡ����Υ����ȥ�(�������Ƥ�������)
// * RSS �˽��Ϥ�������ͥ�̾���ͤ�
$page_title = 'PukiWiki Plus!';

// index.php �ʤɤ��ѹ��������Υ�����ץ�̾������
// �Ȥ������ꤷ�ʤ��Ƥ�����ʤ�
//$script = 'http://example.com/pukiwiki/';

// $script ����ե�����̾�򥫥åȤ��� (URL��û������)
// Web�����С�¦������ǡ��ǥ��쥯�ȥ����ꤷ���Ȥ���
// ɽ������ǥե���ȤΥե�����̾�θ���ˤ����ǻ��ꤹ��
// �ե�����̾���ޤޤ�Ƥ���ɬ�פ�����ޤ�
//$script_directory_index = 'index.php';

// �Խ��Ԥ�̾��(�������Ƥ�������)
$modifier = 'anonymous';

// �Խ��ԤΥۡ���ڡ���(�������Ƥ�������)
$modifierlink = 'http://pukiwiki.example.com/';

// �ǥե���ȤΥڡ���̾
$defaultpage  = 'FrontPage';	// �ȥåץڡ��� (�ڡ�������ꤷ�ʤ��Ȥ�)
$whatsnew     = 'RecentChanges';	// ��������
$whatsdeleted = 'RecentDeleted';	// �������
$interwiki    = 'InterWikiName';	// InterWikiName �ΰ�����񤯥ڡ���
$menubar      = 'MenuBar';	// ��˥塼�Ȥ���ɽ�����������Ƥ�񤯥ڡ���
$sidebar      = 'SideBar';	// ��˥塼�Ȥ���ɽ�����������Ƥ�񤯥ڡ���
$headarea     = ':Header';
$footarea     = ':Footer';

/////////////////////////////////////////////////
// XHTML version
// skin���DTD������ڤ��ؤ���Τ˻��ѡ�paint.inc.php�к�
$html_transitional = FALSE; // FALSE:XHTML 1.1, TRUE:XHTML 1.0 Transitional

/////////////////////////////////////////////////
// Allow using JavaScript
//   JavaScript����Ѥ���ץ饰����ʤɤ�
//   ��ǽ���������ޤ�
define('PKWK_ALLOW_JAVASCRIPT', 1);	// 0 or 1

/////////////////////////////////////////////////
// TrackBack��ǽ����Ѥ���
$trackback = 1;

// Show trackbacks with an another window
$trackback_javascript = 0;

/////////////////////////////////////////////////
// Referer��ǽ����Ѥ���
$referer = 1;

/////////////////////////////////////////////////
// WikiName�� *̵����* ����
$nowikiname = 1;

/////////////////////////////////////////////////
// AutoLink��ͭ���ˤ�����ϡ�AutoLink�оݤȤʤ�
// �ڡ���̾�κ�û�Х��ȿ������(5�ʾ��侩)
// AutoLink��̵���ˤ������0
$autolink = 5;

/////////////////////////////////////////////////
// AutoGlossary��ͭ���ˤ���
// �ڡ���̾�κ�û"ʸ��"�������(2�ʾ��侩)
// AutoGlossary��̵���ˤ������0
$autoglossary = 2;

/////////////////////////////////////////////////
// ��뵡ǽ��ͭ���ˤ���
$function_freeze = 1;

/////////////////////////////////////////////////
// �����ԥѥ����

// �ʲ��� md5('pass') �ν��Ϸ�̤Ǥ�
$adminpass = '1a1dc91c907325c69271ddf0c944bc72';

// = ���� =
//
// �ѥ���ɤ����ꤹ����ˡ�Ȥ��ơ�md5()�ؿ���Ȥ���ˡ�ȡ�
// md5()�ؿ��η�̤����ӻ��Ф��ƻȤ���ˡ������ޤ���
// ���ʤ�������ԥ塼�������˽�ʬ����Ƥ���ΤǤ���С�
// ��Ԥ򤪴��ᤷ�ޤ���
//
// �㤨�Хѥ���ɤ��pass�פȤ�������硢�ʲ����ͤ˵��Ҥ���
// ���Ȥ��Ǥ��ޤ���
//
// $adminpass = md5('pass');	// md5() �ؿ���Ȥ���ˡ
//
// ��������������ˡ�Ǥϡ����Υե�������������뤳�Ȥ��Ǥ���
// (�Ǥ���) ï���ˡ��ѥ���ɤ��Τ�Τ��Τ���⤤��������
// ����ޤ������δ������򲼤��뤿��ˡ�md5()�ؿ��η�̤�����
// ���Ҥ��뤳�Ȥ��Ǥ��ޤ���
//
// md5()�ؿ��η��(MD5�ϥå���)��0����9�ο����ȡ�A����F�ޤ�
// �αѻ�����ʤ�32ʸ����ʸ����ǡ����ξ�������Ǥϸ���ʸ�����
// ��¬���뤳�ȤϺ���Ǥ���
//
// MD5�ϥå���ϡ�Linux��cygwin�Ǥ����
//
//    $ echo -n 'pass' | md5sum
//
// ���ͤˤ��Ʒ׻�����������Ǥ��ޤ���('-n' ���ץ�����˺�줺��!)
// FreeBSD�ʤɤǤ� md5sum ������� md5 ���ޥ�ɤ�ȤäƤ���������
//
// ������Ǥ��ޤ��󤬡�PukiWiki��md5�ץ饰����Ǥ⻻�Ф���ǽ�Ǥ���
//
// http://<���֤������>/pukiwiki.php?plugin=md5
//
// ����URL�˥�����������ȡ�MD5�ϥå���򻻽Ф��뤿��Υե����ब
// ɽ�����졢�����˲��餫��ʸ��������Ϥ���ȥϥå��夬ɽ�������
// �������������ε�ǽ��Ȥäƥѥ���ɤ����Ȥ������Ȥϡ��ѥ�
// ���(�θ���)��ϥå����ͥåȥ�����ή���Ƥ��ޤ��Ȥ���
// ���Ȥˤʤ�ޤ����顢���դΤ���Ԥˤ����İ������Ψ���᤿�ꡢ
// ���˹���Τ���Υҥ�Ȥ���¿��Ϳ�����ǽ��������ޤ���
// �ѥ���ɤȥϥå�����Ȥ߹�碌�������줿�ԤˤȤäƤϡ�
// "$adminpass �˥ϥå��������" �Ȥ����б����̣������ޤ���

/////////////////////////////////////////////////
// ChaSen, KAKASI �ˤ�롢�ڡ���̾���ɤߤμ��� (0:̵��,1:ͭ��)
$pagereading_enable = 0;

// ChaSen('chasen') or KAKASI('kakasi') or None('none')
$pagereading_kanji2kana_converter = 'none';

// ChaSen/KAKASI �Ȥμ����Ϥ��˻Ȥ����������� (UNIX�Ϥ� EUC��Win�Ϥ� SJIS ������)
$pagereading_kanji2kana_encoding = 'EUC';
//$pagereading_kanji2kana_encoding = 'SJIS';

// ChaSen/KAKASI �μ¹ԥե����� (�Ƽ��δĶ��˹�碌������)
$pagereading_chasen_path = '/usr/local/bin/chasen';
//$pagereading_chasen_path = 'c:\progra~1\chasen21\chasen.exe';

$pagereading_kakasi_path = '/usr/local/bin/kakasi';
//$pagereading_kakasi_path = 'c:\kakasi\bin\kakasi.exe';

// �ڡ���̾�ɤߤ��Ǽ�����ڡ�����̾��
$pagereading_config_page = ':config/PageReading';

// converter ='none' �ξ����ɤ߲�̾����
$pagereading_config_dict = ':config/PageReading/dict';

/////////////////////////////////////////////////
// �桼�����
$auth_users = array(
	'foo'	=> md5('foo_passwd'),
	'bar'	=> md5('bar_passwd'),
	'hoge'	=> md5('hoge_passwd'),
);

/////////////////////////////////////////////////
// ǧ����������
// 'pagename' : �ڡ���̾
// 'contents' : �ڡ�������
$auth_method_type = 'contents';

/////////////////////////////////////////////////
// ����ǧ�ڥե饰 (0:���� 1:ɬ��)
$read_auth = 0;

// ����ǧ���оݥѥ��������
$read_auth_pages = array(
	'#�Ҥ������ۤ�#'	=> 'hoge',
	'#(�ͥ��Х�|�ͤ��Ф�)#'	=> 'foo,bar,hoge',
);

/////////////////////////////////////////////////
// �Խ�ǧ�ڥե饰 (0:���� 1:ɬ��)
$edit_auth = 0;

// �Խ�ǧ���оݥѥ��������
$edit_auth_pages = array(
	'#Bar�θ�������#'	=> 'bar',
	'#�Ҥ������ۤ�#'	=> 'hoge',
	'#(�ͥ��Х�|�ͤ��Ф�)#'	=> 'foo',
);

/////////////////////////////////////////////////
// ����ǧ�ڥե饰
// 0: ���������Ĥ���Ƥ��ʤ��ڡ������Ƥ⸡���оݤȤ���
// 1: �������Υ�������桼���˵��Ĥ��줿�ڡ����Τ߸����оݤȤ���
$search_auth = 0;

/////////////////////////////////////////////////
// �����ޤ�������ͭ���ˤ���
$search_fuzzy = 1;

/////////////////////////////////////////////////
// $whatsnew: ���������ɽ������Ȥ��κ�����
$maxshow = 60;

// $whatsdeleted: �������κ�����(0�ǵ�Ͽ���ʤ�)
$maxshow_deleted = 60;

/////////////////////////////////////////////////
// �Խ����뤳�ȤΤǤ��ʤ��ڡ�����̾�� , �Ƕ��ڤ�
$cantedit = array( $whatsnew, $whatsdeleted );

/////////////////////////////////////////////////
// Last-Modified �إå�����Ϥ���
$lastmod = 0;

/////////////////////////////////////////////////
// ���եե����ޥå�
$date_format = 'Y-m-d';

// ����ե����ޥå�
$time_format = 'H:i:s';

/////////////////////////////////////////////////
// RSS �˽��Ϥ���ڡ�����
$rss_max = 15;

/////////////////////////////////////////////////
// �Хå����åפ�Ԥ�
$do_backup = 1;

// �ڡ������������ݤ˥Хå����åפ⤹�٤ƺ������
$del_backup = 0;

// �Хå����å״ֳ֤������
$cycle  = 1;	// ľ���ν������鲿���ַвᤷ�Ƥ�����Хå����åפ��뤫 (0�ǹ�����)
$maxage = 360;	// �����

// NOTE: $cycle x $maxage / 24 = �ǡ����򼺤�����˺����ɬ�פ�����
//          1   x   360   / 24 = 15

// �Хå����åפ��������ڤ�ʸ����
define('PKWK_SPLITTER', '>>>>>>>>>>');

/////////////////////////////////////////////////
// �ڡ����ι������˥Хå������ɤǼ¹Ԥ��륳�ޥ��(mknmz�ʤ�)
$update_exec = '';
//$update_exec = '/usr/bin/mknmz --media-type=text/pukiwiki -O /var/lib/namazu/index/ -L ja -c -K /var/www/wiki/';

/////////////////////////////////////////////////
// HTTP�ꥯ�����Ȥ˥ץ����������Ф���Ѥ���
$use_proxy = 0;

$proxy_host = 'proxy.example.com'; // proxy������̾
$proxy_port = 8080; // �ݡ����ֹ�

// Basicǧ�ڤ�Ԥ�
$need_proxy_auth = 0;
$proxy_auth_user = 'username';	// �桼����̾
$proxy_auth_pass = 'password';	// �ѥ����

// �ץ����������Ф���Ѥ��ʤ��ۥ��ȤΥꥹ��
$no_proxy = array(
	'localhost',	// localhost
	'127.0.0.0/8',	// loopback
//	'10.0.0.0/8'	// private class A
//	'172.16.0.0/12'	// private class B
//	'192.168.0.0/16'	// private class C
//	'no-proxy.com',
);

////////////////////////////////////////////////
// �᡼������

$notify = 0;	// (1:�ڡ����ι������˥᡼�����������)
$notify_diff_only = 0;	// (1:��ʬ��������������)

// SMTP������ (Windows �Τ�, �̾�� php.ini �ǻ���)
$smtp_server = 'localhost';

$notify_to   = 'to@example.com';	// To:�ʰ����
$notify_from = 'from@example.com';	// From:��������

// Subject:�ʷ�̾�� $page�˥ڡ���̾������ޤ�
$notify_subject = '[pukiwiki] $page';

// �᡼��إå�
$notify_header = "From: $notify_from\r\n" .
	'X-Mailer: PukiWiki/' .  S_VERSION . ' PHP/' . phpversion();

/////////////////////////////////////////////////
// �᡼������: POP / APOP Before SMTP

// �᡼����������POP�ޤ���APOP�ˤ��ǧ�ڤ�Ԥ�
$smtp_auth = 0;

$pop_server = 'localhost';	// POP������
$pop_port   = 110;	// �ݡ����ֹ�
$pop_userid = '';	// POP�桼��̾
$pop_passwd = '';	// POP�ѥ����

// ǧ�ڤ� APOP �����Ѥ��뤫�ɤ��� (��������¦���б���ɬ��)
//   ̤���� = ��ư (��ǽ�Ǥ����APOP����Ѥ���)
//   1 = APOP����  (ɬ��APOP����Ѥ���)
//   0 = POP����   (ɬ��POP����Ѥ���)
// $pop_auth_use_apop = 1;

// �ʲ��ǻ��ꤷ����⡼�ȥۥ��Ȥ��鹹�����줿���ϥ᡼����������ʤ�
$notify_exclude = array(
//    '192.168.0.',
);

/////////////////////////////////////////////////
// ���������������˴ޤ�ʤ��ڡ���̾(����ɽ����)
$non_list = '^\:';

// $non_list��ʸ���󸡺����оݥڡ����Ȥ��뤫
// 0�ˤ���ȡ��嵭�ڡ���̾��ñ�측��������������ޤ���
$search_non_list = 1;

/////////////////////////////////////////////////
// �ڡ���̾�˽��äƼ�ư�ǡ������Ȥ���ڡ������ɤ߹���
$auto_template_func = 1;
$auto_template_rules = array(
	'((.+)\/([^\/]+))' => '\2/template'
);

/////////////////////////////////////////////////
// ���Ф��Ԥ˸�ͭ�Υ��󥫡���ư��������
$fixed_heading_anchor = 1;

/////////////////////////////////////////////////
// ���Ф����Ȥ��Խ����ǽ�ˤ��� 
//
// ���Ф��Ԥθ�ͭ�Υ��󥫼�ư��������Ƥ���Ȥ�
// �Τ�ͭ���Ǥ�
$fixed_heading_edited = 0;

/////////////////////////////////////////////////
// <pre>�ι�Ƭ���ڡ�����ҤȤļ�����
$preformat_ltrim = 1;

/////////////////////////////////////////////////
// ���Ԥ�ȿ�Ǥ���(���Ԥ�<br />���ִ�����)
$line_break = 0;

/////////////////////////////////////////////////
// �ڡ�����Ǥ�դΥե졼��˳������˻Ȥ�����
$use_open_uri_in_new_window  = 1;

// Ʊ�쥵���С��Ȥ��Ƥߤʤ��ۥ��Ȥ�URI
$open_uri_in_new_window_servername = array(
	"http://localhost/",
	"http://localhost.localdomain/",
);
// URI�μ���ˤ�äƳ���ư������ꡣ
// "_blank"�������ɽ����false����ꤹ���̵��
$open_uri_in_new_window_opis  = "_blank";	// pukiwiki�γ���Ʊ�쥵���С���
$open_uri_in_new_window_opisi = false;		// pukiwiki�γ���Ʊ�쥵���С���(InterWikiLink)
$open_uri_in_new_window_opos  = "_blank";	// pukiwiki�γ��ǳ��������С�
$open_uri_in_new_window_oposi = "_blank";	// pukiwiki�γ��ǳ��������С�(InterWikiLink)
// (���ա������Ƴ�ĥ���䤹���褦�ˤ��Ƥ��ޤ�����"_blank"�ʳ��ϻ��ꤷ�ʤ��Ǥ�������)

/////////////////////////////////////////////////
// �桼����������������б�����
//
// ��å����饤����Ȥ�����Ȥ��������Ȥ��ۤ���
// ����ˡ��������äʤɤ˰տ�Ū�����б��Ȥ�������硢
// �Ǹ�Υǥե��������ʳ��ιԤ����ƺ�����뤤��
// �����ȥ����Ȥ��Ʋ�������
//
// �ǥ�����䥹���������Ǥ�keitai�ץ��ե������
// ���줷�������ϡ��ǥե��������ʳ��ιԤ����ƺ��
// ���뤤�ϥ����ȥ����Ȥ�����ˡ��ǥե���������
// 'profile'=>'keitai' �Ƚ������Ʋ�������

$agents = array(
// pattern: A regular-expression that matches device(browser)'s name and version
// profile: A group of browsers

    // Embedded browsers (Rich-clients for PukiWiki)

	// Windows CE (Microsoft(R) Internet Explorer 5.5 for Windows(R) CE)
	// Sample: "Mozilla/4.0 (compatible; MSIE 5.5; Windows CE; sigmarion3)" (sigmarion, Hand-held PC)
	array('pattern'=>'#\b(?:MSIE [5-9]).*\b(Windows CE)\b#', 'profile'=>'default'),

	// ACCESS "NetFront" / "Compact NetFront" and thier OEM, expects to be "Mozilla/4.0"
	// Sample: "Mozilla/4.0 (PS2; PlayStation BB Navigator 1.0) NetFront/3.0" (PlayStation BB Navigator, for SONY PlayStation 2)
	// Sample: "Mozilla/4.0 (PDA; PalmOS/sony/model crdb/Revision:1.1.19) NetFront/3.0" (SONY Clie series)
	// Sample: "Mozilla/4.0 (PDA; SL-A300/1.0,Embedix/Qtopia/1.1.0) NetFront/3.0" (SHARP Zaurus)
	array('pattern'=>'#^(?:Mozilla/4).*\b(NetFront)/([0-9\.]+)#',	'profile'=>'default'),

    // Embedded browsers (Non-rich)

	// Windows CE (the others)
	// Sample: "Mozilla/2.0 (compatible; MSIE 3.02; Windows CE; 240x320 )" (GFORT, NTT DoCoMo)
	array('pattern'=>'#\b(Windows CE)\b#', 'profile'=>'keitai'),

	// ACCESS "NetFront" / "Compact NetFront" and thier OEM
	// Sample: "Mozilla/3.0 (AveFront/2.6)" ("SUNTAC OnlineStation", USB-Modem for PlayStation 2)
	// Sample: "Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0" (DDI Pocket: AirH" Phone by JRC)
	array('pattern'=>'#\b(NetFront)/([0-9\.]+)#',	'profile'=>'keitai'),
	array('pattern'=>'#\b(CNF)/([0-9\.]+)#',	'profile'=>'keitai'),
	array('pattern'=>'#\b(AveFront)/([0-9\.]+)#',	'profile'=>'keitai'),
	array('pattern'=>'#\b(AVE-Front)/([0-9\.]+)#',	'profile'=>'keitai'), // The same?

	// NTT-DoCoMo, i-mode (embeded Compact NetFront) and FOMA (embedded NetFront) phones
	// Sample: "DoCoMo/1.0/F501i", "DoCoMo/1.0/N504i/c10/TB/serXXXX" // c�ʹߤϲ���
	// Sample: "DoCoMo/2.0 MST_v_SH2101V(c100;TB;W22H12;serXXXX;iccxxxx)" // ()����ϲ���
	array('pattern'=>'#^(DoCoMo)/([0-9\.]+)#',	'profile'=>'keitai'),

	// Vodafone's embedded browser
	// Sample: "J-PHONE/2.0/J-T03"	// 2.0��"�֥饦����"�С������
	// Sample: "J-PHONE/4.0/J-SH51/SNxxxx SH/0001a Profile/MIDP-1.0 Configuration/CLDC-1.0 Ext-Profile/JSCL-1.1.0"
	array('pattern'=>'#^(J-PHONE)/([0-9\.]+)#',	'profile'=>'keitai'),

	// Openwave(R) Mobile Browser (EZweb, WAP phone, etc)
	// Sample: "OPWV-SDK/62K UP.Browser/6.2.0.5.136 (GUI) MMP/2.0"
	array('pattern'=>'#\b(UP\.Browser)/([0-9\.]+)#',	'profile'=>'keitai'),

	// Opera, dressing up as other embedded browsers
	// Sample: "Mozilla/3.0(DDIPOCKET;KYOCERA/AH-K3001V/1.4.1.67.000000/0.1/C100) Opera 7.0" (Like CNF at 'keitai'-mode)
	array('pattern'=>'#\bDDIPOCKET\b.+\b(Opera) ([0-9\.]+)\b#',	'profile'=>'keitai'),

	// Planetweb http://www.planetweb.com/
	// Sample: "Mozilla/3.0 (Planetweb/v1.07 Build 141; SPS JP)" ("EGBROWSER", Web browser for PlayStation 2)
	array('pattern'=>'#\b(Planet[Ww]eb)/[a-z]?([0-9\.]+)#',	'profile'=>'keitai'),

	// DreamPassport, Web browser for SEGA DreamCast
	// Sample: "Mozilla/3.0 (DreamPassport/3.0)"
	array('pattern'=>'#\b(DreamPassport)/([0-9\.]+)#',	'profile'=>'keitai'),

	// Palm "Web Pro" http://www.palmone.com/us/support/accessories/webpro/
	// Sample: "Mozilla/4.76 [en] (PalmOS; U; WebPro)"
	array('pattern'=>'#\b(WebPro)\b#',	'profile'=>'keitai'),

	// ilinx "Palmscape" / "Xiino" http://www.ilinx.co.jp/
	// Sample: "Xiino/2.1SJ [ja] (v. 4.1; 153x130; c16/d)"
	array('pattern'=>'#^(Palmscape)/([0-9\.]+)#',	'profile'=>'keitai'),
	array('pattern'=>'#^(Xiino)/([0-9\.]+)#',	'profile'=>'keitai'),

	// SHARP PDA Browser (SHARP Zaurus)
	// Sample: "sharp pda browser/6.1[ja](MI-E1/1.0) "
	array('pattern'=>'#^(sharp [a-z]+ browser)/([0-9\.]+)#',	'profile'=>'keitai'),

	// WebTV
	array('pattern'=>'#^(WebTV)/([0-9\.]+)#',	'profile'=>'keitai'),

    // Desktop-PC browsers

	// Opera (for desktop PC, not embedded) -- See BugTrack/743 for detail
	// NOTE: Keep this pattern above MSIE and Mozilla
	// Sample: "Opera/7.0 (OS; U)" (not disguise)
	// Sample: "Mozilla/4.0 (compatible; MSIE 5.0; OS) Opera 6.0" (disguise)
	array('pattern'=>'#\b(Opera)[/ ]([0-9\.]+)\b#',	'profile'=>'default'),

	// MSIE: Microsoft Internet Explorer (or something disguised as MSIE)
	// Sample: "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"
	array('pattern'=>'#\b(MSIE) ([0-9\.]+)\b#',	'profile'=>'default'),

	// Mozilla Firefox
	// NOTE: Keep this pattern above Mozilla
	// Sample: "Mozilla/5.0 (Windows; U; Windows NT 5.0; ja-JP; rv:1.7) Gecko/20040803 Firefox/0.9.3"
	array('pattern'=>'#\b(Firefox)/([0-9\.]+)\b#',	'profile'=>'default'),

    	// Loose default: Including something Mozilla
	array('pattern'=>'#^([a-zA-z0-9 ]+)/([0-9\.]+)\b#',	'profile'=>'default'),

	array('pattern'=>'#^#',	'profile'=>'default'),	// Sentinel
);
?>