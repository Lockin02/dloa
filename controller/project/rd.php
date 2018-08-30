<?php
class controller_project_rd extends model_project_rd
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'project/';
	}
	
	/**
	 * 默认访问
	 */
	function c_index()
	{
		return $this->c_list();
	}
	
	/**
	 * 列表
	 */
	function c_list()
	{ 
		$project_manager = 'false';
		$group = new model_system_usergroup_list();
		$group_id = $group->GetId('project_manager'); // 获取项目管理员

		//导出权限
		global $func_limit;
		$canExport = 'false';
		if($func_limit[ '导出管理' ]){
			$canExport = 'true';
		}
		
		//导入
		$canImport = 'false';
		$adminList = $this->canImport();
		if(in_array($_SESSION['USER_ID'], $adminList)){
			$canImport = 'true';
		}
		
		if ($group_id && $group->CheckUser($group_id, $_SESSION['USER_ID']))
		{
			$project_manager = 'true';
		}
		//募投项目
		$ipo_option = '';
		$ipo = new model_project_ipo();
		$ipo_data = $ipo->GetDataList('type=1');
		if ($ipo_data)
		{
			foreach ($ipo_data as $row)
			{
				$ipo_option .='<option value="'.$row['id'].'">'.$row['project_name'].'</option>';
			}
		}
		//政府项目
		$zf_option = '';
		$zf_data = $ipo->GetDataList('type=2');
		if ($zf_data)
		{
			foreach ($zf_data as $row)
			{
				$zf_option .='<option value="'.$row['id'].'">'.$row['project_name'].'</option>';
			}
		}
		
		/* add at 2012-08-29 */
		//项目类型
		$project_option = '';
		$projectType = new model_project_rdtype();
		$project_data = $projectType->GetDataList();
		if ($project_data)
		{
			foreach ($project_data as $row)
			{
				$project_option .='<option value="'.$row['id'].'">'.$row['typename'].'</option>';
			}
		}
		/* end */
		
		//产品
		$product_option = '';
		$product_obj = new model_product_list();
		$product = $product_obj->GetDataList();
		if ($product)
		{
			foreach ($product as $row)
			{
				$product_option .= '<option value="'.$row['id'].'">'.$row['product_name'].'</option>';
			}
		}
		
		$dept_obj = new model_system_dept();
		$dept_option = $dept_obj->options();
		$this->show->assign('canExport', $canExport);
		$this->show->assign('canOperate', $func_limit[ '操作权限' ]);
		$this->show->assign('canImport',$canImport);
		$this->show->assign('admin',$project_manager);
		$this->show->assign('product_option', $product_option);
		$this->show->assign('dept_option', $dept_option);
		$this->show->assign('ipo_option', $ipo_option);
		$this->show->assign('zf_option', $zf_option);
		/* add at 2012-08-29 */
		$this->show->assign('project_option', $project_option);
		//$this->show->assign('membersStr', $membersStr);
		//$this->show->assign('user_option', $user_option);
		/* end */
		$this->show->display('rd');
	}
	
	/**
	 * 列表显示数据
	 */
	function c_list_data()
	{	
		$condition = '';
		
		if($_GET['keyword'] != ''){
			$condition .= "a.name like '%".trim($_GET['keyword'])."%'";
		}
		
		if($_GET['stage'] != ''){
			if($condition != ''){
				$condition .= " AND a.stage = '".trim($_GET['stage'])."'";
			}else{
				$condition .= " a.stage = '".trim($_GET['stage'])."'";
			}
		}
		
		if($_GET['projectType'] != ''){
			if($condition != ''){
				$condition .= " AND a.project_type = '".trim($_GET['projectType'])."'";
			}else{
				$condition .= " a.project_type = '".trim($_GET['projectType'])."'";
			}
		}
		
		if($_GET['dept'] != ''){
			if($condition != ''){
				$condition .= " AND a.dept_id = '".trim($_GET['dept'])."'";
			}else{
				$condition .= " a.dept_id = '".trim($_GET['dept'])."'";
			}
		}
		
		if($_GET['ipoType'] != ''){
			if($condition != ''){
				$condition .= " AND a.ipo_id = '".trim($_GET['ipoType'])."'";
			}else{
				$condition .= " a.ipo_id = '".trim($_GET['ipoType'])."'";
			}
		}
		
		if($_GET['zfType'] != ''){
			if($condition != ''){
				$condition .= " AND a.zf_id like '%".trim($_GET['zfType'])."%'";
			}else{
				$condition .= " a.zf_id like '%".trim($_GET['zfType'])."%'";
			}
		}
		
		if($_GET['projectStatus'] != ''){
			
			if($_GET['projectStatus'] != 'all'){
				if($condition != ''){
					$condition .= " AND a.status = '".trim($_GET['projectStatus'])."'";
				}else{
					$condition .= " a.status = '".trim($_GET['projectStatus'])."'";
				}
			}
		}else{
			if($condition != ''){
				$condition .= " AND a.status != '3' AND a.status != '6' ";
			}else{
				$condition .= " a.status != '3' AND a.status != '6' ";
			}
		}
		//$condition = $this->conditions();
		
		$data = $this->GetDataList($condition,$_POST['page'],$_POST['rows']);

		$json = array('total'=>$this->num);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		echo json_encode ( $json );
	}
	
	function c_change_data()
	{	
		$condition = '';
		if($_GET['keyword'] != ''){
			$condition .= " ( ";
			$condition .= " name like '%".trim($_GET['keyword'])."%' ";
			$condition .= " OR old_name like '%".trim($_GET['keyword'])."%' ";
			$condition .= " OR number like '%".trim($_GET['keyword'])."%' ";
			$condition .= " OR remarks like '%".trim($_GET['keyword'])."%' ";
			$condition .= " ) ";
		}
		
		if($_GET['status'] != ''){
			if($condition != ''){
				$condition .= " AND status = '".trim($_GET['status'])."'";
			}else{
				$condition .= " status = '".trim($_GET['status'])."'";
			}
		}
		
		$data = $data = $this->GetRdChangeDataList($condition,$_POST['page'],$_POST['rows']);
		$json = array('total'=>$this->num);
		
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		echo json_encode ( $json );
	}
	
	function c_change_save()
	{
		$_POST = mb_iconv($_POST);
		$id = isset($_GET['id']) ? intval($_GET['id']) : $_POST['id'];

		if (!$id)
		{
			unset($_POST[id]);
			if ($this->add_change($_POST))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			if ($this->Edit_change($_POST, $id))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	function c_change_del()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($this->Del_change($id))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
	function c_related_product()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['id']);
		if ($id)
		{
			$info = $this->GetOneInfo('id='.$id);
			if ($info['product_id_str'])
			{
				$product_obj = new model_product_list();
				$product = $product_obj->GetDataList("a.id in(".$info['product_id_str'].")");
				if ($product)
				{
					$str = '<div><ul>';
					foreach ($product as $rs)
					{
						$str .='<li style="height:25px;lin-height:25px;">'.$rs['product_name'].'（'.$rs['manager_name'].'）</li>';
					}
					$str .='</ul></div>';
					echo un_iconv($str);
				}
			}
		}
	}
	
	/**
	 * 保存数据
	 */
	function c_save()
	{
		$_POST = mb_iconv($_POST);
		
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		unset($_POST['id']);
		
		$memberId = $_POST['memberId'];
		unset($_POST['memberId']);
		
		$addTime = $_POST['addTime'];
		unset($_POST['addTime']);
		
		$leaveTime = $_POST['leaveTime'];
		unset($_POST['leaveTime']);
		
		$percent = $_POST['percent'];
		unset($_POST['percent']);
		
		$functionId = $_POST['member_function'];
		unset($_POST['member_function']);
		
		if(is_array($_POST['zf_id']) and count($_POST['zf_id']) > 0){
			$_POST['zf_id'] = implode(',', $_POST['zf_id']);
		}
		
		$user_data = $this->get_username('user_name');
		
		$manager = $this->get_userid_str($user_data, $_POST['manager_name']);
		$_POST['manager']  = $manager ? implode(',', $manager) : '';
		
		$assistant = $this->get_userid_str($user_data, $_POST['assistant_name']);
		$_POST['assistant'] = $assistant ? implode(',', $assistant) : '';
		
		//$developer = $this->get_userid_str($user_data, $_POST['developer_name']);
		$_POST['developer'] = $_POST['developer_name'];
		unset($_POST['developer_name']);
		
		$members = spliti("\|", $memberId);
		$addTimes = spliti("\|", $addTime);
		$leaveTimes = spliti("\|", $leaveTime);
		$percents = spliti("\|", $percent);
		$functionId = spliti("\|", $functionId);
		
		$feedbackId = 0;
		if(empty($id)){
			//增加新记录
			$new_id = $this->Add($_POST);
			if($new_id){
				for($i = 0; $i < count($members); $i++){
					if($members[$i] != ""){
						$this->addMember($members[$i], $addTimes[$i], $leaveTimes[$i], $percents[$i], $new_id, $functionId[$i]);
					}
				}
				$result = array('temp remove');
				file_put_contents('project.txt', $result);
				$feedbackId = $new_id;
			}
		}else{
			$result = $this->Edit($_POST, $id);
			if($result){
				for($i = 0; $i < count($members); $i++){
					if($members[$i] != ""){
						$result = $this->isExist($members[$i], $id);
						if($result != 1){
							$this->addMember($members[$i], $addTimes[$i], $leaveTimes[$i], $percents[$i], $id, $functionId[$i]);
						}else{
							$this->UpdateMember($members[$i], $addTimes[$i], $leaveTimes[$i], $percents[$i], $id , $functionId[$i]);
						}
					}
				}
				$feedbackId = $id;
			}
		}
		
		echo $feedbackId;
		
//		if ($id == '')
//		{
			//增加新记录
//			$new_id = $this->Add($_POST);
//			
//			for($i = 0; $i < count($members); $i++){
//				if($members[$i] != ""){
//					$this->addMember($members[$i], $addTimes[$i], $leaveTimes[$i], $percents[$i], $new_id, $functionId[$i]);
//				}
//			}
			
			//如果新增成功同步PMS
//			if ($new_id > 0)
//			{
//				$project = array();
//				$project['tid'] = $new_id;
//				$project['name'] = $_POST['name'];
//				$project['PM'] = $_POST['manager'];
//				$project['code'] = $_POST['number'];
//				$project['begin'] = $_POST['begin_date'];
//				$project['end'] = $_POST['end_date'];
//				$project['days'] =  floor((strtotime($_POST['end_date'])-strtotime($_POST['begin_date'])) / 86400);
//				$project['team'] = $_POST['name'].'项目组';
//				$project['products'] = $_POST['product_id_str'] ? explode(',', $_POST['product_id_str']) : '';
//				$project['desc'] = $_POST['description'];
//				$project['acl'] = 'private';
//				
//				/* temp remove */
//				//$pms = new api_pms();
//				//$result = $pms->GetModule('project', 'add&params=tid='.$new_id,un_iconv($project),'post');
//				$result = array('temp remove');
//				file_put_contents('project.txt', $result);
//				echo 1;
//			}else{
//				echo -1;
//			}
//		}else if ($id){
			
			//编辑记录
//			if ($this->Edit($_POST, $id))
//			{
//				
//				for($i = 0; $i < count($members); $i++){
//					if($members[$i] != ""){
//						$result = $this->isExist($members[$i], $id);
//						if($result != 1){
//							$this->addMember($members[$i], $addTimes[$i], $leaveTimes[$i], $percents[$i], $id, $functionId[$i]);
//						}else{
//							$this->UpdateMember($members[$i], $addTimes[$i], $leaveTimes[$i], $percents[$i], $id , $functionId[$i]);
//						}
//					}
//				}
				
				//如果编辑成功同步PMS
//				$project = array();
//				$project['name'] = $_POST['name'];
//				$project['PM'] = $_POST['manager'];
//				$project['code'] = $_POST['number'];
//				$project['begin'] = $_POST['begin_date'];
//				$project['end'] = $_POST['end_date'];
//				$project['days'] =  floor((strtotime($_POST['end_date'])-strtotime($_POST['begin_date'])) / 86400);
//				$project['team'] = $_POST['name'].'项目组';
//				$project['products'] = $_POST['product_id_str'] ? explode(',', $_POST['product_id_str']) : '';
//				$project['desc'] = $_POST['description'];
//				//$project['acl'] = 'private';
//				
//				/* temp remove */
//				//$pms = new api_pms();
//				//$result = $pms->GetModule('project', 'edit&params=tid='.$id,un_iconv($project),'post');
//				echo 1;
//			}else{
//				echo -1;
//			}
//		}
	}
	
	/**
	 * 删除项目
	 */
	function c_del()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			if ($this->Edit(array('is_delete'=>1), $id))
			{
				$pms = new api_pms();
				$pms->GetModule('project', 'del&params=tid='.$id,'','post');
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -1;
		}
	}
	
	/**
	 * 根据部门获取人员列表
	 */
	function c_user_list(){
		$deptId = $_GET['deptId'] ? $_GET['deptId'] : $_POST['deptId'];
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$existMember = array();
		if($id != "NaN"){
			$members = $this->getMemberList($id);
			if(isset($members)){
				foreach($members as $key=>$value){
					$existMember[] = $value['account'];
				}
			}
		}
		
		$members_option = '';
		if(isset($deptId)){
			$data = $this->getUserByDept($deptId);
			$data = un_iconv($data);
			
			if($data){
				foreach($data as $key=>$value){
					if(!in_array($key, $existMember)){
						$members_option .='<option value="'.$key.'_'.$value.'">'.$value.'</option>';
					}
				}
			}
		}
		echo $members_option;
	}
	
	
	function c_member_table(){
		
		//项目人员
		$membersStr = '';
		$members = $this->getMemberList($_POST['id']);
		$condition = " a.status != '3' AND a.status != '6' ";
		$data = $this->GetDataList($condition); 
		foreach ($data as $key => $val){
			if(!empty($val['id']) && $_POST['id'] != $val['id']){
				$datas[] = $val['id'];	
			}
		}
		$conditionID = '';
		if(!empty($datas)){
			$conditionID = " AND project in(".implode(',',$datas).")";
		}

		if($members){
			$i = 0;
			foreach ($members as $key=>$row){

				$realName = $this->getRealNameByAccount($row['account']);
				$aa = $this->getMemberPercent($row['account'],$_POST['id'],$conditionID);
				$total = 100 - $this->getMemberPercent($row['account'],$_POST['id'],$conditionID); 
				$total = $total >=0 ? $total : 0;
				$i = $i + 1;
				$addTime = $row['addtime'] == '0000-00-00 00:00:00'?'':substr($row['addtime'], 0, 10);
				$leaveTime = $row['leavetime'] == '0000-00-00 00:00:00'?'':substr($row['leavetime'], 0, 10);
				$member_function = $row['function'];
				$lang = iconv("gbk","utf-8","占比过大,不可超过 `$total`");
				$membersStr .= '
					<tr align="center">
						<td><input type="hidden" name="memberId" id="memberId['. $i .']" value="'. $row['account'] .'" /><span style="width:40px;">'.$realName.'</span></td>
						<td><input type="text" readonly onClick="WdatePicker();" class="Wdate" id="addtime['.$i.']" name="addtime" value="'.$addTime.'" style="width:80px;" /></td>
						<td><input type="text" readonly onClick="WdatePicker();" class="Wdate" id="leavetime['.$i.']" name="leavetime" value="'.$leaveTime.'" style="width:80px;" /></td>
						<td><input type="text" id="percent['. $i .']" name="percent" value="'.$row['percent'].'" style="width:25px;" onblur="if($(this).val() > '.$total.'){alert(\''.$lang.'\');$(this).val(\'\');}"/ onkeyup="value=value.replace(/[^\d]/g,\'\')">%</td>
						<td>
			 				<select id="member_function['. $i .']" name="member_function" style="width:50px;" >
			 				'.$this->deptTypeOption(true, $member_function).'
			 				</select>
		 				</td>
		 				<td>
		 					<input type="button" id="remove_'.$i.'_button" value="'.un_iconv('移除').'" onclick="removeMembers(\''.$row['account'].'\');" />
		 				</td>
					</tr>
				';
			}
		}
		echo $membersStr;
	}
	
	/**
	 * 查看人员信息
	 */
	function c_view_member_table(){
		
		//项目人员
		$membersStr = '';
		$members = $this->getMemberList($_POST['id']);
		if($members){
			$i = 0;
			foreach ($members as $key=>$row){
				$realName = $this->getRealNameByAccount($row['account']);
				$i = $i + 1;
				$percent = $row['percent'] == ''?'':$row['percent'].'%';
				$addTime = $row['addtime'] == '0000-00-00 00:00:00'?'':substr($row['addtime'], 0, 10);
				$leaveTime = $row['leavetime'] == '0000-00-00 00:00:00'?'':substr($row['leavetime'], 0, 10);
				$function = $this->getTypeForFunction($row['function']);
				$membersStr .= '
					<tr>
						<td><span style="width:40px;">'.$realName.'</span></td>
						<td>'.$addTime.'</td>
						<td>'.$leaveTime.'</td>
						<td>'.$percent.'</td>
						<td>'.$function.'</td>
					</tr>
				';
			}
		}
		echo $membersStr;
	}
	
	/**
	 * 成员真实名称
	 */
