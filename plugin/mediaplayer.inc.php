<?php
/////////////////////////////////////////////////
// PukiWiki Plus! - Yet another WikiWikiWeb clone.
//
// $Id: mediaplayer.inc.php,v 0.3 2004/09/15 15:41:57 miko Exp $
//

// Windows Media �����ݡ��ȤǤ���ץ��ȥ��뤫��
function is_wmv($str)
{
	return preg_match('/^(https?|mms)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]*)$/', $str);
}

// ư���֥��å�ɽ��
function plugin_mediaplayer_convert()
{
	global $head_tags;
	global $script, $vars;

	$usage = "#mediaplayer(): Usage: (URI-to-image,URI-to-video)<br />\n";
	$args = func_get_args();

	// ź�եե�����Τ���ڡ���: default�ϸ��ߤΥڡ���̾
	$page = isset($vars['page']) ? $vars['page'] : '';

	// ź�եե�����Υե�����̾
	$name = '';

	// ź�եե�����ޤǤΥѥ������(�ºݤ�)�ե�����̾
	$file = '';

	// ������: "[�ڡ���̾�����/]ź�եե�����̾"�����뤤��"URL"�����
	$name = $args[0];
	$is_url = is_url($name);

	if(! $is_url) {
		// ź�եե�����
		if (! is_dir(UPLOAD_DIR)) {
			return "Preview File not Found.";
		}

		$matches = array();
		if (preg_match('#^(.+)/([^/]+)$#', $name, $matches)) {
			// �ե�����̾�˥ڡ���̾(�ڡ������ȥѥ�)����������Ƥ��뤫
			if ($matches[1] == '.' || $matches[1] == '..') {
				$matches[1] .= '/';
			}
			$name = $matches[2];
			$page = get_fullname(strip_bracket($matches[1]), $page);
			$file = UPLOAD_DIR . encode($page) . '_' . encode($name);
			$is_file = is_file($file);

		} else {
			// �ǥե���ȥڡ���̾���Ф���
			$file = UPLOAD_DIR . encode($page) . '_' . encode($name);
			$is_file = is_file($file);
		}
		if (! $is_file) {
			return "Preview File not found.";
		}
	}

	// �����Υ��ɥ쥹�����å�
	$url = isset($args[0]) ? $args[0] : '';
	if (is_url($url)) {
		if(! preg_match('/\.(jpe?g|gif|png)$/i', $url)) {
			return $usage . $url;
		}
	} else {
		if(! preg_match('/\.(jpe?g|gif|png)$/i', $name)) {
			return $usage . $name;
		}
		$url = $script . '?plugin=ref' . '&amp;page=' . rawurlencode($page) .
			'&amp;src=' . rawurlencode($name);
	}

	// ư��Υ��ɥ쥹�����å�
	$wmv = isset($args[1]) ? $args[1] : '';
	if (! is_wmv($wmv) || ! preg_match('/\.(wmv|asf)$/i', $wmv))
		return $usage . $wmv;

	// �ɲ� JavaScript
	$head_tags[] = '<script type="text/javascript" src="'.SKIN_DIR.'mediaplayer.js"></script>';

	// ��ǥ����ץ쥤�䡼���ɲ�
	return <<<EOD
<div align="center">
	<div class="playercontainer">
		<img src="$url" class="videosplash" width="320" height="240" alt="" title="" style="left:0px;z-index:10;width:320;height:240;" />
		<div class="player">
			<object id="mplayer" width="320" height="240" CLASSID="CLSID:6BF52A52-394A-11D3-B153-00C04F79FAA6">
				<param name="Url"          value="$wmv">
				<param name="autoStart"    value="false">
				<param name="uiMode"       value="none">
				<param name="enabled"      value="true">
			</object>
			<table class="controlstable" cellpadding="0" cellspacing="0">
				<tr>
					<td nowrap><img alt="play video" class="mediaplayerbutton" onmouseover="hover(this);" onmouseout="out(this);" onclick="play('player',this);" src="image/player/play_n.gif" /></td>
					<td nowrap width="46"><img alt="stop video" class="mediaplayerbutton" onmouseover="hover(this);" onmouseout="out(this);" onclick="stop('player',this);" src="image/player/stop_d.gif" /></td>
					<td nowrap width="172" class="slider" onclick="slide('player',this);"><img align="middle" alt="indicator" height="3" class="indicator" src="image/player/indicator.gif" /><img alt="handle" onclick="handle('player', this);" class="indicatorhandle" src="image/player/handle.gif" /><img onclick="handle('player',this);" alt="amount downloaded" align="middle" class="downloadindicator" src="image/player/download_indicator.gif" /></td>
					<td nowrap width="46" align="right"><img class="mediaplayerbutton" alt="toggle sound" onmouseover="hover(this);" onmouseout="out(this);" onclick="mute('player',this);" src="image/player/sound_on_n.gif" /></td>
					<td nowrap><img alt="launch in external player" class="mediaplayerbutton" onmouseover="hover(this);" onmouseout="out(this);" onclick="openPlayer('player',this);" src="image/player/FullScreen_h.gif" /></td>
				</tr>
			</table>
		</div>
		<table class="controlstablenoscript" cellpadding="0" cellspacing="0">
			<tr>
				<td width="320" nowrap><a href="$wmv" title="Launch the streaming media file"><img alt="Launch the streaming media file" border="0" class="mediaplayerbutton" onmouseover="hover(this);" onmouseout="out(this);" onclick="play('player',this);" src="image/player/FullScreen_h.gif" /></a></td>
			</tr>
		</table>
	</div>
</div>
EOD;
}
?>