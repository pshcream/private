<?php
function get_active_menu_name($menu_datas)
{
	global $g5;
	//print_r($g5);
	foreach($menu_datas as $item)
	{
		if($item['me_code'] == $g5['me_code']){
			if($item['sub']){
				get_active_menu_name($item['sub']);
			}else{
				return $item['me_name'];
			}
		}	
	}
}
function get_active_menu($menu_datas)
{
	global $g5;

	foreach($menu_datas as $item)
	{
		$part = parse_url($item['me_link']);
		$item['path'] = $part['path'].'/';

		$part = parse_url($_SERVER['REQUEST_URI']);
		$self['path'] = $part['path'].'/';

		if($item['me_code'] == $g5['me_code'] || 
			(!$g5['me_code'] && !in_array($item['path'], array('', '/')) && strncmp($item['path'], $self['path'], strlen($item['path']))===0))
		{
			//echo $item['me_code'].' - '.$g5['me_code'].'<br />';
			//echo $item['path'].' - '.$self['path'].'<br />';
			//echo $item['path'] .' - '. strncmp($item['path'], $self['path'], strlen($item['path']));
			$g5['me_code'] = $item['me_code'];
		}

		if($item['sub']) get_active_menu($item['sub']);
	}
}

function get_layout_menu($menu_datas)
{
	global $g5,$is_admin,$member;

	$output = '';
	foreach($menu_datas as $item)
	{
		if ($item['me_code']=="30") {
			if($member['mb_level']<10)
				break;
			
		}
		
		$item['act'] = $item['me_code'] == substr($g5['me_code'], 0, strlen($item['me_code'])) ? 'active' : '';

		if(!$item['sub'])
		{
			$output .= '<li class="nav-item"><a href="'.$item['me_link'].'" target="_'.$item['me_target'].'" class="nav-link '.$item['act'].' '.$item['me_class'].'">'.$item['me_name'].'</a></li>';
		}
		else
		{
			$output .= '<li class="nav-item dropdown"><a href="'.$item['me_link'].'" target="_'.$item['me_target'].'" class="nav-link dropdown-toggle '.$item['act'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$item['me_name'].'</a><div class="dropdown-menu">';

			foreach($item['sub'] as $item2)
			{
				$item2['act'] = $item2['me_code'] == substr($g5['me_code'], 0, strlen($item2['me_code'])) ? 'active' : '';

				if($item2['me_id']==-1)
					$output .= '<div class="dropdown-divider"></div>';
				else
					$output .= '<a href="'.$item2['me_link'].'" class="dropdown-item '.$item2['act'].' '.$item2['me_class'].'">'.$item2['me_name'].'</a>';
			}
			
			$output .= '</div></li>';
		}
	}

	return $output;
}

function get_layout_breadcrumb($menu_datas, $recursive=false)
{
	global $g5;

	$output = '';
	foreach($menu_datas as $item)
	{
		if($item['me_code'] == substr($g5['me_code'], 0, strlen($item['me_code'])))
			if($item['me_code'] != $g5['me_code'])
				$output .= '<li class="breadcrumb-item"><a href="'.$item['me_link'].'">'.$item['me_name'].'</a></li>';
			else
				$output .= '<li class="breadcrumb-item active">'.$item['me_name'].'</li>';

		if($item['sub']) $output .= get_layout_breadcrumb($item['sub'], true);
	}

	if(!$recursive) $output = '<li class="breadcrumb-item"><a href="'.G5_URL.'">Home</a></li>'.$output;

	return $output;
}

function get_member_info($mb_id, $name='', $email='', $homepage='')
{
    global $config;
    global $g5;
    global $bo_table, $sca, $is_admin, $member;

    $email_enc = new str_encrypt();
    $email = $email_enc->encrypt($email);
    $homepage = set_http(clean_xss_tags($homepage));

    $name     = get_text($name, 0, true);
    $email    = get_text($email);
    $homepage = get_text($homepage);

	$mb_ico_url = G5_IMG_URL.'/no_profile.gif';
	$mb_img_url = G5_IMG_URL.'/no_profile.gif';

    if ($mb_id)
	{
		$mb_icon_img = $mb_id.'.gif';

		if(file_exists(G5_DATA_PATH.'/member/'.substr($mb_id,0,2).'/'.$mb_icon_img))
			$mb_ico_url = G5_DATA_URL.'/member/'.substr($mb_id,0,2).'/'.$mb_icon_img;

		if(file_exists(G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2).'/'.$mb_icon_img))
			$mb_img_url = G5_DATA_URL.'/member_image/'.substr($mb_id,0,2).'/'.$mb_icon_img;

		/*
        if ($config['cf_use_member_icon']) {
                if ($config['cf_use_member_icon'] == 2) // 회원아이콘+이름
		*/
	} else {
		if(!$bo_table)
		  return array('ico'=>$mb_ico_url, 'img'=>$mb_img_url, 'menu'=>'');

		$menu .= '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;sca='.$sca.'&amp;sfl=wr_name,1&amp;stx='.$name.'" title="'.$name.' 이름으로 검색" class="dropdown-item" rel="nofollow" onclick="return false;">'.$name.'</a>';
	}

	$menu = '<div class="dropdown-menu">';

    if($mb_id)
        $menu .= '<a href="'.G5_BBS_URL.'/memo_form.php?me_recv_mb_id='.$mb_id.'" class="dropdown-item" onclick="win_memo(this.href); return false;">쪽지보내기</a>';
    if($email)
        $menu .= '<a href="'.G5_BBS_URL.'/formmail.php?mb_id='.$mb_id.'&name='.urlencode($name).'&email='.$email.'" class="dropdown-item"  onclick="win_email(this.href); return false;">메일보내기</a>';
    if($homepage)
        $menu .= '<a href="'.$homepage.'" class="dropdown-item" target="_blank">홈페이지</a>';
    if($mb_id)
        $menu .= '<a href="'.G5_BBS_URL.'/profile.php?mb_id='.$mb_id.'" onclick="win_profile(this.href); return false;" class="dropdown-item" >자기소개</a>';
    if($bo_table) {
        if($mb_id)
            $menu .= '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&sca='.$sca.'&sfl=mb_id,1&stx='.$mb_id.'" class="dropdown-item" >아이디로 검색</a>';
        else
            $menu .= '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table."&sca=".$sca.'&sfl=wr_name,1&stx='.$name.'" class="dropdown-item" >이름으로 검색</a>';
    }
    if($mb_id)
        $menu .= '<a href="'.G5_BBS_URL.'/new.php?mb_id='.$mb_id.'" class="dropdown-item" onclick="check_goto_new(this.href, event);">전체게시물</a>';
    if($is_admin == "super" && $mb_id) {
        $menu .= '<a href="'.G5_ADMIN_URL.'/member_form.php?w=u&mb_id='.$mb_id.'" class="dropdown-item" target="_blank">회원정보변경</a>';
        $menu .= '<a href="'.G5_ADMIN_URL.'/point_list.php?sfl=mb_id&stx='.$mb_id.'" class="dropdown-item" target="_blank">포인트내역</a>';
    }

	$menu .= '</div>';

    return array('ico'=>$mb_ico_url, 'img'=>$mb_img_url, 'menu'=>$menu);
}

