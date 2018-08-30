<?php
class model_index extends model_base {
	function __construct() {
		parent::__construct ();
	}
	
	function model_menu() {
		if ($_GET['nodeid']) return false;
		if (file_exists ( WEB_TOR . 'user_session_ext/' . $_SESSION['USER_ID'] . '.txt' )) {
			$menu_id_str = file_get_contents ( WEB_TOR . 'user_session_ext/' . $_SESSION['USER_ID'] . '.txt' );
			$menu_arr = array();
			$menu_id_2 = array();
			$menu_id_4 = array();
			$menu_id_6 = array();
			if ($menu_id_str) {
				$tmp = explode ( ',', $menu_id_str );
				foreach ( $tmp as $val ) {
					if (strpos ( $val, '_' ) !== false) {
						$val = str_replace ( '_', '', $val );
						$menu_arr[] = $val;
						$menu_id_2[] = substr ( $val, 0, 2 );
						if (strlen ( $val ) == 4) {
							$menu_id_4[] = $val;
						} elseif (strlen ( $val ) == 6) {
							$menu_id_6[] = $val;
						}
					}
				}
			}
		}
		$menu_2 = $menu_id_2 ? implode ( ',', array_unique ( $menu_id_2 ) ) : '';
		$menu_4 = $menu_id_4 ? implode ( ',', array_unique ( $menu_id_4 ) ) : '';
		$menu_6 = $menu_id_6 ? implode ( ',', array_unique ( $menu_id_6 ) ) : '';
		
		$query = $this->query ( "select menu_id,menu_name from sys_menu " . ($menu_2 ? "where menu_id in ($menu_2)" : '')." order by taxis_id asc" );
		$tmp = 0;
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$responce->rows[$i]['cell'] = un_iconv ( array(
															$rs['menu_id'],
															$rs['menu_name'],
															'',
															0,
															'',
															'false',
															'false') );
			/*$str .= '<row>
						<cell>' . $rs['menu_id'] . '</cell>
						<cell>' . mb_convert_encoding ( $rs['menu_name'], 'UTF-8', 'GBK' ) . '</cell>
						<cell></cell>
						<cell>0</cell>
						<cell></cell>
						<cell>false</cell>
						<cell>flase</cell>
					</row>
					';*/
			$i ++;
			//=========================二级
			$sql = $this->query ( "
								select 
									menu_id,func_id,func_name,func_code 
								from 
									sys_function where left(menu_id,2)='" . $rs['menu_id'] . "' 
									and length(menu_id)<=4 " . ($menu_4 ? ' and menu_id in(' . $menu_4 . ')' : '') . "
								order by taxis_id asc
									" );
			while ( ($row = $this->fetch_array ( $sql )) != false ) {
				if (strpos ( $row['func_code'], '@' ) !== false) {
					$responce->rows[$i]['cell'] = un_iconv ( array(
																		$row['menu_id'],
																		$row['func_name'],
																		'',
																		1,
																		$rs['menu_id'],
																		'false',
																		'false') );
					/*$str .= '<row>
								<cell>' . $row['menu_id'] . '</cell>
								<cell>' . mb_convert_encoding ( $row['func_name'], 'utf-8', 'GBK' ) . '</cell>
								<cell></cell>
								<cell>1</cell>
								<cell>' . $rs['menu_id'] . '</cell>
								<cell>false</cell>
								<cell>false</cell>
							</row>
							';*/
				} else {
					$responce->rows[$i]['cell'] = un_iconv ( array(
																		$row['menu_id'],
																		$row['func_name'],
																		$row['func_code'],
																		1,
																		$rs['menu_id'],
																		'true',
																		'true') );
					/*$str .= '<row>
								<cell>' . $row['menu_id'] . '</cell>
								<cell>' . mb_convert_encoding ( $row['func_name'], 'utf-8', 'GBK' ) . '</cell>
								<cell>' . mb_convert_encoding ( $row['func_code'], 'utf-8', 'GBK' ) . '</cell>
								<cell>1</cell>
								<cell>' . $rs['menu_id'] . '</cell>
								<cell>true</cell>
								<cell>true</cell>
							</row>
							';*/
				}
				$i++;
				//=============================三级
				$mysql = $this->query ( "
								select 
									menu_id,func_id,func_name,func_code 
								from 
									sys_function where left(menu_id,4)='" . $row['menu_id'] . "'
									and length(menu_id)>4 " . ($menu_6 ? ' and menu_id in(' . $menu_6 . ')' : '') . "
								order by taxis_id asc
									" );
				while ( ($rw = $this->fetch_array ( $mysql )) != false ) {
					$responce->rows[$i]['cell'] = un_iconv ( array(
																		$rw['menu_id'],
																		$rw['func_name'],
																		$rw['func_code'],
																		2,
																		$row['menu_id'],
																		'true',
																		'true') );
					/*$str .= '<row>
								<cell>' . $rw['menu_id'] . '</cell>
								<cell>' . mb_convert_encoding ( $rw['func_name'], 'utf-8', 'GBK' ) . '</cell>
								<cell>' . mb_convert_encoding ( $rw['func_code'], 'utf-8', 'GBK' ) . '</cell>
								<cell>2</cell>
								<cell>' . $row['menu_id'] . '</cell>
								<cell>true</cell>
								<cell>true</cell>
							</row>
							';*/
				$i++;														
				}
			}
		
		}
		return $responce;
	}
	function model_menu_list() {
		if (file_exists ( WEB_TOR . 'user_session_ext/' . $_SESSION['USER_ID'] . '.txt' )) {
			$menu_id_str = file_get_contents ( WEB_TOR . 'user_session_ext/' . $_SESSION['USER_ID'] . '.txt' );
			$menu_arr = array();
			$menu_id_2 = array();
			$menu_id_4 = array();
			$menu_id_6 = array();
			if ($menu_id_str) {
				$tmp = explode ( ',', $menu_id_str );
				foreach ( $tmp as $val ) {
					if (strpos ( $val, '_' ) !== false) {
						$val = str_replace ( '_', '', $val );
						$menu_arr[] = $val;
						$menu_id_2[] = substr ( $val, 0, 2 );
						if (strlen ( $val ) == 4) {
							$menu_id_4[] = $val;
						} elseif (strlen ( $val ) == 6) {
							$menu_id_6[] = $val;
						}
					}
				}
			}
		}
		$menu_2 = $menu_id_2 ? implode ( ',', array_unique ( $menu_id_2 ) ) : '';
		$menu_4 = $menu_id_4 ? implode ( ',', array_unique ( $menu_id_4 ) ) : '';
		$menu_6 = $menu_id_6 ? implode ( ',', array_unique ( $menu_id_6 ) ) : '';
		$responce = '';
		$i = 0;
		if (! $_GET['nodeid']) {
			$query = $this->query ( "select menu_id,menu_name from sys_menu " . ($menu_2 ? "where menu_id in ($menu_2)" : '')." order by taxis_id asc");
			
			while ( ($rs = $this->fetch_array ( $query )) != false ) {
				$responce->rows[$i]['cell'] = un_iconv ( array(
																$rs['menu_id'],
																$rs['menu_name'],
																'',
																0,
																'',
																'false',
																'false') );
				$i++;
			}
		
		} elseif (strlen ( $_GET['nodeid'] ) == 2) {
			$sql = $this->query ( "
								select 
									menu_id,func_id,func_name,func_code 
								from 
									sys_function where left(menu_id,2)='" . $_GET['nodeid'] . "' 
									" . ($menu_4 ? ' and menu_id in(' . $menu_4 . ')' : '') . "
								order by taxis_id asc
									" );
			while ( ($row = $this->fetch_array ( $sql )) != false ) {
				if (strpos ( $row['func_code'], '@' ) !== false) {
					$responce->rows[$i]['cell'] = un_iconv ( array(
																		$row['menu_id'],
																		$row['func_name'],
																		'',
																		1,
																		$_GET['nodeid'],
																		'false',
																		'false') );
				}else{
					$responce->rows[$i]['cell'] = un_iconv ( array(
																		$row['menu_id'],
																		$row['func_name'],
																		$row['func_code'],
																		1,
																		$_GET['nodeid'],
																		'true',
																		'true') );
				}
				$i++;
			}
		} elseif (strlen ( $_GET['nodeid'] ) == 4) {
			$mysql = $this->query ( "
								select 
									menu_id,func_id,func_name,func_code 
								from 
									sys_function where left(menu_id,4)='" . $_GET['nodeid'] . "'
									and length(menu_id)>4 " . ($menu_6 ? ' and menu_id in(' . $menu_6 . ')' : '') . "
								order by taxis_id asc
									" );
				while ( ($rw = $this->fetch_array ( $mysql )) != false ) {
					$responce->rows[$i]['cell'] = un_iconv ( array(
																		$rw['menu_id'],
																		$rw['func_name'],
																		$rw['func_code'],
																		2,
																		$_GET['nodeid'],
																		'true',
																		'true') );
				$i++;
				}
		}
		return $responce;
	}
	/**
	 * 公告
	 */
	function model_notice() {
		$query = $this->query ( "
								select  
									* 
								from 
									notice 
								where 
									audit=0 
									and (effect=1 or start_date < " . time () . ") 
									and effect!=2 
									and find_in_set('" . $_SESSION['USER_ID'] . "',user_id_str) 
									order by edit_time desc , start_date desc
								limit 10
							" );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '.
						<a href="?model=info_notice&action=showinfo&id=' . $rs['id'] . '&rand_key=' . $rs['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="查看公告：' . $rs['title'] . '">
							' . $rs['title'] . '
						</a> 
						<span>' . date ( 'Y-m-d', $rs['date'] ) . '</span>
					</li>';
		}
		return $str;
	}
	/**
	 * 新闻
	 */
	function model_news() {
		$query = $this->query ( "select news_id,subject,news_time from news order by news_id limit 10" );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '.
						<a href="general/news/show/show_news.php?NEWS_ID=' . $rs['news_id'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=600" class="thickbox" title="查看新闻：' . $rs['subject'] . '">
							' . $rs['subject'] . '
						</a> 
						<span>' . $rs['news_time'] . '</span>
					</li>';
		}
		return $str;
	}
	/**
	 * 工作计划
	 */
	function model_work_plan() {
		$query = $this->query ( "
							select 
								* 
							from 
								work_plan  
							where 
								( find_in_set('" . $_SESSION['DEPT_ID'] . "',TO_ID)>0 or TO_ID='ALL_DEPT' ) 
								and STATUS_TAG ='1' 
								and END_DATE>='" . date ( 'Y-m-d' ) . "' 
							order by PLAN_ID desc LIMIT 10" );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '
					<li> ' . $i . '.
						<a href="general/work_plan/show/plan_detail.php?PLAN_ID=' . $rs['PLAN_ID'] . '">' . $rs['NAME'] . '</a>
						<span>' . $rs['BEGIN_DATE'] . ' 至 ' . $rs['END_DATE'] . ' 
						' . (strtotime ( $rs["BEGIN_DATE"] ) >= (time () - 3 * 24 * 3600) ? ' <img src="../images/new.gif"  border="0" />' : '') . '
						</span>
					</li>
			';
		}
		return $str;
	}
	/**
	 * 投票
	 */
	function model_vote() {
		$query = $this->query ( "
								SELECT 
									vote.*,user.USER_NAME 
								FROM 
									vote ,user 
								where
									user.USER_ID=vote.USER_ID
									and BEGIN_DATE<='" . date ( "Y-m-d" ) . "' 
									and (END_DATE>='" . date ( "Y-m-d" ) . "' or END_DATE='0000-00-00') 
									and Flag='0' 
									and  ( ( ( find_in_set('" . $_SESSION['DEPT_ID'] . "',vote.DEPT_ID)>0 
												or vote.DEPT_ID='ALL_DEPT' 
												or find_in_set('" . $_SESSION['USER_PRIV'] . "',vote.PRIV_ID)>0 
												or vote.PRIV_ID='ALL_PRIV' 
												or find_in_set('" . $_SESSION['USER_ID'] . "',vote.TO_ID)>0 ) 
												and (find_in_set('" . $_SESSION['AREA'] . "',vote.AREA_ID)>0 
												or vote.AREA_ID='ALL_AREA' or vote.AREA_ID='' ) ) 
												or ( vote.DEPT_ID='' and vote.PRIV_ID='' 
												and vote.TO_ID='' 
												and (find_in_set('" . $_SESSION['AREA'] . "',vote.AREA_ID)>0 
												or vote.AREA_ID='ALL_AREA' or vote.AREA_ID='' ) ) )  
									order by vote.ID desc LIMIT 10
							" );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '.
						<a href="general/' . ($rs['TYPE'] == 0 ? 'vote/vote_radio.php?Pid=' . $rs['Pid'] : 'vote/vote_action.php?Pid=' . $rs['Pid']) . '"> 
						' . $rs['SUBJECT'] . ' ' . $rs['USER_NAME'] . ' ' . $rs['BEGIN_DATE'] . ' 
						' . ((strtotime ( $rs["BEGIN_DATE"] ) >= time () - 3 * 24 * 3600) ? '<img src="images/new.gif"  border=0 >' : '') . '
						</a>
					</li>
			
			';
		}
		return $str;
	}
	/**
	 * 车辆信息
	 */
	function model_vehicle() {
		$query = $this->query ( "
							select 
								a.*,b.v_num,c.user_name
							from  
								vehicle_usage as a
								left join vehicle as b on b.V_ID=a.V_ID
								left join user as c on c.user_id=a.VU_USER
							where 
								a.VU_STATUS!='0' 
							order by a.vu_id desc LIMIT 10
							" );
		$i = 0;
		$status = array(
						"0"=>"待批",
						"1"=>"已批准",
						"2"=>"未准",
						"3"=>"进行中",
						"4"=>"使用中",
						"5"=>"已结束");
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '.
						<a href="general/vehicle/usage_detail.php?VU_ID=' . $rs['VU_ID'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=400" class="thickbox" title="查看：' . $rs['v_num'] . ' 申请">
						' . $rs['v_num'] . ' ' . $rs['user_name'] . ' ' . $rs['VU_START'] . ' 至 ' . $rs['VU_END'] . ' ' . $status[$rs['VU_STATUS']] . ' 
						' . (strtotime ( $rs['VU_START'] ) >= time () ? '<img src="../images/new.gif"  border="0">' : '') . '
						</a>
					</li>';
		}
		return $str;
	}
	/**
	 * 会议安排
	 */
	function model_meeting() {
		$query = $this->query ( "
						select 
							a.*,b.MR_NAME
						from 
							meeting as a
							left join meeting_room as b on b.MR_ID=a.M_ROOM
						where 
							a.M_PROPOSER='" . $_SESSION['USER_ID'] . "' 
						order by a.M_ID desc limit 10
						" );
		$status = array(
						0=>'待审批',
						1=>'以审批',
						2=>'未审批',
						3=>'进行中');
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '.
						<a href="general/meeting/meeting_detail.php?M_ID=' . $rs['M_ID'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=400" class="thickbox" title="查看：' . $rs['MR_NAME'] . '">
						' . $rs['MR_NAME'] . ' ' . substr ( $rs['M_NAME'], 0, 20 ) . ' ' . substr ( $rs['M_START'], 0, 10 ) . ' ' . $status[$rs['M_STATUS']] . ' 
						' . (strtotime ( $rs['M_START'] ) >= (time () - 3 * 24 * 3600) ? '<img rc="../images/new.gif"  border="0">' : '') . '
						</a>
					</li>';
		}
		return $str;
	}
	/**
	 * 日历
	 */
	function model_calendar() {
		$str = '<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#d0d0c8" cellpadding="3">
	    <tr align="center" class="D3E5FA">
	      <td bgcolor="#FFCCFF" width="10%" height="23"><b>日</b></td>
	      <td bgcolor="#D3E5FA" width="10%" ><b>一</b></td>
	      <td bgcolor="#D3E5FA" width="10%"><b>二</b></td>
	      <td bgcolor="#D3E5FA" width="10%"><b>三</b></td>
	      <td bgcolor="#D3E5FA" width="10%"><b>四</b></td>
	      <td bgcolor="#D3E5FA" width="10%"><b>五</b></td>
	      <td bgcolor="#CCFFCC" width="10%"><b>六</b></td>
	    </tr>
	    <tr>
	    ';
		$MONTH = isset ( $MONTH ) ? $MONTH : date ( "m" );
		$YEAR = isset ( $YEAR ) ? $YEAR : date ( "Y" );
		$checkDate = $YEAR . "-" . $MONTH;
		$query = $this->query ( "
								select 
									SUBSTRING(CAL_TIME,9,2) as checkday 
								from 
									calendar 
								where 
									left(CAL_TIME,7)='$checkDate' 
									and USER_ID='" . $_SESSION['USER_ID'] . "' 
							" );
		$checkArr = array();
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$checkArr[] = intval ( $rs['checkday'] );
		}
		$firstday = date ( "w", mktime ( 0, 0, 0, $MONTH, 1, $YEAR ) );
		for($i = 0; $i < $firstday; $i ++) {
			$str .= '<td class=TableLine2 valign="top">
    					<div align=center style="font-family:sans-serif"><b>&nbsp;</b></div>
    				</td>';
		}
		$countDays = date ( "t", mktime ( 0, 0, 0, $MONTH, 1, $YEAR ) );
		for($i = 1; $i <= $countDays; $i ++) {
			$tempstr = "YEAR=$YEAR&MONTH=$MONTH&DAY=$i";
			$str .= '<td class=TableLine2 valign="top">
    				<div id="nd" align="center" style="font-family:sans-serif; ' . ($i == date ( "d" ) ? 'background-color :#FF9999;"' : '') . '">
    					<b><a href="general/calendar/manage?' . $tempstr . '" style="color:' . (in_array ( $i, $checkArr ) ? 'red' : '#0066cc') . ';">' . $i . '</a></b>
    				</div>
    			</td>';
			if (date ( "w", mktime ( 0, 0, 0, $MONTH, $i, $YEAR ) ) == 6 && $i != $countDays) {
				$str .= '</tr><tr>';
			}
		}
		$lastday = date ( "w", mktime ( 0, 0, 0, $MONTH, $countDays, $YEAR ) );
		for($j = 0; $j < 6 - $lastday; $j ++) {
			
			$str .= '<td class=TableLine2 valign="top">
						<div align=center style="font-family: sans-serif"><b>&nbsp;</b></div>
					</td>
					';
		}
		$str .= '</tr></table>';
		return $str;
	}
	/**
	 * 费用管理
	 */
	function model_costmanage() {
	
	}
	/**
	 * 个人费用
	 */
	function model_cost_personal() {
		$query = $this->query ( "
							select 
								a.*,b.name
							from 
								cost_summary_list as a 
								left join area as b on b.ID=a.Area
							where 
								(a.InputMan ='" . $_SESSION['USER_ID'] . "' or a.CostMan='" . $_SESSION['USER_ID'] . "') 
							order by a.UpdateDT desc limit 0, 5 
							" );
		$num = $this->_db->num_rows ( $query );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$url = '?placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1100" class="thickbox" title="' . $rs['BillNo'] . ' ' . $rs['name'] . ' ' . $rs['Status'] . '"';
			$str .= '<li>' . $i . '.
						<a href="general/' . ($rs['isProject'] ? 'costmanage/reim/' . $url . '>部门报销' : 'costmanage/reim/index.html' . $url . '>项目报销') . '
						' . $rs['BillNo'] . ' ' . $rs['name'] . ' ' . $rs['Status'] . ' 
						' . (strtotime ( $rs["UpdateDT"] ) >= time () - 3 * 24 * 3600 ? '<img src="images/new.gif"  border="0" >' : '') . '
						</a>
					</li>
			';
		}
		
		$num = 10 - $num;
		$query = $this->query ( "select * from loan_list where Debtor ='" . $_SESSION['USER_ID'] . "' order by ID desc limit $num" );
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$url = '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600" class="thickbox" title="' . $rs['Status'] . '"';
			$str .= '<li>' . $i . '.
						<a href="general/costmanage/loan/bill.php?&status=' . $rs['Status'] . '&ID=' . $rs['ID'] . $url . '>个人借款  
						' . $rs['ID'] . ' ' . $rs['Status'] . '
						' . (strtotime ( $rs["PayDT"] ) >= time () - 3 * 24 * 3600 ? '<img src="../images/new.gif"  border="0" />' : '') . '
						</a>
					</li>';
		}
		return $str;
	}
	/**
	 * 个人考勤
	 */
	function model_attendance() {
		$query = $this->query ( "
							select 
								* 
							from 
								attend_leave 
							where 
								USER_ID='" . $_SESSION['USER_ID'] . "' 
								and STATUS='2' 
							order by 
								LEAVE_ID desc  limit 10
							" );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '.
						<a href="general/attendance/personal/">' . $rs['LEAVE_TYPE'] . ' ' . $rs['LEAVE_DATE1'] . ' ' . $rs['LEAVE_DATE2'] . ' ' . $rs['DAYS'] . '天
						' . (strtotime ( $rs["CreatDT"] ) >= time () - 3 * 24 * 3600 ? '<img src="../images/new.gif"  border="0" />' : '') . '
						</a>
				
					</li>
			';
		}
		return $str;
	}
	/**
	 * 工作日志
	 */
	function model_diary() {
		$query = $this->query ( "select * from diary  where USER_ID='" . $_SESSION['USER_ID'] . "' order by DIA_DATE desc LIMIT 10 " );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '.
						<a href="general/diary/read.php?DIA_ID=' . $rs['DIA_ID'] . '">
							' . ($rs['DIA_TYPE'] == 1 ? '工作日志' : '个人日志') . ' ' . substr ( $rs['DIA_DATE'], 0, 10 ) . ' 
							' . $rs['SUBJECT'] . ' ' . (strtotime ( $rs["DIA_DATE"] ) >= time () - 3 * 24 * 3600 ? '<img src="images/new.gif"  border="0" />' : '') . '
						</a>
					</li>';
		}
		return $str;
	}
	/**
	 * 工作周报
	 */
	function model_weekly() {
		$query = $this->query ( "
								select 
									* 
								from 
									weekly 
								where 
									USER_ID='" . $_SESSION['USER_ID'] . "' 
								order by 
									Flag , id desc LIMIT 10 
							" );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$str .= '<li>' . $i . '
						<a href="general/weekly/read_weekly.php?read_id=' . $rs['id'] . '"> ' . ($rs['type'] == 0 ? '单周报' : ($rs['type'] == 1 ? '双周报' : '月报')) . '
						' . $rs['w_num'] . ' ' . $rs['title'] . ' 
						' . (strtotime ( $rs["CreateDT"] ) >= time () - 3 * 24 * 3600 ? '<img src="images/new.gif"  border="0" >' : '') . ' 
						</a>
					</li>
				';
		}
		return $str;
	
	}
	/**
	 * IT维修申请表
	 */
	function model_itservice() {
		$query = $this->query ( "select * from equi_service where appid='" . $_SESSION['USER_ID'] . "' order by flag, appdate limit 10 " );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '
						<a href="general/equipment/service/detail.php?readid=' . $rs['id'] . '">
							' . $rs['id'] . ' ' . substr ( $rs['appdate'], 0, 10 ) . ' ' . ($rs['flag'] == 0 ? '等待中' : '已完成') . '
							' . (strtotime ( $rs["appdate"] ) >= time () - 3 * 24 * 3600 ? '<img src="images/new.gif"  border="0" />' : '') . '
						</a>
					</li>
			';
		}
		return $str;
	}
	/**
	 * IT维修管理
	 */
	function model_itservice_man() {
		$query = $this->query ( "
								select 
									a.*,b.user_name 
								from 
									equi_service as a
									left join user as b on b.user_id=a.appid	
								order by a.flag, a.appdate limit 10 
								" );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '.
						<a href="general/equipment/service/detail.php?readid=' . $rs['id'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=500" class="thickbox" title="查看详细">
						' . $rs['id'] . ' ' . $rs['user_name'] . ' ' . substr ( $rs['appdate'], 0, 10 ) . '
						' . ($rs['flag'] == 0 ? '等待中' : '已完成') . '
						' . (strtotime ( $rs["appdate"] ) >= time () - 3 * 24 * 3600 ? '<img src="images/new.gif"  border="0" />' : '') . '
						</a>
					</li>
			';
		}
		return $str;
	}
	/**
	 * 讨论区
	 */
	function model_bbs() {
	
	}
	/**
	 * 课程学习
	 */
	function model_study() {
		$query = $this->query ( "select * from course_data where 1 order by upd desc limit 10 " );
		$i = 0;
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$i ++;
			$str .= '<li>' . $i . '
						<a href="general/education/training/study/index.php?data_id=' . $rs['pid'] . '&play_id=' . $rs['id'] . '">
						' . $rs['name'] . ' ' . ($rs['type'] == 1 ? '视频类' : '文档类') . '
						' . (strtotime ( $rs["upd"] ) >= time () - 3 * 24 * 3600 ? '<img src="../images/new.gif"  border="0" />' : '') . '
						</a>
					</li>
			';
		}
		return $str;
	}
}
?>