//	function c_user_real_name(){
//		echo $this->getRealNameByAccount($_POST['userId']);
//	}
	
	/**
	 * 
	 */
	function c_get_dev_options(){
		$members = $this->getMemberList($_POST['id']);
		$str = "";
		if($members){
			foreach($members as $row){
				$realName = $this->getRealNameByAccount($row['account']);
				$str .= '<option value="'.$row['account'].'_'.$realName.'">'.$realName.'</option>';
			}
		}
		echo $str;
	}
	
	/**
	 * 新增记录 成员表格 
	 */
	function c_get_member_info(){
		$str = "";
		
		
		$userAccount = $_POST['memberAccount'];
		$userName = $_POST['member'];
		$total = $_POST['total'];
		$project = 0;
		if(isset($_POST['pid']) && $_POST['pid'] != 0){
			$project = $_POST['pid'];
		}
		
		$info = $this->getMemberInfo($userAccount, $project);
		if(empty($info) && $project != 0){
			$this->addMember($userAccount, '', '', '', $project, '');
			
		}
		$info = $this->getMemberInfo($userAccount, $project);
		$str = '<td>&nbsp;</td>';
		if($info['account'] != ""){
			$str = '<td>
 					<input type="button" id="remove_'.$total.'_button" value="'.un_iconv('移除').'" onclick="removeMembers(\''.$info['account'].'\');" />
 				</td>';
		}
		$percent = $info['percent'] == ""?'':$info['percent'];
		$addTime = $info['addtime'] == '0000-00-00 00:00:00'?'':substr($info['addtime'], 0, 10);
		$leaveTime = $info['leavetime'] == '0000-00-00 00:00:00'?'':substr($info['leavetime'], 0, 10);
		$member_function = $info['function'];
		
		$str = "<tr id=" . $userAccount . " align='center'>" 
	 				. "<td>" 
	 				. '<input type="hidden" name="memberId" id="memberId['. $total .']" value="'. $userAccount .'" /><span style="width:40px;">'. $userName .'</span>'
	 				. "</td>"
	 				
	 				. "<td>"
	 				. '<input type="text" readonly onClick="WdatePicker();" class="Wdate" id="addtime['. $total .']" name="addtime" value="'.$addTime.'" style="width:80px;" />'
	 				. "</td>"
	 				
	 				. "<td>"
	 				. '<input type="text" readonly onClick="WdatePicker();" class="Wdate" id="leavetime['. $total .']" name="leavetime" value="'.$leaveTime.'" style="width:80px;" />'
	 				. "</td>"
	 				
	 				. "<td>"
	 				. '<input type="text" id="percent['. $total .']" name="percent" value="'.$percent.'" style="width:25px;" />%'
	 				. "</td>"
	 				
	 				. "<td>"
	 				. '<select id="member_function['. $total .']" name="member_function" >'
	 				. $this->deptTypeOption(true, $member_function)
	 				. '</select>'
	 				. "</td>"
	 				. $str
	 				. "</tr>";
		
		 echo $str;
		
	}
	
	function c_export(){
		
		$condition = $this->conditions();
		$this->export($condition);
	}
	
	function c_import(){
		
		if($_FILES['upfile'] and $_FILES['upfile']['tmp_name'] != ''){
			set_time_limit ( 0 );
			$flag = $this->import();
			
			if($flag){
				echo '<script>parent.importResult("' . 1 . '");</script>';
				//showmsg ( '操作成功！', 'index1.php?model=project_rd', 'link' );
			}else{
				echo '<script>parent.importResult("' . 0 . '");</script>';
				//showmsg ( '操作失败！', 'index1.php?model=project_rd', 'link' );
			}
		}else{
			showmsg ( '请选择Excel数据文件！' );
		}
	}
	
	function c_pManager(){
		//募投项目
		$ipo_option = '';
		$ipo = new model_project_ipo();
		$ipo_data = $ipo->GetDataList('type=1');
		if ($ipo_data)
		{
			foreach ($ipo_data as $row)
			{
				$ipo_option .='<option value="'.$row['id'].'">'.$row['project_name'].'</option>';
			}
		}
		//政府项目
		$zf_option = '';
		$zf_data = $ipo->GetDataList('type=2');
		if ($zf_data)
		{
			foreach ($zf_data as $row)
			{
				$zf_option .='<option value="'.$row['id'].'">'.$row['project_name'].'</option>';
			}
		}
		
		/* add at 2012-08-29 */
		//项目类型
		$project_option = '';
		$projectType = new model_project_rdtype();
		$project_data = $projectType->GetDataList();
		if ($project_data)
		{
			foreach ($project_data as $row)
			{
				$project_option .='<option value="'.$row['id'].'">'.$row['typename'].'</option>';
			}
		}
		/* end */
		
		//产品
		$product_option = '';
		$product_obj = new model_product_list();
		$product = $product_obj->GetDataList();
		if ($product)
		{
			foreach ($product as $row)
			{
				$product_option .= '<option value="'.$row['id'].'">'.$row['product_name'].'</option>';
			}
		}
		
		$dept_obj = new model_system_dept();
		$dept_option = $dept_obj->options();
		
		$this->show->assign('product_option', $product_option);
		$this->show->assign('dept_option', $dept_option);
		$this->show->assign('ipo_option', $ipo_option);
		$this->show->assign('zf_option', $zf_option);
		$this->show->assign('project_option', $project_option);
		$this->show->display('mlist');
	}
	
	function c_managerListData()
	{	
		$condition = '';
		
		if($_GET['keyword'] != ''){
			$condition .= "a.name like '%".trim($_GET['keyword'])."%'";
		}
		
		
		if($_GET['projectType'] != ''){
			if($condition != ''){
				$condition .= " AND a.project_type = '".trim($_GET['projectType'])."'";
			}else{
				$condition .= " a.project_type = '".trim($_GET['projectType'])."'";
			}
		}
		
		if($_GET['ipoType'] != ''){
			if($condition != ''){
				$condition .= " AND a.ipo_id = '".trim($_GET['ipoType'])."'";
			}else{
				$condition .= " a.ipo_id = '".trim($_GET['ipoType'])."'";
			}
		}
		
		if($_GET['zfType'] != ''){
			if($condition != ''){
				$condition .= " AND a.zf_id like '%".trim($_GET['zfType'])."%'";
			}else{
				$condition .= " a.zf_id like '%".trim($_GET['zfType'])."%'";
			}
		}
		
		if($_GET['projectStatus'] != ''){
			if($condition != ''){
				$condition .= " AND a.status = '".trim($_GET['projectStatus'])."'";
			}else{
				$condition .= " a.status = '".trim($_GET['projectStatus'])."'";
			}
		}else{
			if($condition != ''){
				$condition .= " AND a.status != '3' AND a.status != '6' ";
			}else{
				$condition .= " a.status != '3' AND a.status != '6' ";
			}
		}
		$condition .= "  AND FIND_IN_SET('".$_SESSION['USER_ID']."',a.manager) ";
		//$condition = $this->conditions();
		
		$data = $this->GetDataList($condition,$_POST['page'],$_POST['rows']);
			
		$json = array('total'=>$this->num);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		echo json_encode ( $json );
	}
	
	function c_remove_member(){
		$rs = -1;
		if($_POST){
			$rs = $this->removeMembers($_POST['account'], $_POST['id']);
		}
		echo $rs;
	}
	
	function c_upload_file(){
		$str = "";
		if($_FILES && $_POST && $_GET['project']){
			
			$checkList = $this->checkFileIsExist($_FILES, $_GET['project']);
			if(count($checkList) == 0){
				$result = $this->uploadFile($_FILES, $_POST, $_GET['project'], $_POST['type']);
				if(count($result) > 0){
					foreach ($result as $error){
						$str .= " -- {$error}<br/>";
					}
				}
			}else{
				foreach ($checkList as $error){
					$str .= " -- {$error}<br/>";
				}
			}
		}
		echo '<script>parent.uploadFileResult("' . $str . '");</script>';
	}
	
	function c_download_file(){
		
		if(isset($_GET['fileId']) && !empty($_GET['fileId'])){
			$data = $this->downloadFile($_GET['fileId']);
		}
	}
	
	function c_delete_file(){
		$isDel = 0;
		if(isset($_POST['fileId']) && !empty($_POST['fileId'])){
			$isDel = $this->removeFile($_POST['fileId']);
		}
		echo $isDel;
	}
	
	function c_show_file_list(){
		$files = array();
		if(isset($_GET['project']) && !empty($_GET['project'])){
			$data = $this->getFileList("project = {$_GET['project']}");
			if(is_array($data)){
				$files = un_iconv($data);
			}
		}
		echo json_encode($files);
	}
	
	function c_edit_file_propertie(){
		$result = 0;
		if($_POST){
			$result = $this->modifiedFileDetailType($_POST['fileId'], $_POST['fileType']);
		}
		echo $result;
	}
}
?>