function chg_paging($write_pages)
{
	$remove = array();
	$remove[] = '<span class="sound_only">페이지';
	$remove[] = '<span class="pg">';
	$remove[] = '</span>';
	$remove[] = ' pg_start';
	$remove[] = ' pg_end';
	$remove[] = ' pg_next';
	$remove[] = ' pg_prev';

	$write_pages = str_replace('<nav class="pg_wrap">', '<nav><ul class="pagination">', $write_pages);
	$write_pages = str_replace('</nav>', '</ul></nav>', $write_pages);
	$write_pages = str_replace($remove, '', $write_pages);
	$write_pages = str_replace('pg_page', 'page-link', $write_pages);

	$write_pages = str_replace('<a href="', '<li class="page-item"><a href="', $write_pages);
	$write_pages = str_replace('</a>', '</a></li>', $write_pages);

	$write_pages = str_replace('<span class="sound_only">열린<strong class="pg_current">', '<li class="page-item active"><a href="#" class="page-link">', $write_pages);
	$write_pages = str_replace('</strong>', '</a></li>', $write_pages);


	$write_pages = str_replace('처음', '<i class="fa fa-angle-double-left"></i>', $write_pages);
	$write_pages = str_replace('이전', '<i class="fa fa-angle-left"></i>', $write_pages);
	$write_pages = str_replace('다음', '<i class="fa fa-angle-right"></i>', $write_pages);
	$write_pages = str_replace('맨끝', '<i class="fa fa-angle-double-right"></i>', $write_pages);

	return $write_pages;
}

