<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004,2013 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: links.php
-----------------------------------------------------
 Назначение: управление перекрестными ссылками
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
  die("Hacking attempt!");
}

if( $member_id['user_group'] != 1  ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

if (!$config['allow_links']) {

$lang['opt_linkshelp'] .= "<br /><br /><font color=\"red\">{$lang['module_disabled']}</font> ";

}

$start_from = intval( $_REQUEST['start_from'] );
$news_per_page = 50;

if( $start_from < 0 ) $start_from = 0;

if ($_GET['action'] == "delete") {
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}

	$id = intval ( $_GET['id'] );

	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '95', '')" );
	$db->query( "DELETE FROM " . PREFIX . "_links WHERE id='{$id}'" );

	@unlink( ENGINE_DIR . '/cache/system/links.php' );
	clear_cache();
	header( "Location: ?mod=links&start_from={$start_from}" ); die();

}

if ($_POST['action'] == "mass_delete") {

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}

	if( !$_POST['selected_tags'] ) {
		msg( "error", $lang['mass_error'], $lang['mass_links_err'], "?mod=links&start_from={$start_from}" );
	}

	foreach ( $_POST['selected_tags'] as $id ) {
		$id = intval($id);
		$db->query( "DELETE FROM " . PREFIX . "_links WHERE id='{$id}'" );
	}

	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '95', '')" );

	@unlink( ENGINE_DIR . '/cache/system/links.php' );
	clear_cache();
	header( "Location: ?mod=links&start_from={$start_from}" ); die();

}
if ($_GET['action'] == "add") {

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}

	$tag = convert_unicode( urldecode ( $_GET['tag'] ), $config['charset']  );
	$url = convert_unicode( urldecode ( $_GET['url'] ), $config['charset']  );
	$onlyone = intval ( $_GET['onlyone'] );

	$tag = @$db->safesql( htmlspecialchars( strip_tags( stripslashes( trim( $tag ) ) ), ENT_COMPAT, $config['charset'] ) );

	$url = @$db->safesql( htmlspecialchars( strip_tags( stripslashes( trim( $url ) ) ), ENT_QUOTES, $config['charset'] ) );
	$url = str_ireplace( "document.cookie", "d&#111;cument.cookie", $url );
	$url = preg_replace( "/javascript:/i", "j&#097;vascript:", $url );
	$url = preg_replace( "/data:/i", "d&#097;ta:", $url );

	if (!$tag) msg( "error", $lang['index_denied'], $lang['links_err'], "?mod=links" );

	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '93', '{$tag}')" );
	$db->query( "INSERT INTO " . PREFIX . "_links (word, link, only_one) values ('{$tag}', '{$url}', '{$onlyone}')" );

	@unlink( ENGINE_DIR . '/cache/system/links.php' );
	clear_cache();
	header( "Location: ?mod=links" ); die();
}

if ($_GET['action'] == "edit") {

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}

	$tag = convert_unicode( urldecode ( $_GET['tag'] ), $config['charset']  );
	$url = convert_unicode( urldecode ( $_GET['url'] ), $config['charset']  );
	$onlyone = intval ( $_GET['onlyone'] );

	$tag = @$db->safesql( htmlspecialchars( strip_tags( stripslashes( trim( $tag ) ) ), ENT_COMPAT, $config['charset'] ) );
	$url = @$db->safesql( htmlspecialchars( strip_tags( stripslashes( trim( $url ) ) ), ENT_QUOTES, $config['charset'] ) );
	$url = str_ireplace( "document.cookie", "d&#111;cument.cookie", $url );
	$url = preg_replace( "/javascript:/i", "j&#097;vascript:", $url );
	$url = preg_replace( "/data:/i", "d&#097;ta:", $url );
	$id = intval ( $_GET['id'] );

	if (!$tag) msg( "error", $lang['index_denied'], $lang['links_err'], "?mod=links&start_from={$start_from}" );


	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '94', '{$tag}')" );
	$db->query( "UPDATE " . PREFIX . "_links SET word='{$tag}', link='{$url}', only_one='{$onlyone}' WHERE id='{$id}'" );

	@unlink( ENGINE_DIR . '/cache/system/links.php' );
	clear_cache();
	header( "Location: ?mod=links&start_from={$start_from}" ); die();
}

