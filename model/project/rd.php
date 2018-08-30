<?php
class model_project_rd extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'project_rd';
	}
	
	function GetDataList($condition=NULL,$page=null,$rows=null)
	{
		if ($page && $rows && !$this->num)
		{
			$condition = trim($condition) != "" ? $condition." and is_delete=0" : $condition." is_delete=0 ";
			$this->num = $this->GetCount ( str_replace ( 'a.', '', $condition ) );
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		$query = $this->query("
								select 
									a.*,b.project_name as ipo_name,c.project_name as zf_name,d.dept_name,e.typename as project_type_name
								from
									$this->tbl_name as a
									left join project_ipo as b on  b.id=a.ipo_id
									left join project_ipo as c on c.id=a.zf_id
									left join department as d on d.dept_id=a.dept_id
									left join project_rd_type as e on e.id = a.project_type 
								where 
									a.is_delete=0  ".($condition ? "and  $condition" : "")."
								order by a.id desc
								".($limit ? "limit $limit" : '')."

		");
									
//							echo 'select 
//									a.*,b.project_name as ipo_name,c.project_name as zf_name,d.dept_name,e.typename as project_type_name
//								from
//									$this->tbl_name as a
//									left join project_ipo as b on  b.id=a.ipo_id
//									left join project_ipo as c on c.id=a.zf_id
//									left join department as d on d.dept_id=a.dept_id
//									left join project_rd_type as e on e.id = a.project_type 
//								where 
//									a.is_delete=0  '.($condition ? "and  $condition" : "").'
//								order by a.id desc';
		$data = array();
		$user_data = $this->get_username();
		while (($rs = $this->fetch_array($query)) != false)
		{
			$manager = $rs['manager'] ? $this->get_username_list($user_data, $rs['manager']) : '';
			$assistant = $rs['assistant'] ? $this->get_username_list($user_data, $rs['assistant']) : '';
			$developer = $rs['developer'] ? $this->get_username_list($user_data, $rs['developer']) : '';
			$rs['manager_name'] = $manager ? implode(',', $manager) : '';
			$rs['assistant_name'] = $assistant ? implode(',', $assistant) : '';
			$rs['developer_name'] = $developer ? implode(',', $developer) : '';
			
			$zfList = explode(',', $rs['zf_id']);
			
			
			$zfNameStr = '';
			foreach($zfList as $value){
				if($value != "" and $value != 0){
					$ipoData = $this->getIpoName($value);
					
					
					$zfNameStr .= $ipoData['project_name'].",";
					
				}
				
			}
			if(strlen($zfNameStr) > 0){
				$zfNameStr = substr($zfNameStr, 0, strlen($zfNameStr) - 1);
			}
			$rs['zf_name'] = $zfNameStr;
			
			$data[] = $rs;
		}
		return $data;
		
	}
	
	/* 项目变更记录 查询数据列表 */
	function GetRdChangeDataList($condition=NULL,$page=null,$rows=null)
	{ 
		if ($page && $rows && !$this->num){ 
			$sql = "SELECT COUNT(*) as sp_counter FROM project_rd_change ";
			$result = $this->_db->getArray($sql);
			$this->num = $result[0][sp_counter];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}

		/*$id=array();
		$change_query = $this->query(" select rd_id from project_rd_change ");
		while (($change_id = $this->fetch_array($change_query)) != false){$id[] = $change_id[rd_id];}

		$rd_query = $this->query(" select id from $this->tbl_name where is_delete=0 ");
		while (($rd = $this->fetch_array($rd_query)) != false)
		{ 
			if(in_array($rd[id],$change_id)){
				$change_id = array_flip($change_id);
				unset($change_id[$rd[id]]);
				$change_id = array_flip($change_id);
			}else{
				$this->query(" INSERT INTO from project_rd_change(`rd_id`,`name`,) where is_delete=0 ");
				$SQL = "INSERT INTO 
				project_rd_action(`account`, `percent`, `project`, `addtime`, `leavetime`, `function`)
				 VALUES('{$member}', '{$percent}', {$projectId}, '{$addTime}', '{$leaveTime}', '{$functionId}')";
			}
		
		}*/
		$limit = '';
		$data = array();
		$change_query = $this->query(" 
										select 
											id, name, old_name, number, status, remarks 
										from 
											project_rd_change 
										".($condition ? 'where'.$condition :'').($limit ? "limit $limit" : '')
									);
		$i=1;
		while (($change_d = $this->fetch_array($change_query)) != false)
		{ 
			$change_d['num'] = $i++;
			$data[] = $change_d; 
		}

		return $data;
		
	}
	public function add_change($row)
	{
		if(!is_array($row))return FALSE;
		if(empty($row))return FALSE;
		foreach($row as $key => $value){
			$cols[] = "`".$key."`";
			$vals[] = "'".nl2br($value)."'";
		}
		$col = join(',', $cols);
		$val = join(',', $vals);

		$sql = "INSERT INTO project_rd_change ({$col}) VALUES ({$val})";
		
		if( FALSE != $this->query($sql) ){ // 获取当前新增的ID
			$newinserid = $this->_db->insert_id();
			
			if( $newinserid ){
				return $newinserid;
			}else{
				return array_pop( $this->find($row, "{$this->pk} DESC",$this->pk) );
			}
		}
		return FALSE;
	}
	public function Edit_change($row, $id)
	{
		if(empty($row))return FALSE;
		$strArray = array('<br>' => '','<br/>'=>'','<br />'=>'');
		foreach($row as $key => $value){ 
			$value = strtr($value,$strArray);
			$vals[] = "`$key` = '".nl2br($value)."'"; 
		}
		$values = join(", ",$vals);
		if($id){ 
			$sql = "UPDATE project_rd_change SET {$values}  where id = {$id}";
			return $this->query($sql);
		}
		return false;
	}
	public function Del_change($id)
	{
		if($id){
			$sql = "DELETE FROM project_rd_change where id = {$id}";
			return $this->query($sql);
		}
		return false;
	}
	/**
	 * 获取所有用户名数组，KEY=USER_ID,VALUE=USER_NAME
	 */
	function get_username($key_type='user_id')
	{
		$query = $this->query("select user_id,user_name from user");
		$data = array();
		if ($key_type == 'user_id')
		{
			while (($rs = $this->fetch_array($query))!=false) {
				$data[$rs['user_id']] = $rs['user_name'];
			}
		}else{
			while (($rs = $this->fetch_array($query))!=false) {
					$data[$rs['user_name']] = $rs['user_id'];
				}
		}
		return $data;
	}
	/**
	 * 按用户ID返回用户名数组
	 * @param unknown_type $data
	 * @param unknown_type $userid
	 */
	function get_username_list($data,$userid)
	{
		if ($data && $userid)
		{
			$arr = explode(',',$userid);
			$username = array();
			if ($arr)
			{
				foreach ($arr as $val)
				{
					$username[$val] = $data[$val];
				}
			}
			return $username;
		}else{
			return false;
		}
	}
	/**
	 * 获取用户姓名返回用户ID数组
	 * 
	 * @param unknown_type $data
	 * @param unknown_type $username_str
	 */
	function get_userid_str($data,$username_str)
	{
	if ($data && $username_str)
		{
			$arr = explode(',',$username_str);
			$userid = array();
			if ($arr)
			{
				foreach ($arr as $val)
				{
					$val = trim($val);
					if ($val) $userid[$val] = $data[$val];
				}
			}
			return $userid;
		}else{
			return false;
		}
	}
	
	/**
	 * 
	 * get user by dept id
	 * $deptId
	 * Return list 
	 */
	function getUserByDept($deptId){
		$SQL = "select user_id,user_name from user where dept_id = {$deptId} ORDER BY user_name ASC ";
		$query = $this->query($SQL);
		$data = array();
		while (($rs = $this->fetch_array($query))!=false) {
			$data[$rs['user_id']] = $rs['user_name'];
		}
		return $data;
	}
	
	function addMember($member, $addTime = '', $leaveTime = '', $percent = '', $projectId, $functionId = ''){
		$SQL = "INSERT INTO 
				project_rd_action(`account`, `percent`, `project`, `addtime`, `leavetime`, `function`)
				 VALUES('{$member}', '{$percent}', {$projectId}, '{$addTime}', '{$leaveTime}', '{$functionId}')";
		$query = $this->query($SQL);
	}
	
	function UpdateMember($member, $addTime, $leaveTime, $percent, $projectId, $functionId = ''){
		$SQL = "UPDATE `project_rd_action` SET "
			." `percent` = '".$percent."',"
			." `addtime` = '".$addTime."',"
			." `leavetime` = '".$leaveTime."',"
			." `function` = '".$functionId."'"
			." WHERE "
			." `account` = '".$member."'"
			." AND `project` = '".$projectId."'";
			$query = $this->query($SQL);
	}
	
	function isExist($member, $projectId){
		$SQL = "SELECT COUNT(*) AS sp_counter FROM `project_rd_action`"
			." WHERE "
			." `account` = '".$member."'"
			." AND `project` = '".$projectId."'";
			$result = $this->_db->getArray($SQL);
			return $result[0]['sp_counter'];
	}
	
	function getRealNameByAccount($realName){
		$SQL = "SELECT `user_name` FROM user WHERE `user_id` = '".$realName."'";
		$result = $this->_db->getArray($SQL);
		$result = un_iconv($result);
		
		return isset($result[0]['user_name'])?$result[0]['user_name']:"";
	}
	
	function getMemberInfo($account, $project){
		$SQL = "SELECT * FROM `project_rd_action`"
			." WHERE "
			." `account` = '".$account."'"
			." AND `project` = '".$project."'";
			$result = $this->_db->getArray($SQL);
			return $result[0];
	}
	
	function getIpoName($id){
		$SQL = "SELECT * from project_ipo WHERE id = {$id}";
		$result = $this->_db->getArray($SQL);
		return $result[0];
	}
	
	function getMemberList($project){
		$SQL = "SELECT * FROM `project_rd_action` "
			." WHERE "
			." `project` = '".$project."'"
			//." AND `deleted` = 0 "
			." ORDER BY leavetime,account ASC";
		$query = $this->query($SQL);
		while (($rs = $this->fetch_array($query))!=false) {
			$data[] = $rs;
		}
		return $data;
	}
	
	function getMemberPercent($account,$id,$condition){
		$SQL = "SELECT sum(percent)as total FROM `project_rd_action` "
			." WHERE "
			." `account` = '".$account."'"
			." AND `id` <> '".$id."'"
			.$condition;

		$result = $this->_db->getArray($SQL);
		return $result[0]['total'];
	}
	
	function getMemberListExt($project){
		$today = date('Y-m-d H:i:s');
		$SQL = "SELECT * FROM project_rd_action as p, USER as u "
			." WHERE "
			." u.user_id = p.account "
			." AND p.project = '".$project."' AND (p.leavetime = '0000-00-00 00:00:00' OR p.leavetime > '{$today}' )"
			//." AND `deleted` = 0 "
			." ORDER BY p.leavetime,p.account ASC";
		$query = $this->query($SQL);
		while (($rs = $this->fetch_array($query))!=false) {
			$data[] = $rs;
		}
		return $data;
	}
	
	function getTypeForFunction($functionId){
		$description = "";
		if($functionId == 'dev'){
			$description = un_iconv('开发');
		}else if($functionId == 'qa'){
			$description = un_iconv('测试');
		}else if($functionId == 'manage'){
			$description = un_iconv('管理');
		}else if($functionId == 'demand'){
			$description = un_iconv('需求');
		}
		
		return $description; 
	}
	
	function removeMembers($account, $project){
		$SQL = "delete from project_rd_action where account = '{$account}' and project = '{$project}'";
		//$SQL = "update project_rd_action set deleted = 1 where account = '{$account}' and project = '{$project}'";
		
		$query = $this->query($SQL);
		return $query;
	}
	
	function deptTypeOption($isEdit, $value){
		$str = '';
		if($isEdit){
			if($value == ''){
				$str .= '<option value ="" selected ></option>';
			}else{
				$str .= '<option value ="" ></option>';
			}
			
			if($value == 'dev'){
				$str .= '<option value="dev" selected >'.un_iconv('开发').'</option>';
			}else{
				$str .= '<option value="dev" >'.un_iconv('开发').'</option>';
			}
			
			if($value == 'qa'){
				$str .= '<option value="qa" selected >'.un_iconv('测试').'</option>';
			}else{
				$str .= '<option value="qa" >'.un_iconv('测试').'</option>';
			}
			
			if($value == 'manage'){
				$str .= '<option value="manage" selected >'.un_iconv('管理').'</option>';
			}else{
				$str .= '<option value="manage" >'.un_iconv('管理').'</option>';
			}
			
			if($value == 'demand'){
				$str .= '<option value="demand" selected >'.un_iconv('需求').'</option>';
			}else{
				$str .= '<option value="demand" >'.un_iconv('需求').'</option>';
			}
			
		}else{
			$str .= '
				<option value ="" ></option>
 				<option value="dev" >'.un_iconv('开发').'</option>
 				<option value="qa" >'.un_iconv('测试').'</option>
 				<option value="manage" >'.un_iconv('管理').'</option>
 				<option value="demand" >'.un_iconv('需求').'</option>
				';
		}
		return $str;
	}
	
	function conditions(){
		$condition = '';
		if($_REQUEST['export_keyword'] != ''){
			$condition .= "a.name like '%".trim($_REQUEST['export_keyword'])."%'";
		}
		
		if($_REQUEST['export_projectType'] != ''){
			if($condition != ''){
				$condition .= " AND a.project_type = '".trim($_REQUEST['export_projectType'])."'";
			}else{
				$condition .= " a.project_type = '".trim($_REQUEST['export_projectType'])."'";
			}
		}
		
		if($_REQUEST['export_stage'] != ''){
			if($condition != ''){
				$condition .= " AND a.stage = '".trim($_REQUEST['export_stage'])."'";
			}else{
				$condition .= " a.stage = '".trim($_REQUEST['export_stage'])."'";
			}
		}
		
		if($_REQUEST['export_dept'] != ''){
			if($condition != ''){
				$condition .= " AND a.dept_id = '".trim($_REQUEST['export_dept'])."'";
			}else{
				$condition .= " a.dept_id = '".trim($_REQUEST['export_dept'])."'";
			}
		}
		
		if($_REQUEST['export_ipoType'] != ''){
			if($condition != ''){
				$condition .= " AND a.ipo_id = '".trim($_REQUEST['export_ipoType'])."'";
			}else{
				$condition .= " a.ipo_id = '".trim($_REQUEST['export_ipoType'])."'";
			}
		}
		
		if($_REQUEST['export_zfType'] != ''){
			if($condition != ''){
				$condition .= " AND a.zf_id like '%".trim($_REQUEST['export_zfType'])."%'";
			}else{
				$condition .= " a.zf_id like '%".trim($_REQUEST['export_zfType'])."%'";
			}
		}
		
		if($_REQUEST['export_projectStatus'] != ''){
			if($_REQUEST['export_projectStatus'] != 'all'){
				if($condition != ''){
					$condition .= " AND a.status = '".trim($_REQUEST['export_projectStatus'])."'";
				}else{
					$condition .= " a.status = '".trim($_REQUEST['export_projectStatus'])."'";
				}
			}
			
		}else{
			if($condition != ''){
				$condition .= " AND a.status != '3' AND a.status != '6' ";
			}else{
				$condition .= " a.status != '3' AND a.status != '6' ";
			}
		}
		return $condition;
	}
	
	function export($condition){
		
		$SQL = "
				select 
					a.*,b.project_name as ipo_name,c.project_name as zf_name,d.dept_name,e.typename as project_type_name
				from
					$this->tbl_name as a
					left join project_ipo as b on  b.id=a.ipo_id
					left join project_ipo as c on c.id=a.zf_id
					left join department as d on d.dept_id=a.dept_id
					left join project_rd_type as e on e.id = a.project_type 
				where 
					a.is_delete=0  ".($condition ? "and  $condition" : "")."
				order by a.id desc
		";
		
		$query = $this->query($SQL);
		
		$user_data = $this->get_username();
		while (($rs = $this->fetch_array($query)) != false){
			$manager = $rs['manager'] ? $this->get_username_list($user_data, $rs['manager']) : '';
			$assistant = $rs['assistant'] ? $this->get_username_list($user_data, $rs['assistant']) : '';
			$developer = $rs['developer'] ? $this->get_username_list($user_data, $rs['developer']) : '';
			$rs['manager_name'] = $manager ? implode(',', $manager) : '';
			$rs['assistant_name'] = $assistant ? implode(',', $assistant) : '';
			$rs['developer_name'] = $developer ? implode(',', $developer) : '';
			
			$zfList = explode(',', $rs['zf_id']);
			
			
			$zfNameStr = '';
			foreach($zfList as $value){
				if($value != "" and $value != 0){
					$ipoData = $this->getIpoName($value);
					
					
					$zfNameStr .= $ipoData['project_name'].",";
					
				}
				
			}
			if(strlen($zfNameStr) > 0){
				$zfNameStr = substr($zfNameStr, 0, strlen($zfNameStr) - 1);
			}
			$rs['zf_name'] = $zfNameStr;
			
			$data[] = $rs;
		}
		
		if(count($data) > 0){
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			$PHPExcel = new PHPExcel ();
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			$objWriter = new PHPExcel_Writer_Excel5 ( $PHPExcel );
			
			$PHPExcel->setActiveSheetIndex ( 0 );
			$objActSheet = $PHPExcel->getActiveSheet ();
			
			/**
			 * header
			 */
			$objActSheet->setTitle ( un_iconv ( '研发项目' ) );
			
			$objActSheet->setCellValue ( 'A1', un_iconv ( '唯一ID ' ) );
			$objActSheet->getColumnDimension ( 'A' )->setWidth ( 10 );
			$objActSheet->setCellValue ( 'B1', un_iconv ( '项目名称 ' ) );
			$objActSheet->getColumnDimension ( 'B' )->setWidth ( 25 );
			$objActSheet->setCellValue ( 'C1', un_iconv ( '项目编码' ) );
			$objActSheet->getColumnDimension ( 'C' )->setWidth ( 25 );
			
			$objActSheet->setCellValue ( 'D1', un_iconv ( '项目阶段' ) );
			$objActSheet->getColumnDimension ( 'D' )->setWidth ( 15 );
			
			$objActSheet->setCellValue ( 'E1', un_iconv ( '募投项目' ) );
			$objActSheet->getColumnDimension ( 'E' )->setWidth ( 30 );
			$objActSheet->setCellValue ( 'F1', un_iconv ( '政府项目' ) );
			$objActSheet->getColumnDimension ( 'F' )->setWidth ( 25 );
			$objActSheet->setCellValue ( 'G1', un_iconv ( '项目类型' ) );
			$objActSheet->getColumnDimension ( 'G' )->setWidth ( 15 );
			
			$objActSheet->setCellValue ( 'H1', un_iconv ( '合同/商机编号' ) );
			$objActSheet->getColumnDimension ( 'H' )->setWidth ( 20 );
			
			$objActSheet->setCellValue ( 'I1', un_iconv ( '立项时间' ) );
			$objActSheet->getColumnDimension ( 'I' )->setWidth ( 15 );
			$objActSheet->setCellValue ( 'J1', un_iconv ( '项目经理' ) );
			$objActSheet->getColumnDimension ( 'J' )->setWidth ( 15 );
			$objActSheet->setCellValue ( 'K1', un_iconv ( '所属部门' ) );
			$objActSheet->getColumnDimension ( 'K' )->setWidth ( 15 );
			$objActSheet->setCellValue ( 'L1', un_iconv ( '项目状态' ) );
			$objActSheet->getColumnDimension ( 'L' )->setWidth ( 10 );
			$objActSheet->setCellValue ( 'M1', un_iconv ( '关联产品' ) );
			$objActSheet->getColumnDimension ( 'M' )->setWidth ( 25 );
			$objActSheet->setCellValue ( 'N1', un_iconv ( '项目成员' ) );
			$objActSheet->getColumnDimension ( 'N' )->setWidth ( 40 );
			$objActSheet->setCellValue ( 'O1', un_iconv ( '项目描述' ) );
			$objActSheet->getColumnDimension ( 'O' )->setWidth ( 40 );
			$objActSheet->setCellValue ( 'P1', un_iconv ( '结项时间' ) );
			$objActSheet->getColumnDimension ( 'P' )->setWidth ( 15 );
			
			/**
			 * header style
			 */
			$objActSheet->getStyle ( 'A1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
			$objActSheet->getStyle ( 'A1' )->getFill ()->getStartColor ()->setRGB ( 'c0c0c0' );
			$objActSheet->getStyle ( 'A1' )->getFont ()->setBold ( true );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setWrapText ( true );
			$A1Style = $objActSheet->getStyle ( 'A1' );
			$objBorderA1 = $A1Style->getBorders ();
			$objBorderA1->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
			$objBorderA1->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objActSheet->duplicateStyle ( $A1Style, 'A1:P1' );
			$objActSheet->getRowDimension ( '1' )->setRowHeight ( 25 );
			
			/**
			 * set data
			 * @var unknown_type
			 */
			
			$i = 2;
			foreach($data as $key => $record){
				$objActSheet->setCellValue ( 'A' . $i, un_iconv($record['id']));
				$objActSheet->setCellValue ( 'B' . $i, un_iconv($record['name']));
				$objActSheet->setCellValue ( 'C' . $i, un_iconv($record['number']));
				
				$stage = "";
				if($record['stage'] == 1){
					$stage = "研发阶段";
				}elseif($record['stage'] == 2){
					$stage = "开发阶段";
				}
				
				$objActSheet->setCellValue ( 'D' . $i, un_iconv($stage));
				
				$objActSheet->setCellValue ( 'E' . $i, un_iconv($record['ipo_name']));
				
				$objActSheet->setCellValue ( 'F' . $i, un_iconv($record['zf_name']));
				$objActSheet->getStyle('F' . $i)->getAlignment()->setWrapText(true);
				
				$objActSheet->setCellValue ( 'G' . $i, un_iconv($record['project_type_name']));
				
				$objActSheet->setCellValue ( 'H' . $i, un_iconv($record['extra']));
				
				$objActSheet->setCellValue ( 'I' . $i, $record['begin_date']);
				$objActSheet->setCellValue ( 'J' . $i, un_iconv($this->getField('USER_NAME', 'account', 'USER_ID = "'.$record['manager'].'"', 'USER')));
				
				$objActSheet->setCellValue ( 'K' . $i, un_iconv($record['dept_name']));
				$objActSheet->setCellValue ( 'L' . $i, un_iconv($this->exchangeStatus($record['status'])));
				
				
				$productNameStr = "";
				if(!empty($record['product_id_str'])){
					$referProducts = explode(',', $record['product_id_str']);
					if(count($referProducts) > 0){
						foreach ($referProducts as  $productId){
							//$productNameStr .= $this->getProduct($productId).",";
							$productNameStr .= $this->getField('product_name', 'name', 'id = "'.$productId.'"', 'product').",";
						}
						if(strlen($productNameStr) > 0){
							$productNameStr = substr($productNameStr, 0, strlen($productNameStr) - 1);
						}
					}
				}
				$objActSheet->setCellValue ( 'M' . $i, un_iconv($productNameStr));
				$objActSheet->getStyle('M' . $i)->getAlignment()->setWrapText(true);
				
				$memberStr = "";
				$members = $this->getMemberListExt($record['id']);
				if(count($members) > 0){
					foreach ($members as $member){
						$memberStr .= $member['USER_NAME'].",";
					}
					if (strlen($memberStr) > 0){
						$memberStr = substr($memberStr, 0, strlen($memberStr) - 1);
					}
				}
				$objActSheet->setCellValue ( 'N' . $i, un_iconv($memberStr));
				$objActSheet->getStyle('N' . $i)->getAlignment()->setWrapText(true);
				
				$objActSheet->setCellValue ( 'O' . $i, un_iconv($record['description']));
				$objActSheet->getStyle('O' . $i)->getAlignment()->setWrapText(true);
				
				$objActSheet->setCellValue ( 'P' . $i, $record['end_date']);
				$i++;
			}
			/**
			 * output
			 */
			$fileName = '研发项目';
			header("Content-type: text/html;charset=GBK");
			header("Content-Type: application/force-download");  
			header("Content-Type: application/octet-stream");  
			header("Content-Type: application/download");
			header("Content-type: application/vnd.ms-excel");
			header('Content-Disposition:inline;filename="'.$fileName.date('Y-m-d').'.xls'.'"');  
			header("Content-Transfer-Encoding: binary");  
			//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
			header("Pragma: no-cache");  
			$objWriter->save('php://output');
		}
	}
	
	private function exchangeStatus($var, $isId = true){
		$str = '';
		if($isId){
			if($var == 0){
				$str = '进行中';
			}elseif($var == 1){
				$str = '未开始';
			}elseif($var == 2){
				$str = '已完成';
			}elseif($var == 3){
				$str = '已取消';
			}elseif($var == 4){
				$str = '未审核';
			}elseif($var == 5){
				$str = '已审核';
			}elseif($var == 6){
				$str = '已关闭';
			}
		}else{
			if($var == '进行中'){
					$str = '0';
				}elseif($var == '未开始'){
					$str = '1';
				}elseif($var == '已完成'){
					$str = '2';
				}elseif($var == '已取消'){
					$str = '3';
				}elseif($var == '未审核'){
					$str = '4';
				}elseif($var == '已审核'){
					$str = '5';
				}elseif($var == '已关闭'){
					$str = '6';
				}
		}
		
		return $str;
	}
	
	/*
	function getProduct($id){
		$SQL = "SELECT product_name as name FROM product WHERE id = '{$id}'";
		$result = $this->_db->getArray($SQL);
		return $result[0]['name'];
	}
	
	function getDept($id){
		$SQL = "SELECT DEPT FROM department WHERE DEPT_ID = '{$id}'";
		
	}
	*/
	function getField($fieldName, $alias, $conditions, $tableName){
		$str = "";
		
		$SQL = "SELECT {$fieldName} as {$alias} FROM {$tableName} WHERE {$conditions} ";
		$result = $this->_db->getArray($SQL);
		if(isset($result[0][$alias]) and !empty($result[0][$alias]) and !is_array($result[0][$alias])){
			$str = $result[0][$alias];
		}
		
		return $str;
	}
	
	
	
	function import(){
		$flag = -1;
		$filename = $_FILES['upfile']['tmp_name'];
		require_once WEB_TOR . 'includes/classes/PHPExcel.php'; //包含类   
		require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
		require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';
		$PHPExcel = new PHPExcel ();
		$PHPReader = new PHPExcel_Reader_Excel2007 ( $PHPExcel );
		if (! $PHPReader->canRead ( $filename )){
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
		}
		
		if ($PHPReader->canRead ( $filename )){
			$newRecordList = array();
			$Excel = $PHPReader->load ( $filename );
			$countnum = $Excel->getSheetCount ();
			$this->_db->ping ();
			
			for($i = 0; $i < $countnum; $i ++){
				$errorList = array();
				
				if ($i == 0){ //添加类别和设备
					$data = $Excel->getSheet ( $i )->toArray ();
					$data = mb_iconv ( $data );
					
					if(count($data) > 0){
						
						for ($i = 1; $i < count($data); $i++){
							
							$record['name'] = $data[$i][1];//项目名称
							$record['number'] = $data[$i][2];//项目编码
							
							$stage = 0;
							if(trim($data[$i][3]) == "研究阶段"){
								$stage = 1;
							}elseif(trim($data[$i][3]) == "开发阶段"){
								$stage = 2;
							}
							$record['stage'] = $stage;
							
							$record['ipo_id'] = $this->getField('id', 'id', 'project_name = "'.$data[$i][4].'" AND type = "1"', 'project_ipo');//募投项目
							
							
							
							
							$zfStr = "";
							if(!empty($data[$i][5])){
								$zfs = explode(",", $data[$i][5]);
								
								if(is_array($zfs) and count($zfs) > 0){
									foreach ($zfs as $zfName){
										if(!empty($zfName)){
											$zfStr .= $this->getField('id', 'id', 'project_name = "'.$zfName.'" AND type = "2"', 'project_ipo').",";//政府项目
										}
									}
									
									if(strlen($zfStr) > 0){
										$zfStr = substr($zfStr, 0, strlen($zfStr) - 1);
									}
								}
							}
							
							$record['zf_id'] = $zfStr;
							
							
							$record['project_type'] = $this->getField('id', 'id', 'typename = "'.$data[$i][6].'"', 'project_rd_type');//项目类型
							
							$record['extra'] = $data[$i][7];//合同\商机编号
							
							
							$record['begin_date'] = $data[$i][8];//立项时间
							
							$record['manager'] = $this->getField('USER_ID', 'account', 'USER_NAME = "'.$data[$i][9].'"', 'USER');//项目经理
							
							$record['dept_id'] = $this->getField('DEPT_ID', 'id', 'DEPT_NAME = "'.$data[$i][10].'"', 'department');//所属部门
							
							$record['status'] = $this->exchangeStatus($data[$i][11], false);//项目状态
							
							$productStr = "";
							if(!empty($data[$i][12])){
								$products = explode(",", $data[$i][12]);
								if(is_array($products) and count($products) > 0){
									foreach ($products as $productId){
										$productStr .= $this->getField('id', 'id', 'product_name = "'.$productId.'"', 'product').",";//所属产品
									}
									if(strlen($productStr) > 0){
										$productStr = substr($productStr, 0, strlen($productStr) - 1);
									}
								}
							}
							$record['product_id_str'] = $productStr;//关联产品
							
							//$record['developer'] = $data[$i][13];//项目成员
							$record['description'] = $data[$i][14];//项目描述
							
							if(!empty($data[$i][0])){
								$id = $data[$i][0];
								$this->Edit($record, $id);
							}else{
								$new_id = $this->Add($record);
							}
						}
						
					}
					
				}
			}
			$flag = 1;
		}
		return $flag;
	}
	
	function canImport(){
		$SQL = "SELECT value from project_config";
		$query = $this->query($SQL);
		$data = array();
		while (($rs = $this->fetch_array($query)) != false){
			$data[] = $rs['value'];
		}
		
		return $data;
	}
	
	function getProjectById($id){
		$SQL = "SELECT * FROM `project_rd` WHERE `id` = {$id}";
		$datas = $this->_db->getArray($SQL);
		$data = array();
		if(is_array($datas)){
			$data = array_pop($datas);
		}
		return $data;
	}
	
	/*********************************** FILE ***********************************/
	public function checkFileIsExist($file, $projectId){
		$errorList = array();
		$tempList = array();
		foreach($file['upload_file']['error'] as $key => $error){
			if($error == 0 && $file['upload_file']['size'][$key] > 0 && !empty($file['upload_file']['name'][$key])){
				if(!in_array($file['upload_file']['name'][$key], $tempList)){
					$tempList[] = $file['upload_file']['name'][$key];
					$position = strrpos($file['upload_file']['name'][$key], ".");
					if($position !== false){
						$suffix = substr($file["upload_file"]["name"][$key], $position + 1);
						$filename = substr($file["upload_file"]["name"][$key], 0, $position);
						$SQL = "SELECT COUNT(*) AS num FROM `project_rd_file` WHERE filename = '{$filename}' AND suffix = '{$suffix}' AND project = '{$projectId}'";
						$datas = $this->_db->getArray($SQL);
						if(is_array($datas)){
							$data = array_pop($datas);
							if($data['num'] > 0){
								$errorList[] = $file['upload_file']['name'][$key]." 已存在";
							}
						}else{
							$errorList[] = $file['upload_file']['name'][$key]." 上传失败";
						}
					}
				}else{
					$errorList[] = $file['upload_file']['name'][$key]." 上传列表中有重复";
				}
			}
		}
		return $errorList;
	}
	
	
	public function uploadFile($file, $data, $projectId, $methodType){
		set_time_limit ( 0 );
		$project = $this->getProjectById($projectId);
		
		/* local */
		$uploadPath = "attachment/project_rd/{$project['name']}/";
		$this->chckAndCreateUploadPath($uploadPath);
		
		/* FTP */
		$serverPath = "project/{$project['name']}/";
		$ftp = new includes_class_ftp('172.16.1.102', '21', 'oa_upload', 'dinglicom.com');
		
		$errorArr = array();
		$uploadFtpFailErrorArr = array();
		foreach($file['upload_file']['error'] as $key => $error){
			if($error == 0 && $file['upload_file']['size'][$key] > 0 && !empty($file['upload_file']['name'][$key])){
				$position = strrpos($file['upload_file']['name'][$key], ".");
				if($position !== false){
					$suffix = substr($file["upload_file"]["name"][$key], $position + 1);
					$filename = substr($file["upload_file"]["name"][$key], 0, $position);
					$nikename = $filename;
					$size = $file['upload_file']['size'][$key];
					$type = $data['upload_file_type'][$key];
					
					//TODO
					$insertId = $this->saveFileDetail($filename, $nikename, $suffix, $size, $projectId, $type);
					if($insertId){
						$flag = move_uploaded_file($file["upload_file"]["tmp_name"][$key], "{$uploadPath}{$filename}.{$suffix}");
						if($flag){
							$this->modifiedFileDetailStatus($insertId, 'finished');
							
							/* FTP */
							if(!$ftp->up_file("{$uploadPath}{$filename}.{$suffix}", "{$serverPath}{$filename}.{$suffix}")){
								$uploadFtpFailErrorArr[] = "{$filename}.{$suffix} Upload File Server";
							}
						}
					}else{
						$errorArr[] = $file['upload_file']['name'][$key]." Upload fail";
					}
				}else{
					$errorArr[] = $file['upload_file']['name'][$key]." has not suffix";
				}
			}else{
				if($methodType == 'need'){
					$errorArr[] = "Upload File";
				}
			}
		}
		
		$ftp->close();
		
		return $errorArr;
	}

	private function saveFileDetail($filename, $nikename, $suffix, $size, $project, $type){
		$date = date('Y-m-d');
		$SQL = "
			INSERT INTO 
				project_rd_file(`filename`, `nikename`, `suffix`, `size`, `project`, `type`, `uploadby`, `updatedate`, `status`, `deleted`)
			VALUES
				('{$filename}', '{$nikename}', '{$suffix}', '{$size}', '{$project}', '{$type}', '{$_SESSION['USER_ID']}', '{$date}', 'uploading', '0');
		";
		$lastInsertID = 0;
		if($this->_db->query($SQL) != false){
			$lastInsertID = $this->_db->insert_id();
		}
		return $lastInsertID;
	}
	
	private function modifiedFileDetailStatus($id, $status){
		$SQL = "UPDATE `project_rd_file` SET `status` = '{$status}' WHERE `id` = '{$id}'";
		$isSuccess = false;
		if($this->_db->query($SQL) != false){
			$isSuccess = true;
		}
		return $isSuccess;
	}
	
	function modifiedFileDetailType($id, $type){
		$SQL = "UPDATE `project_rd_file` SET `type` = '{$type}' WHERE `id` = '{$id}'";
		$isSuccess = false;
		if($this->_db->query($SQL) != false){
			$isSuccess = true;
		}
		return $isSuccess;
	}
	
	public function getFileList($conditions = "", $isDel = 0){
		if($conditions != ""){
			$conditions = " AND ".$conditions;
		}
		
		$SQL = "SELECT 
					id as file_id, 
					filename as file_name,  
					suffix as file_type,
					size as file_size,
					project as file_belong,
					type as file_project_type
				FROM `project_rd_file` WHERE `deleted` = {$isDel} {$conditions} ORDER BY type ASC";
		return $this->_db->getArray($SQL);
	}
	
	public function getIsExist(){
		
	} 
	
	public function getFileByConditions($data){
		$conditionArr = array();
		foreach ($data as $key => $value){
			$conditionArr[] = "`{$key}` = '{$value}'";
		}
		if(count($conditionArr) > 0){
			$conditions = "WHERE ".join(" AND ",$conditionArr);
		}
		$SQL = "SELECT * FROM `project_rd_file` {$conditions}";
		return $this->_db->getArray($SQL);
	}
	
	function chckAndCreateUploadPath($path){
		$pathList = explode("/", $path);
		$temp = "";
		foreach($pathList as $path){
			if(!empty($path)){
				$temp .= $path."/";
			}
			
			if(!is_dir($temp)){
				mkdir($temp);
			}
		}
	}
	
	function downloadFile($id){
		$errorList = array();
		$resourceID = $this->connectFtp();
		if(!is_resource($resourceID)){
			$errorList[] = 'could not connect FTP';
		}else{
			if(!$this->loginFtp($resourceID)){
				$errorList[] = 'user or password invalid';
			}
		}
		$this->closeFtp($resourceID);
		
		$dataObj = array();
		$dataObj['id'] = $id;
		$files = $this->getFileByConditions($dataObj);
		$file = array_pop($files);
		
		$project = $this->getProjectById($file['project']);
		if(count($errorList) > 0){
			$downloadPath = "attachment/project_rd/{$project['name']}/";
		}else{
			$downloadPath = "http://172.16.1.102:8082/project/".$project['name']."/";
		}
		
		if(is_array($file)){
			if(count($errorList) > 0){
				$this->oupputExt($downloadPath, $file);
			}else{
				$this->output($downloadPath, $file);
			}
		}
	}
	
	function output($path ,$file){
		set_time_limit ( 0 );
		$downloadFileName = basename(trim($file['filename']).'.'.$file['suffix']);
		//echo $downloadFileName;exit;
		$temp = rawurlencode($downloadFileName);
		$downloadFile = $path.$temp;
		
		header("Content-Type: application/force-download");
		//header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".$downloadFileName); 
		readfile($downloadFile);
		exit;
	}
	
	function oupputExt($path ,$file){
		set_time_limit ( 0 );
		$downloadFileName = basename(trim($file['filename']).'.'.$file['suffix']);
		$downloadFile = $path.$downloadFileName;
		$file = fopen($downloadFile, "r");
	    Header("Content-type: application/octet-stream");
	    Header("Accept-Ranges: bytes");
	    Header("Accept-Length: ".filesize($downloadFile));
	    Header("Content-Disposition: attachment; filename=".$downloadFileName);
	    echo fread($file, filesize($downloadFile));
	    fclose($file);
	    exit;
	}
	
	function removeFile($id){
		
		//获取文件信息，项目信息
		$dataObj = array();
		$dataObj['id'] = $id;
		$files = $this->getFileByConditions($dataObj);
		$file = array_pop($files);
		$project = $this->getProjectById($file['project']);
		
		$serverPath = "project/{$project['name']}/".basename(trim($file['filename']).'.'.$file['suffix']);
		
		//连接FTP，并移除文件
		$ftp = new includes_class_ftp('172.16.1.102', '21', 'oa_upload', 'dinglicom.com');
		$ftp->del_file($serverPath);
		
		$uploadPath = "attachment/project_rd/{$project['name']}/".basename(trim($file['filename']).'.'.$file['suffix']);
		@unlink($uploadPath);
		
		//删除数据库内容
		$SQL = "DELETE FROM `project_rd_file` WHERE `id` = {$id}";
		//$SQL = "UPDATE `project_rd_file` SET `deleted` = 1 WHERE `id` = {$id}";
		return $this->_db->query($SQL);
	}
	
	private function connectFtp(){
		$host = "172.16.1.102";
		return ftp_connect($host);
	}
	
	private function loginFtp($resource){
		$user = "oa_upload";
		$password = "dinglicom.com";
		return ftp_login($resource, $user, $password);
	}
	
	private function putFile($resource, $remotePath, $sorucePath){
		return ftp_put($resource, $remotePath, $sorucePath, FTP_BINARY); 
	}
	
	private function closeFtp($resource){
		return ftp_close($resource);
	}
	
}
