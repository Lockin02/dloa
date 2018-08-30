<?php
class controller_system_organizer_mailgroup_index extends model_system_organizer_mailgroup_index
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'system/organizer/mailgroup/';
	}
	
	function c_index()
	{
		$this->c_list();
	}
	/**
	 * 列表
	 */
	function c_list()
	{
		global $func_limit;
		$this->show->assign('add',$func_limit['添加权限']?$func_limit['添加权限']:'');
		$this->show->display('list');
	}
	function c_indexdata()
	{
		echo $this->model_indexdata();
		
	}
	/**
	 * 添加
	 */
	function c_add()
	{
		
		if ($_POST)
		{
			set_time_limit(0);
			if ($this->model_add($_POST))
			{
				showmsg('操作成功！','self.parent.location.reload();','button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}

		}else{
			$gl = new includes_class_global();
			$select_dept='';
			global $func_limit;
			if ($func_limit['管理员']||$func_limit['添加权限'])
			{
				$dept = new model_system_dept();
				$select_dept = $dept->options();
				$select_area = $gl->area_select();
				
			}else{
				$data = $gl->GetDept($_SESSION['DEPT_ID']);
				$areaaName=$gl->get_area($_SESSION['AREA']);
				$select_dept = '<option value="'.$_SESSION['DEPT_ID'].'">'.$data[0]['DEPT_NAME'].'</option>';
				$select_area = '<option value="'.$_SESSION['AREA'].'">'.$areaaName.'</option>';
			}
			$this->show->assign('select_area',$select_area);
			$this->show->assign('select_dept',$select_dept);
			$this->show->display('add');
		}
	}
	/**
	 * 修改
	 */
	function c_edit()
	{
		if ($_POST)
		{
			set_time_limit(0);
			if($this->model_edit($_GET['id'],$_GET['key'],$_POST))
			{
				showmsg('操作成功！','self.parent.location.reload();','button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
		}else{
			if ($_GET['id'] && $_GET['key'])
			{
				$rs = $this->model_get_groupinfo($_GET['id'] , $_GET['key']);
				if ($rs)
				{
					$dept = new model_system_dept();
					
					$gl = new includes_class_global();
					global $func_limit;
					if ($func_limit['管理员'])
					{
						$dept = new model_system_dept();
						$select_dept = $dept->options();
						$select_area = $gl->area_select();
						
					}else{
						$data = $gl->GetDept($_SESSION['DEPT_ID']);
						$areaaName=$gl->get_area($_SESSION['AREA']);
						$select_dept = '<option value="'.$_SESSION['DEPT_ID'].'">'.$data[0]['DEPT_NAME'].'</option>';
						$select_area = '<option value="'.$_SESSION['AREA'].'">'.$areaaName.'</option>';
					}
					if($rs['type']==1){
					   $UserName = $gl->GetUserName((array)explode(',',$rs['memberlist']));
					   $UserId=trim($rs['memberlist']);
					   $checked1='checked';
					   $optionUserIdStr=$this->getOption((array)explode(',',trim($rs['unmemberlist'])));
								   
					}else if($rs['type']==2){
						$select_dept = $dept->options($rs['dept_id']);
						$deptUserName=$gl->GetUserName((array)explode(',',$rs['extra']));
						$deptUserId=trim($rs['extra']);
						$checked2='checked';
					    $optionDeptStr=$this->getOption((array)explode(',',trim($rs['unmemberlist'])));
					}else if($rs['type']==3){
						$select_area = $gl->area_select($rs['ascription']);
						$areaUserName=$gl->GetUserName((array)explode(',',$rs['extra']));
						$areaUserId=trim($rs['extra']);
						$checked3='checked';
						$optionAreaStr=$this->getOption((array)explode(',',trim($rs['unmemberlist'])));
					}else if($rs['type']==4){
						$select_dept = $dept->options($rs['dept_id']);
						$select_area = $gl->area_select($rs['ascription']);
						$mixUserName=$gl->GetUserName((array)explode(',',$rs['extra']));
						$mixUserId=trim($rs['extra']);
						$checked6='checked';
						$optionMixStr=$this->getOption((array)explode(',',trim($rs['unmemberlist'])));
					}
					$SendName =$gl->GetUserName((array)explode(',',$rs['senderlist']));
					$noUserName =$gl->GetUserName((array)explode(',',$rs['noUserIdlist']));
					if($rs['send_type']==1){
					   $checked4='checked';
						
					}else if($rs['send_type']==2){
						$checked5='checked';
					}
					
					$this->show->assign('sId',$rs['dept_id']);
					$optionSendUserStr=$this->getOption((array)explode(',',trim($rs['unsenderlist'])));
					$this->show->assign('checked1',$checked1?$checked1:'');
					$this->show->assign('checked2',$checked2?$checked2:'');
					$this->show->assign('checked3',$checked3?$checked3:'');
					$this->show->assign('checked4',$checked4?$checked4:'');
					$this->show->assign('checked5',$checked5?$checked5:'');
					$this->show->assign('checked6',$checked6?$checked6:'');
					
					$this->show->assign('optionAreaStr',$optionAreaStr?$optionAreaStr:'');
					$this->show->assign('optionDeptStr',$optionDeptStr?$optionDeptStr:'');
					$this->show->assign('optionMixStr',$optionMixStr?$optionMixStr:'');
					$this->show->assign('optionUserIdStr',$optionUserIdStr?$optionUserIdStr:'');
					$this->show->assign('optionSendUserStr',$optionSendUserStr?$optionSendUserStr:'');
					
					$this->show->assign('UserName',$UserName?implode(',',(array)$UserName).',':'');
					$this->show->assign('UserId',$UserId?rtrim($UserId,',').',':'');
		
					$this->show->assign('deptUserName',$deptUserName?implode(',',(array)$deptUserName).',':'');
					$this->show->assign('deptUserId',$deptUserId?rtrim($deptUserId,',').',':'');
					
					$this->show->assign('areaUserName',$areaUserName?implode(',',(array)$areaUserName).',':'');
					$this->show->assign('areaUserId',$areaUserId?rtrim($areaUserId,',').',':'');
					
					$this->show->assign('mixUserName',$mixUserName?implode(',',(array)$mixUserName).',':'');
					$this->show->assign('mixUserId',$mixUserId?rtrim($mixUserId,',').',':'');

					$this->show->assign('description',$rs['description']);
					$this->show->assign('groupname',$rs['groupname']);
					$this->show->assign('SendName',$SendName?implode(',',(array)$SendName).',' :'');
					$this->show->assign('SendId',$rs['senderlist']?rtrim($rs['senderlist'],',').',':'');
					$this->show->assign('noUserName',$noUserName?implode(',',(array)$noUserName).',':'');
					$this->show->assign('noUserId',$rs['noUserIdlist']?$rs['noUserIdlist']:'');
					
					$this->show->assign('select_dept',$select_dept);
					$this->show->assign('select_area',$select_area);
					$this->show->display('edit');
				
				}else{
					showmsg('数据不在！');
				}
			}
		}
	}
	function getOption($unAreaUserIdI){
		$optionStr='';
		if($unAreaUserIdI&&is_array($unAreaUserIdI)){
		   	  foreach($unAreaUserIdI as $key=>$val){
		   	  	$optionStr.='<option value="'.$val.'" title="双击删除">'.$val.'</option>';
		   	  }
		}
	return $optionStr;	
	}
	/**
	 * 修改
	 */
	function c_detail()
	{
		if ($_GET['id'] && $_GET['key'])
			{
				$rs = $this->model_get_groupinfo($_GET['id'] , $_GET['key']);
				if ($rs)
				{
					$dept = new model_system_dept();
					
					$gl = new includes_class_global();
					global $func_limit;
					if ($func_limit['管理员'])
					{
						$dept = new model_system_dept();
						$select_dept = $dept->options();
						$select_area = $gl->area_select();
						
					}else{
						$data = $gl->GetDept($_SESSION['DEPT_ID']);
						$areaaName=$gl->get_area($_SESSION['AREA']);
						$select_dept = '<option value="'.$_SESSION['DEPT_ID'].'">'.$data[0]['DEPT_NAME'].'</option>';
						$select_area = '<option value="'.$_SESSION['AREA'].'">'.$areaaName.'</option>';
					}
					if($rs['type']==1){
					   $UserName = $gl->GetUserName(explode(',',$rs['memberlist']));
					   $UserId=trim($rs['memberlist']);
					   $checked1='checked';
						
					}else if($rs['type']==2){
						$select_dept = $dept->options($rs['dept_id']);
						$deptUserName=$gl->GetUserName(explode(',',$rs['extra']));
						$deptUserId=trim($rs['extra']);
						$checked2='checked';
					}else if($rs['type']==3){
						$select_area = $gl->area_select($rs['ascription']);
						$areaUserName=$gl->GetUserName(explode(',',$rs['extra']));
						$areaUserId=trim($rs['extra']);
						$checked3='checked';
						
					}else if($rs['type']==4){
						$select_dept = $dept->options($rs['dept_id']);
						$select_area = $gl->area_select($rs['ascription']);
						$mixUserName=$gl->GetUserName(explode(',',$rs['extra']));
						$mixUserId=trim($rs['extra']);
						$checked6='checked';
						
					}
					$unMemberListI=(array)explode(',',$rs['unmemberlist']);
					$unSenderListI=(array)explode(',',$rs['unsenderlist']);
	
					$memberlistI=array_diff(explode(',',$rs['memberlist']),explode(',',$rs['extra']));
					$memberlist = $gl->GetUserName($memberlistI);
					$SendName =$gl->GetUserName(explode(',',$rs['senderlist']));
					$noUserName =$gl->GetUserName((array)explode(',',$rs['noUserIdlist']));
					
					if($rs['send_type']==1){
					   $checked4='checked';
						
					}else if($rs['send_type']==2){
						$checked5='checked';
					}
					$this->show->assign('checked1',$checked1?$checked1:'');
					$this->show->assign('checked2',$checked2?$checked2:'');
					$this->show->assign('checked3',$checked3?$checked3:'');
					$this->show->assign('checked4',$checked4?$checked4:'');
					$this->show->assign('checked5',$checked5?$checked5:'');
					$this->show->assign('checked6',$checked6?$checked6:'');
					$this->show->assign('UserName',$memberlist?implode('、',(array)$memberlist):'');
                 	$this->show->assign('deptUserName',$deptUserName?'、'.implode('、',(array)$deptUserName):'');
                 	$this->show->assign('areaUserName',$areaUserName?'、'.implode('、',(array)$areaUserName):'');
                 	$this->show->assign('mixUserName',$mixUserName?'、'.implode('、',(array)$mixUserName):'');
                 	
                 	$this->show->assign('unMemberList',$unMemberListI?'、'.implode('、',(array)$unMemberListI):'');
                 	$this->show->assign('unSenderList',$unSenderListI?'、'.implode('、',(array)$unSenderListI):'');
                 	$sendI=array_filter(array_unique((array)array_merge((array)$memberlist,(array)$areaUserName,(array)$deptUserName)));
					$this->show->assign('SendName',$SendName?implode('、',(array)$SendName) :($sendI?implode('、',(array)$sendI):''));
					$this->show->assign('noUserName',$noUserName?implode('、',(array)$noUserName):'');
					
					$this->show->assign('description',$rs['description']);
					$this->show->assign('groupname',$rs['groupname']);

					$this->show->assign('select_dept',$select_dept);
					$this->show->assign('select_area',$select_area);
					$this->show->display('detail');
				
				}else{
					showmsg('数据不在！');
				}

		}
	}
	/**
	 * 删除
	 */
	function c_del()
	{
		if ($_GET['id'] && $_GET['key'])
		{
			if ($this->model_del($_GET['id'] , $_GET['key']))
			{
				showmsg('操作成功！','self.parent.location.reload();','button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
		}
	}
	
	 function c_deptData(){
        $this->model_deptData();
    } 
}

?>