echoheader("", "");

echo <<<HTML
<form action="?mod=links" method="get" name="navi" id="navi">
<input type="hidden" name="mod" value="links">
<input type="hidden" name="start_from" id="start_from" value="{$start_from}">
</form>
<form action="?mod=links" method="post" name="optionsbar" id="optionsbar">
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_links']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
HTML;

$i = $start_from+$news_per_page;

$result_count = $db->super_query("SELECT COUNT(*) as count FROM " . PREFIX . "_links");
$all_count_news = $result_count['count'];


		// pagination

		$npp_nav = "<div class=\"news_navigation\" style=\"margin-bottom:5px; margin-top:5px;\">";
		
		if( $start_from > 0 ) {
			$previous = $start_from - $news_per_page;
			$npp_nav .= "<a onClick=\"javascript:search_submit($previous); return(false);\" href=\"#\" title=\"{$lang['edit_prev']}\">&lt;&lt;</a> ";
		}
		
		if( $all_count_news > $news_per_page ) {
			
			$enpages_count = @ceil( $all_count_news / $news_per_page );
			$enpages_start_from = 0;
			$enpages = "";
			
			if( $enpages_count <= 10 ) {
				
				for($j = 1; $j <= $enpages_count; $j ++) {
					
					if( $enpages_start_from != $start_from ) {
						
						$enpages .= "<a onClick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$j</a> ";
					
					} else {
						
						$enpages .= "<span>$j</span> ";
					}
					
					$enpages_start_from += $news_per_page;
				}
				
				$npp_nav .= $enpages;
			
			} else {
				
				$start = 1;
				$end = 10;
				
				if( $start_from > 0 ) {
					
					if( ($start_from / $news_per_page) > 4 ) {
						
						$start = @ceil( $start_from / $news_per_page ) - 3;
						$end = $start + 9;
						
						if( $end > $enpages_count ) {
							$start = $enpages_count - 10;
							$end = $enpages_count - 1;
						}
						
						$enpages_start_from = ($start - 1) * $news_per_page;
					
					}
				
				}
				
				if( $start > 2 ) {
					
					$enpages .= "<a onClick=\"javascript:search_submit(0); return(false);\" href=\"#\">1</a> ... ";
				
				}
				
				for($j = $start; $j <= $end; $j ++) {
					
					if( $enpages_start_from != $start_from ) {
						
						$enpages .= "<a onClick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$j</a> ";
					
					} else {
						
						$enpages .= "<span>$j</span> ";
					}
					
					$enpages_start_from += $news_per_page;
				}
				
				$enpages_start_from = ($enpages_count - 1) * $news_per_page;
				$enpages .= "... <a onClick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$enpages_count</a> ";
				
				$npp_nav .= $enpages;
			
			}
		
		}
		
		if( $all_count_news > $i ) {
			$how_next = $all_count_news - $i;
			if( $how_next > $news_per_page ) {
				$how_next = $news_per_page;
			}
			$npp_nav .= "<a onClick=\"javascript:search_submit($i); return(false);\" href=\"#\" title=\"{$lang['edit_next']}\">&gt;&gt;</a>";
		}
		
		$npp_nav .= "</div>";
		
		// pagination

$i = 0;