function chg_board_list($str_board_list)
{
	$str_board_list = str_replace('<li>', '<li class="list-inline-item">', $str_board_list);
	$str_board_list = str_replace('<strong>', '', $str_board_list);
	$str_board_list = str_replace('</strong><span class="cnt_cmt">', ' <span class="badge badge-light">', $str_board_list);
	$str_board_list = str_replace(' class=sch_on>', ' class="btn btn-primary btn-sm active">', $str_board_list);
	$str_board_list = str_replace(' >', ' class="btn btn-primary btn-sm">', $str_board_list);

	return $str_board_list;
}

//input value 에서 xss 공격 filter 역할을 함 ( 반드시 input value='' 타입에만 사용할것 )
function get_sanitize_input($s, $is_html=false){

    if(!$is_html){
        $s = strip_tags($s);
    }

    $s = htmlspecialchars($s, ENT_QUOTES, 'utf-8');

    return $s;
}

function get_category_option_rchada($bo_table='', $ca_name='')
{
    global $g5, $board, $is_admin;

    $categories = explode("|", $board['bo_category_list']); // 구분자가 | 로 되어 있음
    $str = "";
    for ($i=0; $i<count($categories); $i++) {
        $category = trim($categories[$i]);
        if (!$category) continue;

        $str .= "<option value=\"$categories[$i]\"";
        if ($category == $ca_name) {
            $str .= ' selected="selected"';
        }
        $str .= ">$categories[$i]</option>\n";
    }

    return $str;
}

if ( ! function_exists('array_overlap')) {
    function array_overlap($arr, $val) {
        for ($i=0, $m=count($arr); $i<$m; $i++) {
            if ($arr[$i] == $val)
                return true;
        }
        return false;
    }
}
if ( ! function_exists('get_hp')) {
    function get_hp($hp, $hyphen=1)
    {
        global $g5;

        if (!is_hp($hp)) return '';

        if ($hyphen) $preg = "$1-$2-$3"; else $preg = "$1$2$3";

        $hp = str_replace('-', '', trim($hp));
        $hp = preg_replace("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $preg, $hp);

        if (isset($g5['sms5_demo']) && $g5['sms5_demo'])
            $hp = '0100000000';

        return $hp;
    }
}
if ( ! function_exists('is_hp')) {
    function is_hp($hp)
    {
        $hp = str_replace('-', '', trim($hp));
        if (preg_match("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $hp))
            return true;
        else
            return false;
    }
}
if ( ! function_exists('alert_just')) {
    // 경고메세지를 경고창으로
    function alert_just($msg='', $url='')
    {
        global $g5;

        if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

        //header("Content-Type: text/html; charset=$g5[charset]");
        echo "<meta charset=\"utf-8\">";
        echo "<script language='javascript'>alert('$msg');";
        echo "</script>";
        exit;
    }
}

if ( ! function_exists('utf2euc')) {
    function utf2euc($str) {
        return iconv("UTF-8","cp949//IGNORE", $str);
    }
}
if ( ! function_exists('is_ie')) {
    function is_ie() {
        return isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false);
    }
}

if ( ! function_exists('parent_alert')) {
	function parent_alert($txt){
		echo "<script type='text/javascript'>";
		echo "	parent.document.getElementById('CD_BOARDID').value='';";
		echo "	parent.document.getElementById('COUNSEL_GUBUN').value='';";
		echo "	alert('".$txt."');	";
		echo "</script>";
	}
}
if ( ! function_exists('parent_alert_reload')) {
	function parent_alert_reload($txt){
		echo "<script type='text/javascript'>	";
		echo "	alert('".$txt."');	";
		echo "	parent.document.getElementById('CD_BOARDID').value='';	";
		echo "	parent.document.getElementById('COUNSEL_GUBUN').value='';	";
		echo "	parent.document.location.reload();	";
		echo "</script>	";
	}
}


?>