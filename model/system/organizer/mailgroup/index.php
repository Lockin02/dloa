<?php
class model_system_organizer_mailgroup_index extends model_base {
	private $Http = null;
	private $EmailServer = null;
	function __construct() {
		parent :: __construct();
		$this->tbl_name = 'sys_organizer_mailgroup';
		$this->Http = new includes_class_httpclient();
	}
	/**
	 * 列表
	 */
	function model_list() {
		global $func_limit;
		$gl = new includes_class_global();
		$sub_sql = "( find_in_set('" . $_SESSION['USER_ID'] . "',extra) or IFNULL(find_in_set('" . $_SESSION['USER_ID'] . "',senderlist),find_in_set('" . $_SESSION['USER_ID'] . "',memberlist)))";
		if ($func_limit['管理员']) {
			$sub_sql = ' 1 ';
		} else {
			$sub_sql .= " or (find_in_set('" . $_SESSION['DEPT_ID'] . "',dept_id) and type=2) " .
			" or ( find_in_set('" . $_SESSION['AREA'] . "',ascription) and type=3) " .
			" or ( find_in_set('" . $_SESSION['AREA'] . "',ascription) and find_in_set('" . $_SESSION['DEPT_ID'] . "',dept_id) and type=4) " .
			"or ( find_in_set('" . $_SESSION['USER_ID'] . "',senderlist) and type=1 ) " .
			"or ( find_in_set('" . $_SESSION['USER_ID'] . "',extra))";
			$ringhtI=explode(',',$func_limit['管理部门']);
			if ($ringhtI&&is_array($ringhtI)) {
				foreach($ringhtI as $key =>$val){
					if($val){
						$sub_sqls .= "or find_in_set($val,dept_id) ";	
					}
					
				}
				$sub_sqls=ltrim($sub_sqls,'or');
				if($sub_sqls){
					$sub_sql .= " or (($sub_sqls) and type=2) ";	
				}
				
			}

		}
		$sql = "select a.*   from  sys_organizer_mailgroup as a
													  where	 $sub_sql  order by type asc";
		$query = $this->query($sql);

		$str = '';
		while (($row = $this->fetch_array($query)) != false) {
			if ($row['type'] == 1) {
				$type = '个人组';
			}
			elseif ($row['type'] == 2) {
				$data = $gl->GetDept($row['dept_id']);
				$type = '部门(' . $data[0]['DEPT_NAME'] . ')';
			}
			elseif ($row['type'] == 3) {
				$areaaName = $gl->get_area($row['ascription']);
				$type = '区域(' . $areaaName . ')';
			}elseif ($row['type'] == 4) {
				$data = $gl->GetDept($row['dept_id']);
				$areaaName = $gl->get_area($row['ascription']).'-'.$data[0]['DEPT_NAME'];
				$type = '混合(' . $areaaName . ')';
			}

			$memberlist = $row['memberlist'] ? $row['memberlist'] : '';
			$memberlist = $row['extra'] ? $memberlist . ',' . $row['extra'] : $memberlist;
			$cout = $memberlist ? count(array_filter(array_unique((array) explode(',', $memberlist)))) : 0;
			$str .= '<tr>';
			$str .= '<td>' . $row['id'] . '</td>';
			$str .= '<td>' . $row['groupname'] . '</td>';
			$str .= '<td>' . ($type ? $type : '所有部门') . '</td>';
			$str .= '<td class="wrap">' . ($row['senderlist'] ? $row['senderlist'] : '所组员') . '</td>';
			$str .= '<td>' . ($cout) . '</td>';
			$str .= '<td>' . $row['description'] . '</td>';
			$str .= '<td>';
			$str .= thickbox_link('查看', 'a', 'id=' . $row['id'] . '&key=' . $row['rand_key'], '查看：' . $row['groupname'] . ' 通讯组', null, 'detail', 600, 500) . ' | ';
			$str .= thickbox_link('修改', 'a', 'id=' . $row['id'] . '&key=' . $row['rand_key'], '修改：' . $row['groupname'] . ' 通讯组', null, 'edit', 600, 500) . ' | <a href="javascript:del(' . $row['id'] . ',\'' . $row['rand_key'] . '\');">删除</a>';
			$str .= '</td>';
			$str .= '</tr>';
		}

		return $str;
	}
	function model_indexdata() {
		global $func_limit;
		$gl = new includes_class_global();
		$_POST = mb_iconv($_POST);
		$page = $_POST['page'] ? $_POST['page'] : 1;
		$limit = $_POST['rows'] ? $_POST['rows'] : 20;
		$sidx = $_POST['sort'] ? $_POST['sort'] : 1;
		$sord = $_POST['order'] ? $_POST['order'] : " desc";
		$uname = trim($_POST['username']);
		$sdate = trim($_POST['sdate']);
		$edate = trim($_POST['edate']);
		$type = trim($_POST['type']);
		$flag = trim($_POST['flag']);
		$keyword = trim($_POST['keyword']);
		$sub_sql = "( find_in_set('" . $_SESSION['USER_ID'] . "',extra)
					or IF(senderlist='',find_in_set('" . $_SESSION['USER_ID'] . "',memberlist),find_in_set('" . $_SESSION['USER_ID'] . "',senderlist)))
			        or (find_in_set('" . $_SESSION['DEPT_ID'] . "',dept_id) and type=2)
					or ( find_in_set('" . $_SESSION['AREA'] . "',ascription) and type=3)
					or ( find_in_set('" . $_SESSION['AREA'] . "',ascription) and find_in_set('" . $_SESSION['DEPT_ID'] . "',dept_id) and type=4)  
					or ( find_in_set('" . $_SESSION['USER_ID'] . "',senderlist) and type=1 ) 
					or ( find_in_set('" . $_SESSION['USER_ID'] . "',extra))";
		if ($func_limit['管理员']) {
			$sub_sql = ' 1 ';
		}
		elseif ($func_limit['管理部门']) {
			$ringhtI=explode(',',$func_limit['管理部门']);
			if ($ringhtI&&is_array($ringhtI)) {
				foreach($ringhtI as $key =>$val){
					if($val){
						$sub_sqls .= "or find_in_set($val,dept_id) ";	
					}
					
				}
				$sub_sqls=ltrim('or',$sub_sqls);
				if($sub_sqls){
					$sub_sql .= " or (($sub_sqls) and type=2) ";	
				}
				
			}
			
			//$sub_sql .= " or (find_in_set(dept_id,'" . $func_limit['管理部门'] . "') and type=2) ";
		}
		if ($keyword) {
			$sub_sql .= " and (a.memberlist like '%" . $keyword . "%' or a.groupname like '%" . $keyword . "%' or a.senderlist like '%" . $keyword . "%' or a.extra like '%" . $keyword . "%' or a.description like '%" . $keyword . "%')";
		}
		if ($type) {
			$sub_sql .= " and a.type = '$type' ";
		}
		
