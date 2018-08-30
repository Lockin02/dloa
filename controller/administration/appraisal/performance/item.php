<?php
class controller_administration_appraisal_performance_item extends model_administration_appraisal_performance_item
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'administration/appraisal/performance/';
	}
	/**
	 * 访问首页
	 */
	function c_index()
	{
		global $func_limit;
		//部门
		$dept_option_str = '';
		$obj = new model_system_dept();
		if ($func_limit['管理部门'] )
		{
			$dept_id = $func_limit['管理部门'];
		}else{
			$dept_id = $obj->GetParent_ID($_SESSION['DEPT_ID']);
			if ($dept_id)
			{
				$dept_id = array_merge($dept_id,array($_SESSION['DEPT_ID']));
			}
		}
		$dept = $obj->DeptTree($dept_id,0,1);
		$dept_arr = array();
		if ($dept)
		{
			foreach ($dept as $rs)
			{
				$dept_arr[] = array('DEPT_ID'=>$rs['DEPT_ID'],'DEPT_NAME'=>($rs['level'] ? str_repeat('|--',$rs['level']) : '').$rs['DEPT_NAME']);
				$dept_option_str.='<option value="'.$rs['DEPT_ID'].'">'.($rs['level'] ? str_repeat('|--',$rs['level']) : '').$rs['DEPT_NAME'].'</option>';
			}
		}
		$this->show->assign('year',date('Y'));
		$this->show->assign('quarte',ceil(date('n')/3));
		$this->show->assign('dept',json_encode(un_iconv($dept_arr)));
		$this->show->assign('dept_option',$dept_option_str);
		$this->show->display('item');
	}
	
	
	
	/**
	 * 
	 */
	function c_list_data()
	{
		global $func_limit;
		//部门
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		if(!$dept_id)
		{
			$dept_id = $func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID'];
		}
		$condition = '';
		if ($dept_id)
		{
			$condition .='a.dept_id in('.$dept_id.')';
		}
		//年份
		$years = $_GET['years'] ? $_GET['years'] : $_POST['years'];
		if ($years)
		{
			$condition .= ' and a.years='.$years;
		}
		//季度
		$quarter = $_GET['quarter'] ? $_GET['quarter'] : $_POST['quarter'];
		if ($quarter)
		{
			$condition .= ' and a.quarter='.$quarter;
		}
		//关键字
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		if ($keyword)
		{
			$condition .= " and a.name like '%$keyword%'";
		}
		$data = $this->GetDataList($condition,$_POST['page'],$_POST['rows']);
		$json = array('total'=>$this->num);
		if ($data)
		{
			$json['rows'] = un_iconv($data);
		}else{
			$json['rows'] = array();
		}
		
		echo json_encode($json);
	}
	
	
	/**
	 * 添加
	 */
	function c_add()
	{
		if ($_POST)
		{
			$_POST['date'] = date('Y-m-d H:i:s');
			if ($this->Add(mb_iconv($_POST)))
			{
				echo 1;
			}
		}else{
			$this->show->display('item-add');
		}
	}
	/**
	 * 修改
	 */
	function c_edit()
	{
		if ($_POST)
		{
			$_POST['date'] = date('Y-m-d H:i:s');
			if ($this->Edit(mb_iconv($_POST),$_POST['id']))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	
	/**
	 * 删除
	 */
	function c_del()
	{
		if ($_POST['id'])
		{
			if ($this->Del($_POST['id']))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * 复制模板
	 */
	function c_copy_tpl()
	{
		global $func_limit;
		$years = $_GET['years'] ? $_GET['years'] : $_POST['years'];
		$quarter = $_GET['quarter'] ? $_GET['quarter'] : $_POST['quarter'];
		$to_years = $_GET['to_years'] ? $_GET['to_years'] : $_POST['to_years'];
		$to_quarter = $_GET['to_quarter'] ? $_GET['to_quarter'] : $_POST['to_quarter'];
		if ($this->UpdateTemplateList($years,$quarter,$to_years,$to_quarter,$func_limit['管理部门']))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
	
	/**
	 * 部门
	 */
	function c_dept_data()
	{
		global $func_limit;
		$dept_id = $func_limit['管理部门'] ? $func_limit['管理部门']: $_SESSION['DEPT_ID'];
		$dept = new model_system_dept();
		$data = $dept->DeptTree($dept_id);
		$josn[] = array('dept_id'=>'','dept_name'=>'所有部门');
		if ($data)
		{
			foreach ($data as $key=>$rs)
			{
				$josn[] = array('dept_id'=>$rs['DEPT_ID'],'dept_name'=>($rs['level'] ? str_repeat('|--',$rs['level']) : '').$rs['DEPT_NAME']);
			}
		}
		echo json_encode(un_iconv($josn));
	}
	/**
	 * 部门联动职位 
	 */
	function c_get_jobs()
	{
		$dept_id = isset($_GET['dept_id']) ? $_GET['dept_id'] : $_POST['dept_id'];
		$jobs_obj = new model_system_jobs();
		$jobs = $jobs_obj->JobsList($dept_id);
		$jobs_arr = array();
		if ($_GET['type']=='list')
		{
			$jobs_arr[] = array('jobs_id'=>'','jobs_name'=>'所有职位','selected'=>true);
		}
		if ($jobs)
		{
			foreach ($jobs as $rs)
			{
				$jobs_arr[] = array('jobs_id'=>$rs['id'],'jobs_name'=>$rs['name']);
			}
		}
		
		echo json_encode(un_iconv($jobs_arr));
	}
	/**
	 * 用户
	 */
	function c_get_username_list()
	{
		$user_id_str = $_GET['user_id_str'] ? $_GET['user_id_str'] : $_POST['user_id_str'];
		if ($user_id_str)
		{
			$gl = new includes_class_global();
			$data = $gl->GetUserName(explode(',',$user_id_str));
			$username_str = implode('、',$data);
		}
		echo un_iconv($username_str);
	}
	/**
	 * 部门
	 */
	function c_get_deptname_list()
	{
		$dept_id_str = $_GET['dept_id_str'] ? $_GET['dept_id_str'] : $_POST['dept_id_str'];
		if ($dept_id_str)
		{
			$gl = new includes_class_global();
			$data = $gl->GetDept(explode(',',$dept_id_str),true);
			$deptname_str = implode('、',$data);
		}
		echo un_iconv($deptname_str);
	}
	/**
	 * 区域
	 */
	function c_get_areaname_list()
	{
		$area_id_str = $_GET['area_id_str'] ? $_GET['area_id_str'] : $_POST['area_id_str'];
		if ($area_id_str)
		{
			$gl = new includes_class_global();
			$data = $gl->get_area(explode(',',$area_id_str),true);
			$areaname_str = implode('、',$data);
		}
		echo un_iconv($areaname_str);
	}
	/**
	 * 职位
	 */
	function c_get_jobsname_list()
	{
		$jobs_id_str = $_GET['jobs_id_str'] ? $_GET['jobs_id_str'] : $_POST['jobs_id_str'];
		if ($jobs_id_str)
		{
			$gl = new includes_class_global();
			$data = $gl->GetJobs(explode(',',$jobs_id_str),true);
			$jobsname_str = implode('、',$data);
		}
		echo un_iconv($jobsname_str);
	}
	
	
		function c_listTpl()
	{
		$this->show->display('listTpl');
	}
	/**
	 * 
	 */
	function c_listData()
	{
		/*

		global $func_limit;
		//部门
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		if(!$dept_id)
		{
			$dept_id = $func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID'];
		}
		$condition = '';
		if ($dept_id)
		{
			$condition .='a.dept_id in('.$dept_id.')';
		}
		//年份
		$years = $_GET['years'] ? $_GET['years'] : $_POST['years'];
		if ($years)
		{
			$condition .= ' and a.years='.$years;
		}
		//季度
		$quarter = $_GET['quarter'] ? $_GET['quarter'] : $_POST['quarter'];
		if ($quarter)
		{
			$condition .= ' and a.quarter='.$quarter;
		}
		//关键字
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		if ($keyword)
		{
			$condition .= " and a.name like '%$keyword%'";
		}
		*/

		echo $data = $this->model_administration_appraisal_performance_item_listData();
		
	}

	
	function c_addTpl(){ 
		if ($_POST) {
			 echo $this->model_administration_appraisal_performance_item_addTpl();
		} else {
			$this->show->assign ( 'deptId',$_SESSION['DEPT_ID']);
			$this->show->assign ( 'userId', $_SESSION['USER_ID']);
			$this->show->assign ( 'userName',$_SESSION['USERNAME']);
			$this->show->assign ( 'deptName',$_SESSION['DEPT_NAME']);
			$this->show->assign ( 'areaId',$_SESSION['AREA']);
			$this->show->display ( 'addTpl');
		}
	}
function c_editTpl(){ 
		if ($_POST) {
			 echo $this->model_administration_appraisal_performance_item_editTpl();
		} else {
			$keyId=$_GET['keyId'];
			if($keyId){
				$this->tbl_name = 'appraisal_performance_template';
				$tplInfoData = $this->find ( array ('id' => $keyId) );
			}
			$this->show->assign ( 'keyId',$keyId);
			$this->show->assign ( 'deptId',$tplInfoData['dept_id']);
			$this->show->assign ( 'deptName',$tplInfoData['deptName']);
			$this->show->assign ( 'tplName', $tplInfoData['name']);
			$this->show->assign ( 'tplYear',$tplInfoData['years']);
			$this->show->assign ( 'tplStyle',$tplInfoData['quarter']);
			$this->show->assign ( 'assess',$tplInfoData['assess_userid']);
			$this->show->assign ( 'audit',$tplInfoData['audit_userid']);
			$this->show->assign ( 'userType',$tplInfoData['userType']);
			$this->show->assign ( 'userStr',$tplInfoData['user_id_str']);
			$this->show->assign ( 'tplStyleFlag',$tplInfoData['tplStyleFlag']);
			$this->show->assign ( 'remark',$tplInfoData['remark']);
			$this->show->assign ( 'productNum',$tplInfoData['productNum']);
			$this->show->assign ( 'productNo',$tplInfoData['productNo']);
			$this->show->assign ( 'productNx',$tplInfoData['productNx']);
			$this->show->assign ( 'assessName',$tplInfoData['assessName']);
			$this->show->assign ( 'auditName',$tplInfoData['auditName']);
			$this->show->assign ( 'userStrName',$tplInfoData['userStrName']);
			$this->show->assign ( 'isAss',$tplInfoData['isAss']);
			$this->show->assign ( 'asPers',$tplInfoData['asPers']);
			$this->show->assign ( 'asAss',$tplInfoData['asAss']);
			$this->show->assign ( 'asAudit',$tplInfoData['asAudit']);
			$this->show->display ( 'editTpl');
		}
	}
	function c_tplTypeData()
	{
		
		$josn=array(array('id'=>'','text'=>'所有类型'),array('id'=>1,'text'=>'第一季度'),array('id'=>2,'text'=>'第二季度'),array('id'=>3,'text'=>'第三季度'),array('id'=>4,'text'=>'第四季度'),array('id'=>5,'text'=>'上半年'),array('id'=>6,'text'=>'下半年'),array('id'=>7,'text'=>'全年'));
		echo json_encode(un_iconv($josn));
	}
	
	function c_tplYearData()
	{
		$data[]=array('id'=>'','text'=>'所有年份');
		for($i=(date('Y')-2008);$i>=0;$i--){
		   $data[]=array('id'=>(2009+$i),'text'=>(2009+$i));   	
		}
		echo json_encode(un_iconv($data));
	}
	function c_tplDeptData(){
	    
		echo $this->model_administration_appraisal_performance_item_tplDeptData();		
	}
	function c_userData(){
		echo $this->model_administration_appraisal_performance_item_userData();		
	}
	function c_getGridProjectNameData(){
		echo $this->model_administration_appraisal_performance_item_getGridProjectNameData();	
	}
	function c_getGridColumnsNameData(){
		echo $this->model_administration_appraisal_performance_item_getGridColumnsNameData();	
	}
	function c_getColProjectNameData(){
		echo $this->model_administration_appraisal_performance_item_getColProjectNameData();	
	}
	function c_getGridUserListData(){
		echo $this->model_administration_appraisal_performance_item_getGridUserListData();	
	}
	function c_addTplContent(){
		if ($_POST) {
			 echo $this->model_administration_appraisal_performance_item_addTplContent();
		} else {
			$keyId=$_GET['keyId'];
			if($keyId){
				$this->tbl_name = 'appraisal_performance_template';
				$tplData = $this->find( array ('id' => $keyId) );
				if($tplData['tplStyleFlag']==1){
					$this->tbl_name = 'appraisal_template_columnname';
					$tplColumnData = $this->findAll( array ('tid' => $keyId) );
					if($tplColumnData&&is_array($tplColumnData)){
						  foreach($tplColumnData as $colkey => $_tplColumnData){
							  if($colkey==0){
							  $required='required="true"';
							  $width='width="20%"';
							  }else{
								  $width='width="20%"';
								  $required='';
							  }
							  if($_tplColumnData['columnsName']){
								  $strCol.=<<<EOT
								  <div field="columnName$colkey" $width  headerAlign="center" allowCellValid="true">$_tplColumnData[columnsName]$i
										<textarea property="editor" class="mini-textarea" $required style="width:100%"  allowCellValid="true"></textarea>
								  </div>
EOT;
								}	
						 }
					 }
				  $this->show->assign ( 'tid',$keyId);	
				  $this->show->assign ( 'strCol',$strCol);	 
				  $this->show->display ( 'addTplInContent');
				}else if($tplData['tplStyleFlag']==2){
	                $this->show->assign ( 'tid',$keyId);	
					$this->show->display ( 'addTplHtmlContent');
				}else if($tplData['tplStyleFlag']==3){
					$this->show->assign ( 'tid',$keyId);	
					$this->show->display ( 'addTplExContent');
				}
		    }		
	   }
	}
	function c_addTplHtmlContent(){
		if ($_POST)
		{
			$this->tbl_name = 'appraisal_performance_template';
			$_POST['date'] = date('Y-m-d H:i:s');
			if ($this->Edit(mb_iconv($_POST),$_POST['id']))
			{
				 $this -> tbl_name = 'appraisal_performance_template';
				 $upFlagData=array( 'addTplFlag' =>2);
				 $this -> update ( array ( 'id' =>$_POST['id']) , $upFlagData); 
				echo 2;
			}else{
				echo -1;
			}
		}
		
	}
	
	function c_editTplContent(){
		if ($_POST) {
			 echo $this->model_administration_appraisal_performance_item_addTplContent();
		} else {
			$keyId=$_GET['keyId'];
			if($keyId){
				$this->tbl_name = 'appraisal_performance_template';
				$tplData = $this->find( array ('id' => $keyId) );
				if($tplData['tplStyleFlag']==1||$tplData['tplStyleFlag']==3){
					$this->tbl_name = 'appraisal_template_columnname';
					$tplColumnData = $this->findAll( array ('tid' => $keyId) );
					if($tplColumnData&&is_array($tplColumnData)){
						  foreach($tplColumnData as $colkey => $_tplColumnData){
							  if($colkey==0){
							  $required='required="true"';
							  $width='width="20%"';
							  }else{
								  $width='width="20%"';
								  $required='';
							  }
							  if($_tplColumnData['columnsName']){
								  $strCol.=<<<EOT
								  <div field="columnName$colkey" $width  headerAlign="center" allowCellValid="true">$_tplColumnData[columnsName]$i
										<textarea property="editor" class="mini-textarea" $required style="width:100%"  allowCellValid="true"></textarea>
								  </div>
EOT;
								}	
						 }
					 }
				  $this->show->assign ( 'keyId',$keyId);	
				  $this->show->assign ( 'strCol',$strCol);	 
				  $this->show->display ( 'editTplInContent');
				}else if($tplData['tplStyleFlag']==2){
		          $this->show->assign ( 'keyId',$keyId);	
				  $this->show->display ( 'editTplHtmlContent');
				}else{
					echo 'Error!';
				}
		    }		
	   }
	}
	function c_editTplInConData(){
		$keyId=$_GET['keyId']?$_GET['keyId']:$_POST['keyId'];
		if($keyId){
			 $this->tbl_name = 'appraisal_template_contents';
			 $tplContentData = $this->findAll( array ('tid' => $keyId) );
			 if($tplContentData&&is_array($tplContentData)){				
				 $resultData = array("total"=>count($tplContentData),"data"=>un_iconv($tplContentData));
			 }		
		}
		echo json_encode ( $resultData );
	}
	function c_editTplInContent(){
	   echo $this->model_administration_appraisal_performance_item_editTplInContent();
	  	
	}
		
	function c_getTplHtmlContentData(){
		    $keyId=$_POST['keyId'];
			if($keyId){
				$this->tbl_name = 'appraisal_performance_template';
				$tplData = $this->find( array ('id' => $keyId) );
			}
		echo  un_iconv( $tplData['content']);	
	}
	function c_importTplExContent(){
       if($this->model_administration_appraisal_performance_item_importTplExContent()==2){
       	  showmsg ( '导入成功！', 'parent.CloseOpen();', 'button' );
       }else{
       	  showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
       }
	}
	function c_delTplData(){
		if ($_POST['keys'])
		{   
			$this->tbl_name = 'appraisal_performance_template';
			$idI=explode(',',$_POST['keys']);
			if($idI&&is_array($idI)){
				foreach($idI as $key=>$_id){
					if($_id){
						$flag=$this->Del($_id);
					}		
				}
			 }
			  if ($flag)
			  {
				 echo 2;
			  }else{
				echo 1;
			  }	
		}else{
			
			echo 1;
		}
	}
	function c_delTplContData(){
		if ($_POST['keys'])
		{   
			$this->tbl_name = 'appraisal_template_contents';
			$idI=explode(',',$_POST['keys']);
			if($idI&&is_array($idI)){
				foreach($idI as $key=>$_id){
					if($_id){
						$flag = $this->query ("delete from appraisal_template_columnname  where tId in($_id)");
						$flag = $this->query ("delete from appraisal_template_contents  where tId in($_id)");
					    $this->query ("update appraisal_performance_template set addTplFlag=1  where id in($_id)");
					}		
				}
			 }
			  if ($flag)
			  {
				 echo 2;
			  }else{
				echo 1;
			  }	
		}else{
			
			echo 3;
		}
		
		
	}
	function c_upTplSure(){
		if ($_POST['keys'])
		{   
			$this->tbl_name = 'appraisal_performance_template';
			if($_GET['flag']==2){
				$flag=2;
			}else{
				$flag=3;
			}
			$upFlagData=array( 'addTplFlag' =>$flag);
			$idI=explode(',',$_POST['keys']);
			if($idI&&is_array($idI)){
				foreach($idI as $key=>$_id){
					if($_id){
				 		$flag=$this -> update ( array ( 'id' =>$_id) , $upFlagData); 
					}		
				}
			 }
			  if ($flag)
			  {
				 echo 2;
			  }else{
				echo 1;
			  }	
		}else{
			
			echo 1;
		}
	}
   function c_copyTplData(){
   	echo  $this->model_administration_appraisal_performance_item_copyTplData();
   }
   function c_copyTypeTplData(){
   	echo  $this->model_administration_appraisal_performance_item_copyTypeTplData();
   }
   
   function c_createTplData(){
   	echo  $this->model_administration_appraisal_performance_item_createTplData();
   }
   
   function c_begineUpData(){
   	echo  $this->model_administration_appraisal_performance_item_begineUpData();
   }
   
   function c_onShowTpl(){
			$keyId=$_GET['keyId'];
			if($keyId){
				$this->tbl_name = 'appraisal_performance_template';
				$tplInfoData = $this->find ( array ('id' => $keyId) );
			}
			$this->show->assign ( 'keyId',$keyId);
			$this->show->assign ( 'deptId',$tplInfoData['dept_id']);
			$this->show->assign ( 'deptName',$tplInfoData['deptName']);
			$this->show->assign ( 'tplName', $tplInfoData['name']);
			$this->show->assign ( 'tplYear',$tplInfoData['years']);
			$this->show->assign ( 'tplStyle',$tplInfoData['quarter']);
			$this->show->assign ( 'assess',$tplInfoData['assess_userid']);
			$this->show->assign ( 'audit',$tplInfoData['audit_userid']);
			$this->show->assign ( 'userType',$tplInfoData['userType']);
			$this->show->assign ( 'userStr',$tplInfoData['user_id_str']);
			$this->show->assign ( 'tplStyleFlag',$tplInfoData['tplStyleFlag']);
			$this->show->assign ( 'remark',$tplInfoData['remark']);
			$this->show->assign ( 'productNum',$tplInfoData['productNum']);
			$this->show->assign ( 'productNo',$tplInfoData['productNo']);
			$this->show->assign ( 'productNx',$tplInfoData['productNx']);
			$this->show->assign ( 'assessName',$tplInfoData['assessName']);
			$this->show->assign ( 'auditName',$tplInfoData['auditName']);
			$this->show->assign ( 'userStrName',$tplInfoData['userStrName']);
			$this->show->assign ( 'isAss',$tplInfoData['isAss']);
			$this->show->assign ( 'asPers',$tplInfoData['asPers']);
			$this->show->assign ( 'asAss',$tplInfoData['asAss']);
			$this->show->assign ( 'asAudit',$tplInfoData['asAudit']);
			$this->show->display ( 'showTpl');
	   
	}
  function c_init(){ 
		echo $this->model_administration_appraisal_performance_item_init();
	} 
   
  
}
?>