if ( $all_count_news ) {

	$entries = "";

	$db->query("SELECT * FROM " . PREFIX . "_links ORDER BY id DESC LIMIT {$start_from},{$news_per_page}");

	while($row = $db->get_row()) {

		$entries .= "<tr>
        <td style=\"padding:4px;\" nowrap><div id=\"content_{$row['id']}\">{$row['word']}</div></td>
        <td align=left><div id=\"url_{$row['id']}\">{$row['link']}</div><input type=\"hidden\" name=\"only_one_{$row['id']}\" id=\"only_one_{$row['id']}\" value=\"{$row['only_one']}\" /></td>
        <td align=center>[&nbsp;<a uid=\"{$row['id']}\" class=\"editlink\" href=\"?mod=links\">{$lang['word_ledit']}</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a uid=\"{$row['id']}\" class=\"dellink\" href=\"?mod=links\">{$lang['word_ldel']}</a>&nbsp;]</td>
        <td align=center><input name=\"selected_tags[]\" value=\"{$row['id']}\" type=\"checkbox\"></td>
        </tr>
        <tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=4></td></tr>";


	}

	$db->free();

echo <<<HTML
<table width="100%" id="tagslist">
	<tr class="thead">
    <th width="300" style="padding:2px;">{$lang['links_tag']}</th>
    <th>{$lang['links_url']}</th>
    <th width="350" align="center"><div style="text-align: center;">&nbsp;{$lang['user_action']}&nbsp;</div></th>
    <th width="30" align="center"><div style="text-align: center;"><input type="checkbox" name="master_box" title="{$lang['edit_selall']}" onclick="javascript:ckeck_uncheck_all()"></div></th>
	</tr>
	<tr class="tfoot"><th colspan="4"><div class="hr_line"></div></th></tr>
	{$entries}
	<tr class="tfoot"><th colspan="4"><div class="hr_line"></div></th></tr>
	<tr class="tfoot"><th colspan="2">{$npp_nav}</th><th colspan="2" valign="top">
<div style="margin-bottom:5px; margin-top:5px; text-align: right;"><input class="btn btn-success btn-mini" type="button" onclick="addLink()" value="{$lang['add_links']}">&nbsp;
<select name=action>
<option value="">{$lang['edit_selact']}</option>
<option value="mass_delete">{$lang['edit_seldel']}</option>
</select>&nbsp;<input class="btn btn-warning btn-mini" type="submit" value="{$lang['b_start']}"></div></th></tr>
	<tr class="tfoot"><th colspan="4"><div class="hr_line"></div>{$lang['opt_linkshelp']}</th></tr>
</table>

<script type="text/javascript">
$(function(){

		$("#tagslist").delegate("tr", "hover", function(){
		  $(this).toggleClass("hoverRow");
		});

		var tag_name = '';

		$('.dellink').click(function(){

			tag_name = $('#content_'+$(this).attr('uid')).text();
			var urlid = $(this).attr('uid');

		    DLEconfirm( '{$lang['tagscloud_del']} <b>&laquo;'+tag_name+'&raquo;</b> {$lang['tagscloud_del_2']}', '{$lang['p_confirm']}', function () {

				document.location='?mod=links&start_from={$start_from}&user_hash={$dle_login_hash}&action=delete&id=' + urlid;

			} );

			return false;
		});


		$('.editlink').click(function(){

			var tag = $('#content_'+$(this).attr('uid')).text();
			var url = $('#url_'+$(this).attr('uid')).text();
			var onlyone = $('#only_one_'+$(this).attr('uid')).val();
			var urlid = $(this).attr('uid');

			var b = {};
		
			b[dle_act_lang[3]] = function() { 
							$(this).dialog("close");						
					    };
		
			b[dle_act_lang[2]] = function() { 
							if ( $("#dle-promt-tag").val().length < 1) {
								 $("#dle-promt-tag").addClass('ui-state-error');
							} else if ( $("#dle-promt-url").val().length < 1 ) {
								 $("#dle-promt-tag").removeClass('ui-state-error');
								 $("#dle-promt-url").addClass('ui-state-error');
							} else {
								var tag = $("#dle-promt-tag").val();
								var url = $("#dle-promt-url").val();
	
								if ( $("#only-one").prop( "checked" ) ) { var onlyone = "1"; } else { var onlyone = "0"; }
	
								$(this).dialog("close");
								$("#dlepopup").remove();
	
								document.location='?mod=links&start_from={$start_from}&user_hash={$dle_login_hash}&action=edit&tag=' + encodeURIComponent(tag) + '&url=' + encodeURIComponent(url)+ '&onlyone=' + onlyone+ '&id=' + urlid;
	
							}				
						};
	
			$("#dlepopup").remove();

			$("body").append("<div id='dlepopup' title='{$lang['add_links_new']}' style='display:none'><br />{$lang['add_links_tag']}<br /><input type='text' name='dle-promt-tag' id='dle-promt-tag' class='ui-widget-content ui-corner-all' style='width:97%; padding: .4em;' value=\""+tag+"\"/><br /><br />{$lang['add_links_url']}<br /><input type='text' name='dle-promt-url' id='dle-promt-url' class='ui-widget-content ui-corner-all' style='width:97%; padding: .4em;' value='"+url+"'/><br /><br /><input type='checkbox' name='only-one' id='only-one' value=''><label for='only-one'>&nbsp;{$lang['add_links_one']}</label><input type='hidden' name='url-id' id='url-id' value='"+urlid+"'></div>");
		
			$('#dlepopup').dialog({
				autoOpen: true,
				width: 500,
				buttons: b
			});

			if ( onlyone == 1 ) {  $("#only-one").prop( "checked", "checked" ); }

			return false;
		});

});
</script>
HTML;


}  else {

echo <<<HTML
<table width="100%">
    <tr>
        <td style="padding:2px;height:50px;"><div align="center"><br /><br />{$lang['links_not_found']}<br /><br></a></div><input class="btn btn-success" type="button" onclick="addLink()" value="{$lang['add_links']}"><div class="hr_line"></div>{$lang['opt_linkshelp']}</td>
    </tr>
</table>
HTML;

}

echo <<<HTML
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div>
<input type="hidden" name="mod" value="links">
<input type="hidden" name="user_hash" value="{$dle_login_hash}">
<input type="hidden" name="start_from" id="start_from" value="{$start_from}">
</form>
<script language="javascript" type="text/javascript">  
<!-- 
    function search_submit(prm){
      document.navi.start_from.value=prm;
      document.navi.submit();
      return false;
    }

	function ckeck_uncheck_all() {
	    var frm = document.optionsbar;
	    for (var i=0;i<frm.elements.length;i++) {
	        var elmnt = frm.elements[i];
	        if (elmnt.type=='checkbox') {
	            if(frm.master_box.checked == true){ elmnt.checked=false; }
	            else{ elmnt.checked=true; }
	        }
	    }
	    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
	    else{ frm.master_box.checked = true; }
	}
	function addLink() {
		var b = {};
	
		b[dle_act_lang[3]] = function() { 
						$(this).dialog("close");						
				    };
	
		b[dle_act_lang[2]] = function() { 
						if ( $("#dle-promt-tag").val().length < 1) {
							 $("#dle-promt-tag").addClass('ui-state-error');
						} else if ( $("#dle-promt-url").val().length < 1 ) {
							 $("#dle-promt-tag").removeClass('ui-state-error');
							 $("#dle-promt-url").addClass('ui-state-error');
						} else {
							var tag = $("#dle-promt-tag").val();
							var url = $("#dle-promt-url").val();

							if ( $("#only-one").prop( "checked" ) ) { var onlyone = "1"; } else { var onlyone = "0"; }

							$(this).dialog("close");
							$("#dlepopup").remove();

							document.location='?mod=links&user_hash={$dle_login_hash}&action=add&tag=' + encodeURIComponent(tag) + '&url=' + encodeURIComponent(url)+ '&onlyone=' + onlyone;

						}				
					};

		$("#dlepopup").remove();

		$("body").append("<div id='dlepopup' title='{$lang['add_links_new']}' style='display:none'><br />{$lang['add_links_tag']}<br /><input type='text' name='dle-promt-tag' id='dle-promt-tag' class='ui-widget-content ui-corner-all' style='width:97%; padding: .4em;' value=''/><br /><br />{$lang['add_links_url']}<br /><input type='text' name='dle-promt-url' id='dle-promt-url' class='ui-widget-content ui-corner-all' style='width:97%; padding: .4em;' value='http://'/><br /><br /><input type='checkbox' name='only-one' id='only-one' value=''><label for='only-one'>&nbsp;{$lang['add_links_one']}</label></div>");
	
		$('#dlepopup').dialog({
			autoOpen: true,
			width: 500,
			buttons: b
		});

	}
//-->
</script>
HTML;


echofooter();
?>