		$sqls = "SELECT count(a.id)
		         FROM  sys_organizer_mailgroup as a
			     WHERE	$sub_sql  order by a.type asc ";
		$res = $this->_db->get_one($sqls);
		if (!empty ($res)) {
			$count = $res["count(a.id)"];
		}
		$page = $count > 0 && $page > ceil($count / $limit) ? $page = ceil($count / $limit) : $page;
		$start = $page ? $page == 1 || $start < 0 ? 0 : $limit * $page - $limit : 0;

		$sql = " SELECT * 
				 FROM  sys_organizer_mailgroup as a
				 WHERE	 $sub_sql  
				 ORDER BY " . $sidx . " " . $sord . " ,a.id desc LIMIT $start," . $limit . "";
				 
		$query = $this->_db->query($sql);
		$i = 0;
		if ($count) {
			$responce->total = $count;
		}

		//echo $sql;
		
		while (($row = $this->_db->fetch_array($query)) != false) {
		$sender='所有组员';
	           if ($row['type'] == 1) {
					$type = '个人组';
				}
				elseif ($row['type'] == 2) {
					$data = $gl->GetDept($row['dept_id']);
					$deptName='';
					if($data&&is_array($data)){
						foreach($data as $key =>$val){
							$deptName.=$val['DEPT_NAME'].',';
						}
					}
					$deptName=trim($deptName,',');
					$type = '部门(' . $deptName . ')';
				}
				elseif ($row['type'] == 3) {
					$areaaName = $gl->get_area($row['ascription']);
					$type = '区域(' . $areaaName . ')';
				}elseif ($row['type'] == 4) {
					$data = $gl->GetDept($row['dept_id']);
					$areaaName = $gl->get_area($row['ascription']).'-'.$data[0]['DEPT_NAME'];
					$type = '混合(' . $areaaName . ')';
			}

			$memberlist = $row['memberlist'] ? $row['memberlist'] : '';
			$memberlist = $row['extra'] ? $memberlist . ',' . $row['extra'] : $memberlist;
			$cout = $memberlist ? count(array_filter(array_unique((array) explode(',', $memberlist)))) : 0;
			
			if($row['senderlist']){
				  $sender=implode('、',$gl->GetUserName(array_filter(array_unique((array) explode(',', $row['senderlist'])))));
			}
            
			$responce->rows[$i] = (array (
				'id' => $row["id"],
				'groupname' => $row["groupname"],
				'type' =>un_iconv ( $type),
				'cout' => $cout,
				'sender' =>un_iconv ($sender),
				'description' => un_iconv($row["description"]),
				'flag' => in_array($_SESSION['USER_ID'],(array) explode(',', $row['senderlist']))||$func_limit['管理员']||(in_array($row['dept_id'],(array) explode(',', $func_limit['管理部门']))&&$row['type']==2)||(in_array($_SESSION['USER_ID'],(array) explode(',', $row['memberlist']))&&$row['senderlist']=='')?1:2,
				'key' => $row["rand_key"]
			));

			$i++;
		}

		return  json_encode($responce) ;
	}

	/**
	 * 添加
	 * @param $data
	 */
	function model_add($data) {
		$gl = new includes_class_global();
		$send_user_id = '';
		if ($data && is_array($data)) {
			if ($data['type'] == 1) {
				$user_id = explode(',', $data['UserId']);
				if ($data['unUserIds']) {
					$UnuserI = (array) explode(',', $data['unUserIds']);
					$unmemberlist = implode(',', $UnuserI);
				}
			}
			elseif ($data['type'] == 2) {
				$dept_id=$data['dept_id']?implode(',', (array) $data['dept_id']):0;
				$query = $this->query("
										select user_id 
										from user 
										where " . ($dept_id == 0 ? " 1 " : " DEPT_ID in($dept_id) ") . "  and HAS_LEFT=0 and DEL=0 and localizers<>2 
										order by dept_id desc
										");
				while (($row = $this->fetch_array($query)) != false) {

					$userI[] = $row['user_id'];
				}
				if ($data['deptUserId']) {
					$userI = array_merge((array) $userI, (array) explode(',', $data['deptUserId']));
					$extra = $data['deptUserId'];
				}
				if ($data['unDeptUserIds']) {
					$UnuserI = (array)explode(',', $data['unDeptUserIds']);
					$unmemberlist = implode(',', $UnuserI);
				}
				$user_id = $userI;

			}
			elseif ($data['type'] == 3) {
				if ($data['area_id']) {
					$area_id = $data['area_id'];
					$query = $this->query("
										select user_id 
										from user 
										where AREA='$area_id' and HAS_LEFT=0 and DEL=0 and localizers<>2 and userType=1
										order by user_id desc
										");
					while (($row = $this->fetch_array($query)) != false) {

						$userI[] = $row['user_id'];
					}
					if ($data['areaUserId']) {
						$userI = array_merge((array) $userI, (array) explode(',', $data['areaUserId']));
						$extra = $data['areaUserId'];
					}
					if ($data['unAreaUserIds']) {
						$UnuserI = (array)$data['unAreaUserIds'];
						$unmemberlist = implode(',', (array)$data['unAreaUserIds']);
					}
					$user_id = $userI;
				}
			}elseif ($data['type'] == 4) {
				if ($data['mix_area_id']) {
					$SonStr='';
					$area_id = $data['mix_area_id'];
					$dept_id=$data['mix_dept_id']?implode(',', (array) $data['mix_dept_id']):0;
					if($dept_id){
						$SonStr .= " AND DEPT_ID in($dept_id) ";
					}
		            if($area_id){
		            	$SonStr .= " AND AREA='$area_id'";
		            }
					$query = $this->query("select user_id 
											from user 
											where 1 $SonStr and HAS_LEFT=0 and DEL=0 and localizers<>2 and userType=1
											order by user_id desc
											");
					while (($row = $this->fetch_array($query)) != false) {

						$userI[] = $row['user_id'];
					}
					if ($data['mixUserId']) {
						$userI = array_merge((array) $userI, (array) explode(',', $data['mixUserId']));
						$extra = $data['mixUserId'];
					}
					if ($data['unMixUserIds']) {
						$UnuserI = (array)explode(',', $data['unMixUserIds']);
						$unmemberlist = implode(',', (array) $data['unMixUserIds']);
					}
					$user_id = $userI;
				}
			}
			
			$user_id = array_filter(array_unique((array) $user_id));
			if ($data['send_type'] == 2) {
				$send_user_id = explode(',', $data['SendId']);
				$send_user_id = array_filter(array_unique((array) $send_user_id));
			} else
				if ($data['send_type'] == 1) {
					$send_user_id = $user_id;
			}
			if($UnuserI&&is_array($UnuserI)){
				$UnuserI=array_filter(array_unique((array) $UnuserI));
				$UnUserList = implode(';',(array) $UnuserI);
			}
			if($data['unSendNames']){
				$unSendNames=array_filter(array_unique((array) explode(',', $data['unSendNames'])));
				$UnSendUser= implode(';',array_filter(array_unique((array) $unSendNames)));
			}
			
			$userIdI=array();
			$sendUserIdI=array();
			$userIdI=$user_id;
			$sendUserIdI=$send_user_id;
			//dele noUserID
			$noUserIdI=explode(',', $data['noUserId']);
			if($data['noUserId']){
				$noUserIdI=$this->model_get_onLineUserId($data['noUserId']);
				if($noUserIdI&&is_array($noUserIdI)){
					$user_id= array_diff($user_id,$noUserIdI);
					$send_user_id=array_diff($send_user_id,$noUserIdI);
				}
			}
			if ($send_user_id && $user_id && is_array($user_id) && is_array($send_user_id)) {
				$status = $this->Http->Post(Email_Server_Api_Url, array (
					'action' => 'AddGroup',
					'key' => authcode(oa_auth_key . ' ' . time(), 'ENCODE'),
					'groupname' => $data['groupname'],
					'domain' => 'dinglicom.com',
					'description' => $data['description'],
					'memberlist' => $UnUserList?implode(';', $gl->get_email($user_id)).';'.$UnUserList:implode(';', $gl->get_email($user_id)),
					'uright' => ($data['send_type']),
					'senderlist' => $UnSendUser?implode(';', $gl->get_email($send_user_id)).';'.$UnSendUser:implode(';', $gl->get_email($send_user_id))
				));
				if ($status == 1) {
					return $this->create(array (
						'groupname' => $data['groupname'],
						'type' => $data['type'],
						'send_type' => $data['send_type'],
						'dept_id' => $dept_id,
						'ascription' => $area_id,
						'extra' => $extra,
						'memberlist' => implode(',', $userIdI),
						'senderlist' => $data['send_type'] == 2 ? implode(',', $sendUserIdI) : '',
						'unmemberlist' => implode(',', (array)$UnuserI),
						'unsenderlist' => implode(',', (array)$unSendNames),
						'noUserIdlist' => implode(',', (array)$noUserIdI),
						'description' => $data['description']
					));
				}
			} else {
				return false;
			}

		}
	}
	/**
	 * 删除
	 * @param $id
	 * @param $key
	 */
	function model_del($id, $key) {
		$data = $this->find(array (
			'id' => $id,
			'rand_key' => $key
		));
		if ($data) {
			$status = $this->Http->Post(Email_Server_Api_Url, array (
				'action' => 'DeleteGroup',
				'key' => authcode(oa_auth_key . ' ' . time(), 'ENCODE'),
				'groupname' => $data['groupname'],
				'domain' => 'dinglicom.com'
			));
			if ($status == 1) {
				return $this->delete(array (
					'id' => $id,
					'rand_key' => $key
				));
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	/**
	 * 修改
	 * @param $id
	 * @param $key
	 */
	function model_edit($id, $key, $data) {
		$gl = new includes_class_global();
		$rs = $this->find(array (
			'id' => $id,
			'rand_key' => $key
		));
		if ($rs) {
			if ($data['type'] == 1) {
				$user_id=$this->model_get_onLineUserId($data['UserId']);
				if ($data['unUserIds']) {
					$UnuserI = (array) explode(',', $data['unUserIds']);
					$unmemberlist = implode(',', $UnuserI);
				}
			}
			elseif ($data['type'] == 2) {
				$SonDeptStr='';
				if ($data['dept_flag'] == 'P') {
					$dept_id =$SonDeptStr = $data['dept_id']?trim($data['dept_id'],','):0;
				} else {
					$dept_id =$SonDeptStr = $data['dept_id']?implode(',', (array) $data['dept_id']):0;
				}
				
				$query = $this->query("
										select user_id 
										from user 
										where " . ($dept_id == 0 ? " 1 " : " DEPT_ID in($SonDeptStr) ") . "  and HAS_LEFT=0 and DEL=0 and localizers<>2 
										order by dept_id desc
										");
				while (($row = $this->fetch_array($query)) != false) {

					$userI[] = $row['user_id'];
				}
				if ($data['deptUserId']) {
					$userI = array_merge((array) $userI, (array) explode(',', $data['deptUserId']));
					$extra = $data['deptUserId'];
				}
				if ($data['unDeptUserIds']) {
					$UnuserI = (array)explode(',', $data['unDeptUserIds']);
					$unmemberlist = implode(',', $UnuserI);
				}
				$user_id = $userI;
			}
			elseif ($data['type'] == 3) {
				if ($data['area_id']) {
					$area_id = $data['area_id'];
					$query = $this->query("
										select user_id 
										from user 
										where AREA='$area_id' and HAS_LEFT=0 and DEL=0 and localizers<>2 and userType=1
										order by user_id desc
										");
					while (($row = $this->fetch_array($query)) != false) {

						$userI[] = $row['user_id'];
					}
					if ($data['areaUserId']) {
						$userI = array_merge((array) $userI, (array) explode(',', $data['areaUserId']));
						$extra = $data['areaUserId'];
					}
					if ($data['unAreaUserIds']) {
						$UnuserI = (array)$data['unAreaUserIds'];
						$unmemberlist = implode(',', (array)$data['unAreaUserIds']);
					}
					$user_id = $userI;
				}
			}elseif ($data['type'] == 4) {
				if ($data['mix_area_id']) {
					$SonStr='';
					$area_id = $data['mix_area_id'];
					$dept_id =implode(',', (array) $data['mix_dept_id']);
					if($dept_id){
						$SonStr .= " AND DEPT_ID in($dept_id) ";
					}
		            if($area_id){
		            	$SonStr .= " AND AREA='$area_id'";
		            }
					$query = $this->query("select user_id 
											from user 
											where 1 $SonStr and HAS_LEFT=0 and DEL=0 and localizers<>2 and userType=1
											order by user_id desc
											");
					while (($row = $this->fetch_array($query)) != false) {

						$userI[] = $row['user_id'];
					}
					if ($data['mixUserId']) {
						$userI = array_merge((array) $userI, (array) explode(',', $data['mixUserId']));
						$extra = $data['mixUserId'];
					}
					if ($data['unMixUserIds']) {
						$UnuserI = (array)explode(',', $data['unMixUserIds']);
						$unmemberlist = implode(',', (array) $data['unMixUserIds']);
					}
					$user_id = $userI;
				}
			}
			$user_id = array_filter(array_unique((array) $user_id));
			if ($data['send_type'] == 2) {
				$send_user_id=$this->model_get_onLineUserId($data['SendId']);
				$send_user_id = array_filter(array_unique((array) $send_user_id));
			} else
				if ($data['send_type'] == 1) {
					$send_user_id = $user_id;
				}
			if($UnuserI&&is_array($UnuserI)){
				$UnuserI=array_filter(array_unique((array) $UnuserI));
				$UnUserList = implode(';',(array) $UnuserI);
			}
			if($data['unSendNames']){
				$unSendNames=array_filter(array_unique((array) explode(',', $data['unSendNames'])));
				$UnSendUser= implode(';',array_filter(array_unique((array) $unSendNames)));
			}
			$userIdI=array();
			$sendUserIdI=array();
			$userIdI=$user_id;
			$sendUserIdI=$send_user_id;
			//dele noUserID
			$noUserIdI=explode(',', $data['noUserId']);
			if($data['noUserId']){
				$noUserIdI=$this->model_get_onLineUserId($data['noUserId']);
				if($noUserIdI&&is_array($noUserIdI)){
					$user_id= array_diff($user_id,$noUserIdI);
					$send_user_id=array_diff($send_user_id,$noUserIdI);
				}
			}
			if ($send_user_id && $user_id && is_array($user_id) && is_array($send_user_id)) {
				$status = $this->Http->Post(Email_Server_Api_Url, array (
					'action' => 'ModifyGroup',
					'key' => authcode(oa_auth_key . ' ' . time(), 'ENCODE'),
					'groupname' => $rs['groupname'],
					'domain' => 'dinglicom.com',
					'description' => $data['description'],
					'memberlist' => $UnUserList?implode(';', $gl->get_email($user_id)).';'.$UnUserList:implode(';', $gl->get_email($user_id)),
					'uright' => ($data['send_type']),
					'senderlist' => $UnSendUser?implode(';', $gl->get_email($send_user_id)).';'.$UnSendUser:implode(';', $gl->get_email($send_user_id))
				));
			       
				if($extra){
					$extraI=$this->model_get_onLineUserId($extra);
				}
				if ($status == 1) {
					return $this->update(array (
						'id' => $id,
						'rand_key' => $key
					), array (
						'type' => $data['type'],
						'send_type' => $data['send_type'],
						'dept_id' => $dept_id,
						'ascription' => $area_id,
						'extra' =>implode(',',(array)$extraI),
						'memberlist' => implode(',',(array)$userIdI),
						'senderlist' => $data['send_type'] == 2 ? implode(',', $sendUserIdI) : '',
						'unmemberlist' => implode(',', (array)$UnuserI),
						'unsenderlist' => implode(',', (array)$unSendNames),
						'noUserIdlist' => implode(',', (array)$noUserIdI),
						'description' => $data['description']
					));
				}
			} else {
				return false;
			}

		} else {
			return false;
		}
	}

	function model_get_groupinfo($id, $key) {
		return $this->find(array (
			'id' => $id,
			'rand_key' => $key
		));
	}
	function model_get_onLineUserId($userIdStr){
		$userIdArr=array();
		if($userIdStr){
			$userIdStr=trim($userIdStr,',');
				$userIdStrI=explode(',',$userIdStr);
				if($userIdStrI&&is_array($userIdStrI)){
					$extraStr=implode("','",$userIdStrI);
					if($extraStr){
					   $query = $this->query("select user_id 
											from user 
											where 1  and HAS_LEFT=0 and DEL=0 and localizers<>2 and userType=1 and USER_ID in('$extraStr') 
											order by user_id desc
											");
						while (($row = $this->fetch_array($query)) != false) {
	
							$userIdArr[] = $row['user_id'];
						}	
					}
					
				}
			}
		return $userIdArr;	
	}
	
	function model_mailgroup_update($dataI){
		if($dataI&&is_array($dataI)){
				$sql = "SELECT  a.* 
					   FROM sys_organizer_mailgroup as a
					   WHERE ((find_in_set('" . $dataI['userId'] . "',memberlist) and type=1) or
							  (find_in_set('" . $dataI['deptId'] . "',dept_id) and type=2 )  or 
							  (find_in_set('" . $dataI['areaId'] . "',ascription) and type=3) or
							  (find_in_set('" . $dataI['areaId'] . "',ascription) and find_in_set('" . $dataI['deptId'] . "',dept_id) and type=4)
							 )
					    ORDER BY  type asc";
				$egroup_query = $this->db->query($sql);
				while (($row = $this->db->fetch_array($egroup_query)) != false) {
					$Egdata[] = $row;
				}
				if ($Egdata && is_array($Egdata)) {
					foreach ($Egdata as $key => $val) {
						if ($val['type'] == 1) {
							$data['UserId'] = $val['memberlist'];
							$data['unUserIds'] = $val['unsenderlist'];
						} else if ($val['type'] == 2) {
								$data['dept_id'] = $val['dept_id'];
								$data['deptUserId'] = $val['extra'];
								$data['dept_flag'] = 'P';
								$data['unDeptUserIds'] = $val['unmemberlist'];
						} else if ($val['type'] == 3) {
									$data['area_id'] = $val['ascription'];
									$data['areaUserId'] = $val['extra'];
									$data['unAreaUserIds'] = $val['unmemberlist'];
						}else if ($val['type'] == 4) {
									$data['dept_id'] = $val['dept_id'];
									$data['area_id'] = $val['ascription'];
									$data['mixUserId'] = $val['extra'];
									$data['unMixUserIds'] = $val['unmemberlist'];
						}
						$data['id']=$val['id'];
						$data['rand_key']=$val['rand_key'];
						$data['type'] = $val['type'];
						$data['send_type'] = $val['send_type'];
						$data['SendId'] = $val['senderlist'];
						$data['description'] = $val['description'];
						$data['unSendNames'] = $val['unsenderlist'];
						$data['noUserIdlist'] = $val['noUserIdlist'];
						$flag='';
						$flag = $this->model_edit($data['id'], $data['rand_key'], $data);
						if ($flag) {
							$groupName[]=$val['groupname'];
						}
					}
				}
		}
		return $groupName;
	}
	
	
	 /**
     * 数据
     */
    function model_deptData(){
    	global $func_limit;
        $data=array();
        if ($func_limit['管理员']) {
			$sub_sql = ' and   1  ';
		}elseif($func_limit['管理部门']){
			$sub_sql = " and  DEPT_ID IN (".trim($func_limit['管理部门'],',').")  ";
		} 
        
        
        $sidI=explode(',',$sids=$_GET['sId']);
        $sql="SELECT DEPT_ID,DEPT_NAME,PARENT_ID FROM department WHERE DelFlag=0  $sub_sql  ORDER BY DEPT_ID";
        $query=$this->_db->query($sql);
        $data=array();
        while($row=$this->_db->fetch_array($query)){
        	$data[$row['DEPT_ID']]['DEPT_ID']=$row['DEPT_ID'];
        	$data[$row['DEPT_ID']]['DEPT_NAME']=$row['DEPT_NAME'];
        	$data[$row['DEPT_ID']]['PARENT_ID']=$row['PARENT_ID'];
        }
        $dataI=$this->model_getSub($data,0,$sidI);
       $dataI = array_merge(array(array('id'=>'0','text'=>'所有')), $dataI); 
        echo json_encode(un_iconv($dataI));
    }
    
    function model_getSub($data,$pid=0,$checkedArr){
    	if(is_array($data)&&$data){
        	foreach($data as $key =>$val){
        		if($pid==0&&$val['PARENT_ID']==0){
        			$subData=$this->model_getSub($data,$val['DEPT_ID']);
        			if($subData){
        				if(in_array($val['DEPT_ID'],$checkedArr)){
        					$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"state"=>"closed","children"=>$subData,"checked"=>true);
        				 }else{
        			 		$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"state"=>"closed","children"=>$subData);	
        			 	}	
        				
        			}else{
        				if(in_array($val['DEPT_ID'],$checkedArr)){
        					$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"checked"=>true);
        			 	}else{
        			 		$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME']);
        			 	}	
        			}
        			
        		}else if($pid==$val['PARENT_ID']){
					$subData=$this->model_getSub($data,$val['DEPT_ID']);
        			if($subData){
        				if(in_array($val['DEPT_ID'],$checkedArr)){
        					$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"state"=>"closed","children"=>$subData,"checked"=>true);
        				 }else{
        			 		$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"state"=>"closed","children"=>$subData);	
        			 	}	
        				
        			}else{
        				if(in_array($val['DEPT_ID'],$checkedArr)){
        					$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"checked"=>true);
        			 	}else{
        			 		$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME']);
        			 	}	
        			}
					
        		     //if(in_array($val['DEPT_ID'],$checkedArr)){
        				//$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"checked"=>true);
        			 //}else{
        			 	//$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME']);
        			 //}	
        			
        		}
        		
        	}
        	return $dataI;
        }
    	
    }
	
}
?>