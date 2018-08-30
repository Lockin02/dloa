<?php
/**
 * @author Administrator
 * @Date 2012年5月25日 星期五 15:21:04
 * @version 1.0
 * @description:人事管理-基本信息控制层
 */
class controller_hr_personnel_personnel extends controller_base_action {

	function __construct() {
		$this->objName = "personnel";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * 跳转到人事管理-基本信息列表
	 */
    function c_page() {
      $this->view('list');
    }

    /*
     * 跳转到员工等级信息
     */
	function c_toDegree(){
		$this->view('degree');
	}


    /**
	 * 跳转到人事管理-离职员工档案信息
	 */
    function c_toLeaveList() {
      $this->view('leave-list');
    }

	/**
	 * 发送邮件页面
	 */
	function c_toSendEmail(){
		$this->assign("mailServiceNo",'mail'.date("YmdHis"));
		$this->view('sendemail');
	}

	/**
	 * 跳转到人事管理-部门员工档案信息
	 */
    function c_toDeptList() {
		$obj = $this->service->getDeptInfo($_SESSION['USER_ID']);
		$this->assign("companyName",$obj['companyName']);
		$this->assign("deptIdS",$obj['deptIdS']);
		$this->assign("deptIdT",$obj['deptIdT']);
	    $this->view('dept-list');
    }

	/**
	 * 跳转到人事管理-部门助理列表
	 */
    function c_toDeptAssistant() {
      $this->view('deptassistant-list');
    }

	/**
	 * 跳转到人事管理-基本信息列表
	 */
    function c_toMyList() {
      $this->view('my-list');
    }

	/**
	 * 跳转到人事管理-聯系方式
	 */
    function c_toContactList() {
      $this->view('contact-list');
    }


	/*
	 * 跳转到员工等级息查看页面
	 */
	function c_toDegreeView(){
		$obj=$this->service->get_d($_GET['id']);
		foreach($obj as $key => $val){
			$this->assign($key,$val);
		}
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if($photo=="oa_attachment//"){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$this->view('degreeview');
	}


	/**
	 * 列表高级查询
	 */
	function c_toSearch(){
		$otherDao=new model_common_otherdatas();     //新建otherdatas对象
		$this->showDatadicts ( array ('employeesStateName' => 'HRYGZT' ) );
		$this->showDatadicts ( array ('personnelTypeName' => 'HRYGLX' ) );
		$this->showDatadicts ( array ('positionName' => 'HRGWFL' ) );
		$this->showDatadicts ( array ('personnelClassName' => 'HRRYFL' ) );
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ));
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select());
		$arr=$otherDao->getCompanyAndAreaInfo();   //获得所有公司和公司所属区域
		for($i=0;$i<count($arr);$i++){
			$companyOpt =$companyOpt."<option>".$arr[$i]['NameCN']."</option>";  //拼凑option标签
		}
		$this->assign('companyOpt',$companyOpt);     //将所有公司添加到select标签

        $this->view('search');
	}

	/**
	 * 查看页面 - 部门权限
	 */
	function c_pageForRead(){
		$this->view('listforread');
	}
	/**我的档案信息列表显示方法
	*author can
	*2010-12-28
	*/
	function c_myPageJson(){
		$service=$this->service;
		$service->getParam($_POST);
		$service->asc = true;
		$service->searchArr['userAccount']=$_SESSION['USER_ID'];
		$rows=$service->pageBySqlId();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *author can
	 *2010-12-28
	 */
	function c_pageJson(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );

		$rows = $service->page_d ();
		$rows = $this->sconfig->md5Rows ( $rows ); //数据加入安全码

		//判断员工是否有导师
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$stuId = $v['userAccount'];
				$tutorId = $service->getTutorBystuId($stuId);
				if(empty($tutorId)){
					$rows[$k]['isTut'] = 0;
				}else{
					$rows[$k]['isTut'] = 1;
				}
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 人事档案列表
	 */
	 function c_personnelPageJson(){
		$service = $this->service;
		$service->getParam ( $_REQUEST ); //设置前台获取的参数信息

		$comLimit = $service->this_limit['公司权限'];
		$comLimitArr = array();
		if(!strstr($comLimit,';;')&&!empty($comLimit)){
			$comLimitArr = explode(',',$comLimit);
			foreach($comLimitArr as $key=>$val){
				$nameCN .=$this->service->get_table_fields('branch_info',"NamePt='".$val."'",'NameCN').",";
			}
			$nameCN = substr_replace($nameCN,'','-1'); //去掉最后一个逗号
			$service->searchArr['companyNameI']=$nameCN;
		} else if(empty($comLimit)){
			$service->searchArr['companyNameI']='0';
		}

		$service->groupBy = 'c.id';
		$rows = $service->page_d ('select_personnelList');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
        //判断员工是否有导师
        if(is_array($rows)){
	        foreach($rows as $k=>$v){
	           if(empty($v['recordsId'])){
	           	  $rows[$k]['isTut'] = 0;
	           }else{
	           	  $rows[$k]['isTut'] = 1;
	           }
	        }
        }
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *author can
	 *2010-12-28
	 */
	function c_contractPageJson(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );

		$rows = $service->page_d ('select_contactInformation');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		//判断员工是否有导师
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k]['findCompanyEmail']=$this->service->get_table_fields('user', "USER_ID='".$v['userAccount']."'", 'EMAIL');
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonForRead() {
		$service = $this->service;
		$rows = array();
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//系统权限
		$sysLimit = $this->service->this_limit['部门权限'];

		//办事处 － 全部 处理
		if(strstr($sysLimit,';;')){
			$service->getParam($_POST); //设置前台获取的参数信息
			$service->groupBy = 'c.id';
			$rows = $service->page_d('select_personnelList');

		}else if(!empty($sysLimit)){//如果没有选择全部，则进行权限查询并赋值
			$_POST['belongDeptId'] = $sysLimit;
			$service->getParam($_POST); //设置前台获取的参数信息
			$service->groupBy = 'c.id';
			$rows = $service->page_d('select_personnelList');
		}
        //判断员工是否有导师
        if(is_array($rows)){
	        foreach($rows as $k=>$v){
//	           $stuId = $v['userAccount'];
//	           $tutorId = $service->getTutorBystuId($stuId);
	           if(empty($v['recordsId'])){
	           	  $rows[$k]['isTut'] = 0;
	           }else{
	           	  $rows[$k]['isTut'] = 1;
	           }
	        }
        }

		//其余信息加载
		if(!empty($rows)){
			$rows = $this->sconfig->md5Rows ( $rows );
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到新增人事管理-基本信息页面
	 */
	function c_toAdd() {
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photo","images/no_pic.gif");
		$this->assign("photoUrl",$str);
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select());
		$this->showDatadicts ( array ('healthStateCode' => 'HRJKZK' ));
		$this->showDatadicts ( array ('politicsStatusCode' => 'HRZZMM' ));
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ));
		$this->showDatadicts ( array ('englishSkill' => 'HRYYDJ' ));
		$this->showDatadicts ( array ('employeesState' => 'HRYGZT' ));
		$this->showDatadicts ( array ('personnelType' => 'HRYGLX' ));
		$this->showDatadicts ( array ('position' => 'HRGWFL' ));
		$this->showDatadicts ( array ('personnelClass' => 'HRRYFL' ));
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ));
		$this->showDatadicts ( array ('staffState' => 'YGZTZZ' ));
		$this->showDatadicts ( array ('functionCode' => 'HRYGZN' ));
		$this->showDatadicts ( array ('technologyCode' => 'HRJSLY' ));
		$this->showDatadicts ( array ('networkCode' => 'HRFWWL' ));
		$this->showDatadicts ( array ('deviceCode' => 'HRSBDJ' ));
		$this->showDatadicts ( array ('outsourcingCode' => 'HTWBFS' ));
		$this->showDatadicts ( array ('salaryAreaTypeCode' => 'HRGZDQDM' ));
		$this->view ('add' ,true);
	}

   /**
	 * 跳转到新增人事管理-基本信息页面
	 */
	function c_toAddByEntryNotice() {
		$applyId = isset($_GET['applyId']) ? $_GET['applyId'] : "";
		$entryId = isset($_GET['entryId']) ? $_GET['entryId'] : "";
		$resumeId = isset($_GET['resumeId']) ? $_GET['resumeId'] : "";
		$str = str_replace(WEB_TOR ,'' ,UPLOADPATH);
		if(substr($str ,0 ,1) == "/") {
			$str = substr($str,1);
		}
		$resumeDao = new model_hr_recruitment_resume();
		$resumeRow = $resumeDao->get_d($resumeId);
		$applyDao = new model_hr_recruitment_employment();
		$obj = $applyDao->get_d($applyId);
		if ($obj['companyType'] == '集团') {
			$this->assign ( 'companyTypeCode', 1 );
		} else if ($obj['companyType'] == '子公司') {
			$this->assign ( 'companyTypeCode', '0' );
		}
		if(is_array($obj)){
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			if ($obj ['isMedicalHistory'] == '是') {
				$this->assign ( 'isYes', 'checked' );
			} else if ($obj ['isMedicalHistory'] == '否') {
				$this->assign ( 'isNo', 'checked' );
			}
			$this->assign("employmentId" ,$obj['id']);
		} else {
			$this->assign("professionalName" ,'');
			$this->assign("appointAddress" ,'');
			$this->assign("appointPost" ,'');
			$this->assign("employmentId" ,'');
			$this->assign("birthdate" ,"");
			$this->assign("age" ,"");
			$this->assign("identityCard" ,"");
			$this->assign("highSchool" ,"");
			$this->assign("InfectDiseases" ,"");
			$this->assign("height" ,"");
			$this->assign("weight" ,"");
			$this->assign("blood" ,"");
			$this->assign("hobby" ,"");
			$this->assign("professional" ,"");
			$this->assign("bankCardNum" ,"");
			$this->assign("accountNumb" ,"");
			$this->assign("openingBank" ,"");
			$this->assign("archivesCode" ,"");
			$this->assign("archivesLocation" ,"");
			$this->assign("telephone" ,"");
			$this->assign("mobile" ,"");
			$this->assign("personEmail" ,"");
			$this->assign("QQ" ,"");
			$this->assign("MSN" ,"");
			$this->assign("fetion" ,"");
			$this->assign("information" ,"");
			$this->assign("homePhone" ,"");
			$this->assign("emergencyName" ,"");
			$this->assign("emergencyTel" ,"");
			$this->assign("emergencyRelation" ,"");
			$this->assign("nowAddress" ,"");
			$this->assign("nowPost" ,"");
			$this->assign("homeAddress" ,"");
			$this->assign("homePost" ,"");
			$this->assign("nation" ,"");
		}
		$entryNoticeDao = new model_hr_recruitment_entryNotice();
		$entryNoticeRow = $entryNoticeDao->get_d($entryId);
		$this->assign("photo","images/no_pic.gif");
		$this->assign("photoUrl",$str);
		$this->assign("jobName",$entryNoticeRow['hrJobName']);
		$this->assign("jobId",$entryNoticeRow['hrJobId']);
		$this->assign("jobId",$entryNoticeRow['hrJobId']);
		$this->assign("socialPlace",$entryNoticeRow['socialPlace']);
		$this->assign("staffName",$entryNoticeRow['userName']);
		$this->assign("userAccount",$entryNoticeRow['userAccount']);
		$this->assign("belongDeptName",$entryNoticeRow['deptName']);
		$this->assign("belongDeptId",$entryNoticeRow['deptId']);
		$this->assign("belongDeptCode",$entryNoticeRow['deptCode']);
		$this->assign("personLevel",$entryNoticeRow['personLevel']);
		$this->assign("personLevelId",$entryNoticeRow['personLevelId']);
		$this->assign("eprovinceId",$entryNoticeRow['eprovinceId']);
		$this->assign("eprovince",$entryNoticeRow['eprovince']);
		$this->assign("ecityId",$entryNoticeRow['ecityId']);
		$this->assign("ecity",$entryNoticeRow['ecity']);
		$deptDao = new model_deptuser_dept_dept();
		$levelflag = $this->service->get_table_fields('department', "DEPT_ID='".$entryNoticeRow['deptId']."'", 'levelflag');
		$deptRow = $deptDao->getSuperiorDeptById_d($entryNoticeRow['deptId'],$levelflag);
		foreach ( $deptRow as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->assign("entryId",$entryId);
		$area = new includes_class_global();
		$this->assign('area_select',$area->area_select());
		$this->showDatadicts ( array ('healthStateCode' => 'HRJKZK' ), $obj ['healthStateCode'] );
		$this->showDatadicts ( array ('politicsStatusCode' => 'HRZZMM' ), $obj ['politicsStatusCode'] );
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ), $obj ['highEducation'] );
		$this->showDatadicts ( array ('englishSkill' => 'HRYYDJ' ), $obj ['englishSkill'] );
		$this->showDatadicts ( array ('employeesState' => 'HRYGZT' ));
		$this->showDatadicts ( array ('personnelType' => 'HRYGLX' ));
		$this->showDatadicts ( array ('position' => 'HRGWFL' ));
		$this->showDatadicts ( array ('personnelClass' => 'HRRYFL' ));
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ));
		$this->showDatadicts ( array ('staffState' => 'YGZTZZ' ));
		$this->showDatadicts ( array ('functionCode' => 'HRYGZN' ));
		$this->showDatadicts ( array ('technologyCode' => 'HRJSLY' ));
		$this->showDatadicts ( array ('networkCode' => 'HRFWWL' ));
		$this->showDatadicts ( array ('deviceCode' => 'HRSBDJ' ));
		$this->showDatadicts ( array ('salaryAreaTypeCode' => 'HRGZDQDM' ));
		if(is_array($resumeRow)) {
			$this->assign("speciality",$resumeRow['specialty']);
		} else {
			$this->assign("speciality","");
		}
		$this->view ('entrynotice-add' ,true);
	}

   /**
	 * 跳转到编辑人事管理-基本信息页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isMedicalHistory'] == '是') {
			$this->assign ( 'isYes', 'checked' );
		} else if ($obj ['isMedicalHistory'] == '否') {
			$this->assign ( 'isNo', 'checked' );
		}
		if ($obj ['isAddScheme'] == '是') {
			$this->assign ( 'isAddYes', 'checked' );
		} else if ($obj ['isAddScheme'] == '否') {
			$this->assign ( 'isAddNo', 'checked' );
		}
		 //获取照片
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
        $this->show->assign("file",$this->service->getFilesByObjId( $_GET ['id'],true,'personnel_staff'));
		$this->assign("photoUrl",$str);
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select());
		$this->showDatadicts ( array ('healthStateCode' => 'HRJKZK' ), $obj ['healthStateCode'] );
		$this->showDatadicts ( array ('politicsStatusCode' => 'HRZZMM' ), $obj ['politicsStatusCode'] );
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ), $obj ['highEducation'] );
		$this->showDatadicts ( array ('englishSkill' => 'HRYYDJ' ), $obj ['englishSkill'] );
		$this->showDatadicts ( array ('employeesState' => 'HRYGZT' ), $obj ['employeesState'] );
		$this->showDatadicts ( array ('personnelType' => 'HRYGLX' ), $obj ['personnelType'] );
		$this->showDatadicts ( array ('position' => 'HRGWFL' ), $obj ['position'] );
		$this->showDatadicts ( array ('personnelClass' => 'HRRYFL' ), $obj ['personnelClass'] );
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] );
		$this->showDatadicts ( array ('functionCode' => 'HRYGZN' ), $obj ['functionCode']);
		$this->showDatadicts ( array ('technologyCode' => 'HRJSLY' ), $obj ['technologyCode']);
		$this->showDatadicts ( array ('networkCode' => 'HRFWWL' ), $obj ['networkCode']);
		$this->showDatadicts ( array ('deviceCode' => 'HRSBDJ' ), $obj ['deviceCode']);
		$this->showDatadicts ( array ('outsourcingCode' => 'HTWBFS' ), $obj ['outsourcingCode']);
		$this->showDatadicts ( array ('salaryAreaTypeCode' => 'HRGZDQDM' ), $obj ['salaryAreaTypeCode']);
		if($obj ['employeesState']=="YGZTZZ"){
			$this->showDatadicts ( array ('staffState' => 'YGZTZZ' ), $obj ['staffState'] );
		}else if($obj ['employeesState']=="YGZTLZ"){
			$this->showDatadicts ( array ('staffState' => 'YGZTLZ' ), $obj ['staffState'] );
		}
      	$this->view ( 'edit');
   }


   /*
    * 跳转到员工等级信息修改页面
    */
	function c_toDegreeEdit(){
		$obj=$this->service->get_d($_GET['id']);
		foreach($obj as $key => $val){
			$this->assign($key,$val);
		}
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if($photo=="oa_attachment//"){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$this->showDatadicts ( array ('technologyCode' => 'HRJSLY' ), $obj ['technologyCode']);
		$this->showDatadicts ( array ('networkCode' => 'HRFWWL' ), $obj ['networkCode']);
		$this->showDatadicts ( array ('deviceCode' => 'HRSBDJ' ), $obj ['deviceCode']);
		$this->showDatadicts ( array ('outsourcingCode' => 'HTWBFS' ), $obj ['outsourcingCode']);
		$this->view('degreeedit');
	}

   /**
	 * 跳转到编辑人事管理-基本信息页面
	 */
	function c_toMyEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isMedicalHistory'] == '是') {
			$this->assign ( 'isYes', 'checked' );
		} else if ($obj ['isMedicalHistory'] == '否') {
			$this->assign ( 'isNo', 'checked' );
		}
		 //获取照片
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
        $this->show->assign("file",$this->service->getFilesByObjId( $_GET ['id'],false,'personnel_staff'));
		$this->showDatadicts ( array ('healthStateCode' => 'HRJKZK' ), $obj ['healthStateCode'] );
		$this->showDatadicts ( array ('politicsStatusCode' => 'HRZZMM' ), $obj ['politicsStatusCode'] );
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ), $obj ['highEducation'] );
		$this->showDatadicts ( array ('englishSkill' => 'HRYYDJ' ), $obj ['englishSkill'] );
      $this->view ( 'my-edit');
   }

   /**
	 * 跳转到查看人事管理-基本信息页面
	 */
	function c_toView() {
      	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		 //获取照片
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if($photo=="oa_attachment//"){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
	  $this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false,'personnel_staff' ) );
      $this->view ( 'view' );
   }
	/**
	 * 跳转到查看人事管理-部门助理列表-基本信息页面
	 */
	function c_toDeptAssistantView() {
      	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		 //获取照片
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if($photo=="oa_attachment//"){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
	  $this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false,'personnel_staff' ) );
      $this->view ( 'deptassistant-view' );
   }

   /**
	 * 联系信息查看页面
	 *
	 */
	 function c_toContactView(){
      	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$companyEmail=$this->service->get_table_fields('user', "USER_ID='".$obj['userAccount']."'", 'EMAIL');
		$this->assign('compEmail',$companyEmail);
	    $this->view ( 'contact-view' );

	 }

	/**
	 * 入职信息查看页面
	 */
	function c_toInleaveView(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('createDate' ,substr($obj['createTime'] ,0 ,10)); //建档时间
		$this->assign('isBack' ,$obj['isBack'] == 0 ? '否' : '是'); //是否黑名单
		$this->view ( 'inleave-view' );
	}

	/**
	 * 入职信息修改页面
	 */
	function c_toInleaveEdit(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('quitTypeCode' => 'YGZTLZ' ), $obj ['quitTypeCode'] );//HRLZLX
		$this->assign('createDate' ,substr($obj['createTime'] ,0 ,10)); //建档时间
		$this->view('inleave-edit' ,true);
	}

	/**
	 * 部门助理列表-入职信息查看页面
	 */
	function c_toDeptAssistantInleaveView(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'deptassistantinleave-view' );
	}

	/**
	 * 人员信息查看Tab页面
	 */
	 function c_toTabView(){
      	$this->permCheck (); //安全校验
      	$id=isset($_GET ['id'])?$_GET ['id']:'';
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';
      	//获取人员盘点查看权限
//      	$otherdatasDao=new model_common_otherdatas();
//      	$inventoryLimit=$otherdatasDao->getUserPriv('hr_inventory_inventorydetail');
		$interviewId="";
		$entrynotice = new model_hr_recruitment_entryNotice();
		$entrynoticeData = $entrynotice->findAll(array("userNo"=>$userNo));
		if($entrynoticeData){
			foreach($entrynoticeData as $k=>$v){
				if($v['parentId']){
					$interviewId=$v['parentId'];
					break;
				}
			}
		}
		$this->assign ( 'inventoryLimit', 1 );
		$this->assign ( 'id', $id );
		$this->assign ( 'userNo', $userNo );
		$this->assign ( 'interviewId', $interviewId );
		$this->assign ( 'userName', $userName );
		$this->assign ( 'userAccount', $userAccount );
		$this->view ( 'tab-view' );
	}

	/**
	 * 我的档案修改Tab页面
	 */
	 function c_toMyTabEdit(){
      	$this->permCheck (); //安全校验
      	$id=isset($_GET ['id'])?$_GET ['id']:'';
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';
      	$this->assign ( 'id', $id );
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
	    $this->view ( 'my-tab-edit' );

	 }

   /**
	 * 档案修改Tab页面
	 *
	 */
	 function c_toTabEdit(){
      	$this->permCheck (); //安全校验
      	$id=isset($_GET ['id'])?$_GET ['id']:'';
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';
      	$this->assign ( 'id', $id );
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
	    $this->view ( 'tab-edit' );

	 }

   /**
	 * 部门助理列表-查看Tab页面
	 *
	 */
	 function c_toDeptAssistantTabView(){
		$this->permCheck (); //安全校验
      	$id=isset($_GET ['id'])?$_GET ['id']:'';
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';
		$this->assign ( 'inventoryLimit', 1 );
      	$this->assign ( 'id', $id );
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
	    $this->view ( 'deptassistant-tab-view' );

	 }

   /**
	 * 网络服务部列表-查看Tab页面
	 *
	 */
	 function c_toDegreeTabView(){
		$this->permCheck (); //安全校验
      	$id=isset($_GET ['id'])?$_GET ['id']:'';
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';
		$this->assign ( 'inventoryLimit', 1 );
      	$this->assign ( 'id', $id );
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
	    $this->view ( 'degree-tab-view' );

	 }
/**
	 * 部门助理列表-按钮“更多”-查看Tab页面
	 *
	 */
	 function c_toViewMoreTabView(){
		$this->permCheck (); //安全校验
      	$id=isset($_GET ['id'])?$_GET ['id']:'';
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';
		$this->assign ( 'inventoryLimit', 1 );
      	$this->assign ( 'id', $id );
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
	    $this->view ( 'viewmore-tab-view' );

	 }

	 /**
	 * 我的联系信息修改页面
	 *
	 */
	 function c_toMyContactEdit(){
      	$id=isset($_GET ['id'])?$_GET ['id']:'';
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$companyEmail=$this->service->get_table_fields('user', "USER_ID='".$obj['userAccount']."'", 'EMAIL');
		$this->assign('compEmail',$companyEmail);
	    $this->view ( 'mycontact-edit' );

	 }

	 /**
	 *联系信息修改
	 *
	 */
	 function c_editMyContact(){
		$flag=$this->service->editContact_d($_POST['contact'],true);
		if($flag){
			msgGo('保存成功');
		}else{
			msgGo('保存失败');

		}
	 }

	 /*
	  * 网络服务部信息修改
	  */
	 function c_degreeEdit(){
	 	$id=$this->service->degreeEdit($_POST[$this->objName]);
		if($id){
			msgGo('保存成功');
		}else{
			msgGo('保存失败');
		}
	 }

	 /*
	  * 发送邮件
	  */
	 function c_sendEmail(){
	 	$flag=$this->service->sendEmail_d($_POST[$this->objName]);
		if($flag){
			msg('发送成功');
		}else{
			msg('发送失败');
		}
	 }


	 /**
	 *我的基本信息修改
	 *
	 */
	 function c_myEdit(){
		$flag=$this->service->edit_d($_POST['personnel']);
		if($flag){
			msgGo('保存成功');
		}else{
			msgGo('保存失败');

		}
	 }


	/**
	 *入离职信息修改
	 */
	function c_inLeaveEdit(){
		$this->checkSubmit();
		$flag = $this->service->editInLeave_d($_POST['personnel']);
		if($flag){
			msgGo('保存成功');
		} else {
			msgGo('保存失败');
		}
	}

	/**
	 * 基本信息新增
	 */
	 function c_add(){
	 	$this->checkSubmit(); //检验是否重复提交
		$flag = $this->service->add_d($_POST['personnel']);
		if($flag){
			msg('保存成功');
		} else {
			msg('保存失败');
		}
	 }

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
	 * @author Administrator
	 *
	 */
	function c_getPersonnelInfo(){
		$userAccount=$_POST['userAccount'];
		$personnelRow=$this->service->getPersonnelInfo_d($userAccount);
        echo util_jsonUtil::encode ($personnelRow);

	}

	/*
	 * 根据账号获得员工信息
	 */
	 function c_getPersonnelSimpleInfo_d(){
	 	$userAccount=$_POST['userAccount'];
	 	$personnelRow=$this->service->getPersonnelSimpleInfo_d($userAccount);
	 	echo util_jsonUtil::encode ($personnelRow);
	 }


	/******************  员工转正部分 *************************/

    /**
     * 转正列表
     * create by kuangzw
     * create on kuangzw
     * TODO
     */
    function c_listWaitEntry(){
        $this->view('listwaitentry');
    }

    /**
     * 转正json
     * create by kuangzw
     * create on kuangzw
     */
    function c_pageJsonWaitEntry(){
        $service = $this->service;

		$_POST['staffState'] = 'YGZTSY';

		//系统权限
		$sysLimit = $this->service->this_limit['部门权限'];

		//办事处 － 全部 处理
		if(strstr($sysLimit,';;')){
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d('select_waitentry');

		}else if(!empty($sysLimit)){//如果没有选择全部，则进行权限查询并赋值
			$_POST['deptIdArr'] = $sysLimit;
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d('select_waitentry');
		}

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 录入部门建议
     */
    function c_toDeptSuggest(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		$thisDeptId = empty($obj['deptIdS']) ? $obj['deptIdT'] : $obj['deptIdS'];
		$thisDeptName = empty($obj['deptNameS']) ? $obj['deptNameT'] : $obj['deptNameS'];
		$this->assign('deptId',$thisDeptId );
		$this->assign('deptName',$thisDeptName );

		$this->showDatadicts ( array ('deptSuggest' => 'HRBMJY' ), $obj ['deptSuggest']);

		//人员邮件渲染
		$mailInfo = $this->service->getMailInfo_d();
		$this->assignFunc($mailInfo);

		//获取面试评估表信息
		$entryNoticeDao = new model_hr_recruitment_entryNotice();
		$entryInfo = $entryNoticeDao->getInfoByUserNo_d('00001270');
//		$entryInfo = $entryNoticeDao->getInfoByUserNo_d($obj['userNo']);
		if($entryInfo){
			$this->assign('beforeSalary',$entryInfo['useTrialWage']);
			$this->assign('afterSalary',$entryInfo['useFormalWage']);
			if(!empty($entryInfo['personLevel'])){
				$this->assign('personLevel',$entryInfo['personLevel']);
				$this->assign('personLevelId',$entryInfo['personLevelId']);
			}
		}else{
			$this->assign('beforeSalary','0');
			$this->assign('afterSalary','');
		}

		$this->view('deptsuggest');
    }

    /**
     * 录入部门建议
     */
    function c_deptSuggest(){
		$object = $_POST;
		$suggestId = $this->service->deptSuggest_d($object);
		if($suggestId){
			if(isset($_GET['act']) && $_GET['act'] == 'audit'){
				succ_show('controller/hr/trialplan/ewf_index.php?actTo=ewfSelect&billId=' . $suggestId);
			}else{
				msg('确认成功');
			}
		}
    }

    /******************* 工程使用部分 **************************/
    /**
     * 工程人员列表
     * create by kuangzw
     * create on kuangzw
     * TODO
     */
    function c_listEngineering(){
        $this->showDatadicts(array('employeesState' => 'HRYGZT'),'YGZTZZ');
        $this->assign('searchDate',day_date);
        $this->assignFunc($this->service->getDefaultDept_d());
        $this->view('listengineering');
    }

    /**
     * 工程人员json
     * create by kuangzw
     * create on 2012-08-08
     */
    function c_listJsonEngineering(){
        $searchDate = $_POST['searchDate'];//日期
        $employeesState = $_POST['employeesState'];//人员状态
        //数据获取
        $rows = $this->service->listEngineering_d($searchDate,$employeesState);
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }
    /**
     * 工程人员导出
     * create by liangjj
     * create on 2013-08-26
     */
    function c_outExcelEngineering(){
    	$service = $this->service;
    	$searchDate = $_GET['searchDate'];//日期
    	$employeesState = $_GET['employeesState'];//人员状态
    	//数据获取
    	$service->searchArr['belongDeptId'] = $_GET['belongDeptId'];
    	$service->getParam($_GET); //设置前台获取的参数信息
    	$rows = $this->service->listEngineering_d($searchDate,$employeesState);
		//数据合并处理
		$rows = $this->dataFiter($rows);

    	$dao = new model_hr_personnel_personnelExcelUtil();
    	$dao->excelOutPersonInfo('20',$rows);
    }

    /**
     * 工程人员pagejson
     */
    function c_pageJsonEngineering(){
        $service = $this->service;
        $searchDate = $_POST['searchDate'];//日期
        $employeesState = $_POST['employeesState'];//人员状态
        unset($_POST['searchDate']);
        unset($_POST['employeesState']);

		$service->getParam($_POST); //设置前台获取的参数信息
        $sql = $this->service->listEngineering_d($searchDate,$employeesState,true);
		$rows = $service->pageBySql($sql);

		//数据合并处理
		$rows = $this->dataFiter($rows);

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 数据处理
     */
    function dataFiter($rows){
		if($rows){
			foreach($rows as $key => $val){
				if(!empty($val['projectName'])) $rows[$key]['projectName'] = implode('/',array_unique(explode('/',$val['projectName'])));
				if(!empty($val['workStatus'])) $rows[$key]['workStatus'] = implode('/',array_unique(explode('/',$val['workStatus'])));
				if(!empty($val['projectEndDate'])) $rows[$key]['projectEndDate'] = implode('/',array_unique(explode('/',$val['projectEndDate'])));
			}
		}
		return $rows;
    }

    /**
     * 获取人员信息和人员等级信息
     * create by kuangzw
     * create on kuangzw
     */
    function c_getPersonnelAndLevel(){
        $userAccount=$_POST['userAccount'];
        $personnelRow=$this->service->getPersonnelAndLevel_d($userAccount);
        echo util_jsonUtil::encode ($personnelRow);
    }

    /**
     * 判断是否已生成人员档案
     */
    function c_isAddPersonnel(){
        $userAccount=$_POST['userNo'];
        $flag=$this->service->isAddPersonnel_d($userAccount);
        if($flag){
        	echo 1;
        }else{
        	echo 0;
        }
    }

	/******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

		/**
	 * 导入excel-更新人员档案
	 */
	function c_toExcelEdit(){
		$this->display('exceledit');
	}
	/**
	 * 导入联系信息excel
	 */
	function c_toContactExcelIn(){
		$this->display('contact-excelin');
	}
	/**
	 * 导入入离职信息excel
	 */
	function c_toInLeaveExcelIn(){
		$this->display('inleave-excelin');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '基本信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 导入excel-更新基本信息
	 */
	function c_excelEdit(){
		set_time_limit(0);
		$resultArr = $this->service->editExecelData_d ();

		$title = '基本信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 导入联系信息excel
	 */
	function c_contactExcelIn(){
		$resultArr = $this->service->addContactExecelData_d ();

		$title = '联系信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 联系信息导出
	 */
	function c_toContactExcelOut(){
		$this->showDatadicts ( array ('staffState' => 'YGZTZZ' ));
		$this->showDatadicts ( array ('employeesState' => 'HRYGZT' ));
		$this->view("contact-excelout");
	}

	/**
	 * 联系信息导出
	 */
	function c_contactExcelOut(){
		$object = $_POST[$this->objName];

		if(!empty($object['belongDeptId']))
	 		$this->service->searchArr['belongDeptId'] = $object['belongDeptId'];
		if(!empty($object['deptId']))
	 		$this->service->searchArr['deptId'] = $object['deptId'];
		if(!empty($object['deptIdS']))
	 		$this->service->searchArr['deptIdS'] = $object['deptIdS'];
		if(!empty($object['deptIdT']))
	 		$this->service->searchArr['deptIdT'] = $object['deptIdT'];
		if(!empty($object['deptIdF']))
	 		$this->service->searchArr['deptIdF'] = $object['deptIdF'];
		if($object['companyTypeCode'] != '') {
			if($object['companyTypeCode'] == 1) {
				$companyType = '集团';
			}else{
				$companyType = '子公司';
			}
	 		$this->service->searchArr['companyType'] = $companyType;
		}
		if(!empty($object['companyName']))
	 		$this->service->searchArr['companyName'] = $object['companyName'];
		if(!empty($object['jobId']))
			$this->service->searchArr['jobId'] = $object['jobId'];
		if(!empty($object['employeesState']))
	 		$this->service->searchArr['employeesState'] = $object['employeesState'];
		if(!empty($object['staffState']))
			$this->service->searchArr['staffState'] = $object['staffState'];

		set_time_limit(0);
		$this->service->groupBy = "c.id";
		$rows = $this->service->list_d('select_personnelExport');
		$exportData = array();
		foreach ( $rows as $key => $val ){
			$exportData[$key]['userNo'] = $val['userNo'];
			$exportData[$key]['staffName'] = $val['staffName'];
			$exportData[$key]['telephone'] = $val['telephone'];
			$exportData[$key]['mobile'] = $val['mobile'];
			$exportData[$key]['personEmail'] = $val['personEmail'];
			$exportData[$key]['compEmail'] = $val['compEmail'];
			$exportData[$key]['QQ'] = $val['QQ'];
			$exportData[$key]['MSN'] = $val['MSN'];
			$exportData[$key]['fetion'] = $val['fetion'];
			$exportData[$key]['information'] = $val['information'];
			$exportData[$key]['homePhone'] = $val['homePhone'];
			$exportData[$key]['emergencyName'] = $val['emergencyName'];
			$exportData[$key]['emergencyTel'] = $val['emergencyTel'];
			$exportData[$key]['emergencyRelation'] = $val['emergencyRelation'];
			$exportData[$key]['nowPlacePro'] = $val['nowPlacePro'];
			$exportData[$key]['nowPlaceCity'] = $val['nowPlaceCity'];
			$exportData[$key]['nowAddress'] = $val['nowAddress'];
			$exportData[$key]['nowPost'] = $val['nowPost'];
			$exportData[$key]['homeAddressPro'] = $val['homeAddressPro'];
			$exportData[$key]['homeAddressCity'] = $val['homeAddressCity'];
			$exportData[$key]['homeAddress'] = $val['homeAddress'];
			$exportData[$key]['homePost'] = $val['homePost'];
			$exportData[$key]['unitPhone'] = $val['unitPhone'];
			$exportData[$key]['extensionNum'] = $val['extensionNum'];
			$exportData[$key]['unitFax'] = $val['unitFax'];
			$exportData[$key]['mobilePhone'] = $val['mobilePhone'];
			$exportData[$key]['shortNum'] = $val['shortNum'];
			$exportData[$key]['otherPhone'] = $val['otherPhone'];
			$exportData[$key]['otherPhoneNum'] = $val['otherPhoneNum'];
		}
		return $this->service->contactExcelOut ( $exportData );
	}


	/**
	 * 导入联系信息excel
	 */
	function c_inleaveExcelIn(){
		$resultArr = $this->service->addInleaveExecelData_d ();

		$title = '入离职信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 导入员工附件
	 */
	function c_importStaffFile(){
		try{
			set_time_limit(0);
			$res = $this->service->importStaffFile ();
			$title="附件导入结果列表";
			$thead=array('附件','导入结果');
		echo util_excelUtil::showResult($res,$title,$thead);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}

	/*
	 * 跳转到导出页面
	 */
	function c_toExcelOut(){
		$this->showDatadicts(array('employeesState' => 'HRYGZT'),null,true);
		$this->showDatadicts(array('personnelType' => 'HRYGLX'),null,true);
		$this->view("degree-excelout");
	}



	/*
	 * 员工等级信息导出
	 */
	 function c_degreeExcelOut(){
		$object = $_POST[$this->objName];
		//print_r($object);
		if(!empty($object['userNo']))
	 		$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['companyName']))
	 		$this->service->searchArr['companyName'] = $object['companyName'];
		if(!empty($object['belongDeptId']))
			$this->service->searchArr['belongDeptId'] = $object['belongDeptId'];
		if(!empty($object['personLevel']))
	 		$this->service->searchArr['personLevel'] = $object['personLevel'];
		if(!empty($object['officeId']))
			$this->service->searchArr['officeId'] = $object['officeId'];
		if(!empty($object['eprovinceId']))
			$this->service->searchArr['eprovinceId'] = $object['eprovinceId'];
		if(!empty($object['ecityId']))
			$this->service->searchArr['ecityId'] = $object['ecityId'];
		if(!empty($object['jobId']))
			$this->service->searchArr['jobId'] = $object['jobId'];
		if(!empty($object['employeesState']))
			$this->service->searchArr['employeesState'] = $object['employeesState'];
		if(!empty($object['personnelType']))
			$this->service->searchArr['personnelType'] = $object['personnelType'];
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
			$exportData[$key]['staffName']=$planEquRows[$key]['staffName'];
			$exportData[$key]['sex']=$planEquRows[$key]['sex'];
			$exportData[$key]['companyName']=$planEquRows[$key]['companyName'];
			$exportData[$key]['belongDeptName']=$planEquRows[$key]['belongDeptName'];
			$exportData[$key]['personLevel']=$planEquRows[$key]['personLevel'];
			$exportData[$key]['officeName']=$planEquRows[$key]['officeName'];
			$exportData[$key]['eprovinceCity']=$planEquRows[$key]['eprovinceCity'];
			$exportData[$key]['jobName']=$planEquRows[$key]['jobName'];
			$exportData[$key]['employeesStateName']=$planEquRows[$key]['employeesStateName'];
			$exportData[$key]['personnelTypeName']=$planEquRows[$key]['personnelTypeName'];
		}
		return $this->service->degreeExcelOut ( $exportData );
	 }

	/**
	 * 导出全部数据
	 */
	function c_excelOutAll(){
		set_time_limit(600);
		$planEquRows = $this->service->list_d("select_simple");
		$exportData = array();

		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
			$exportData[$key]['staffName']=$planEquRows[$key]['staffName'];
			$exportData[$key]['sex']=$planEquRows[$key]['sex'];
			$exportData[$key]['birthdate']=$planEquRows[$key]['birthdate'];
			$exportData[$key]['nativePlace']=$planEquRows[$key]['nativePlacePro'].$planEquRows[$key]['nativePlaceCity'];
			$exportData[$key]['identityCard']="".$planEquRows[$key]['identityCard'];
			$exportData[$key]['highEducationName']=$planEquRows[$key]['highEducationName'];
			$exportData[$key]['highSchool']=$planEquRows[$key]['highSchool'];
			$exportData[$key]['professionalName']=$planEquRows[$key]['professionalName'];
		}

		return model_hr_personnel_personnel::excelOutAll ( $exportData );
	}

	/**
	 * 导出数据
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['personnel']['listSql']))));
		$excelDao = new model_hr_personnel_excel(); //读取个人勾选记录
		$excelObj = $excelDao->find(array('createId' => $_SESSION['USER_ID']));
		if (is_array($excelObj)) {
			$this->assign('checkId' ,$excelObj['id']);
			$this->assign('checkValue' ,$excelObj['checkValue']);
		} else {
			$this->assign('checkId' ,'');
			$this->assign('checkValue' ,'');
		}
		$this->view('excelout-select');
	}

	/**
	 * 导出数据
	 */
	function c_deptassistantSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['personnel']['listSql']))));
		$this->view('deptassistant-select');
	}

	/**
	 * 导出数据(网络服务部)
	 */
	function c_degreeSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['personnel']['listSql']))));
		$this->view('degree-select');
	}

	/**
	 * 导出数据(其他信息)
	 */
	function c_excelOutOtherSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['personnel']['listSql']))));
		$this->view('other-select');
	}

	/**
	 * 导出数据
	 */
	function c_excelOut(){
		set_time_limit(0);
		$rows = array();//数据集
		$listSql = str_replace("&nbsp;" ," " ,stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}
		$colNameArr = array();//列名数组
		include_once ("model/hr/personnel/personnelFieldArr.php");
		if(is_array($_POST['personnel'])) {
			$checkValue = ''; //初始化后台保存勾选记录
			foreach($_POST['personnel'] as $key => $val) {
				foreach($personnelFieldArr as $fKey => $fVal) {
					if($val == $fKey) {
						$colNameArr[$key] = $fVal;
					}
				}
				$checkValue .= $val.',';
			}

			$excelDao = new model_hr_personnel_excel();
			$checkValue = substr($checkValue ,0 ,-1);
			if ($_POST['checkId'] != '') {
				$excelDao->edit_d(array('id' => $_POST['checkId'] ,'checkValue' => $checkValue) ,true);
			} else {
				$excelDao->add_d(array('checkValue' => $checkValue) ,true);
			}
		}
		$newColArr = array_combine($_POST['personnel'] ,$colNameArr);//合并数组
		//匹配导出列
		$dataArr = array();
		$colIdArr = array_flip($_POST['personnel']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				$row['homeAddress'] = $row['homeAddressPro'].$row['homeAddressCity'].$row['homeAddress'];
				$row['nowAddress'] = $row['nowPlacePro'].$row['nowPlaceCity'].$row['nowAddress'];
				if($row['birthdate'] == '0000-00-00' || $row['birthdate'] == '') {
					$row['birthdate'] = '';
				} else {
					$row['birthdate'] = date('Ymd' ,strtotime($row['birthdate']));
				}
				if($row['entryDate'] == '0000-00-00' || $row['entryDate'] == '') {
					$row['entryDate'] = '';
				} else {
					$row['entryDate'] = date('Ymd' ,strtotime($row['entryDate']));
				}
				if($row['becomeDate'] == '0000-00-00' || $row['becomeDate'] == '') {
					$row['becomeDate'] = '';
				}else{
					$row['becomeDate'] = date('Ymd',strtotime($row['becomeDate']));
				}
				if($row['realBecomeDate'] == '0000-00-00' || $row['realBecomeDate'] == '') {
					$row['realBecomeDate'] = '';
				} else {
					if(date('Ymd' ,strtotime($row['realBecomeDate'])) == '19700101') {
						$row['realBecomeDate'] = $row['realBecomeDate'];
					} else {
						$row['realBecomeDate'] = date('Ymd' ,strtotime($row['realBecomeDate']));
					}
				}
				if($row['quitDate'] == '0000-00-00' || $row['quitDate'] == '') {
					$row['quitDate'] = '';
				} else {
					$row['quitDate'] = date('Ymd' ,strtotime($row['quitDate']));
				}
				if($row['graduateDate'] == '0000-00-00' || $row['graduateDate'] == '') {
					$row['graduateDate'] = '';
				} else {
					$row['graduateDate'] = date('Ymd' ,strtotime($row['graduateDate']));
				}
				$row['createTime'] = date('Ymd' ,strtotime($row['createTime'])); //建档时间
				$row['isBack'] = $row['isBack'] ? '是' : '否'; //是否黑名单
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr ,$colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutSelect($newColArr,$dataArr);
	}

	/**
	 * 导出数据
	 */
	function c_selectExcelOutOther(){
		set_time_limit(0);
		$rows=array();//数据集
		$listSql=str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}
		$userNoArr=array();
		if(is_array($rows)){//获取员工编号
			foreach($rows as $rKey=>$rVal){
				$userNoArr[]=array_push($userNoArr,$rVal['userNo']);
			}
		}
		$userNoArrStr=implode(',',$userNoArr);
		switch($_POST['selectCheck']){
			case 'education':$modelDao=new model_hr_personnel_education();break;
			case 'work':$modelDao=new model_hr_personnel_work();break;
			case 'society':$modelDao=new model_hr_personnel_society();break;
			case 'certificate':$modelDao=new model_hr_personnel_certificate();break;
			case 'project':$modelDao=new model_hr_project_project();break;
			case 'contract':$modelDao=new model_hr_contract_contract();break;
		}
		//获取信息
		$modelRows=$modelDao->getInfoByUserNo_d($userNoArrStr);

		$postField=$_POST[$_POST['selectCheck']];
		if(!empty($_POST['basic'])){
			$postField=array_merge($_POST['basic'],$postField);
		}
		$colNameArr=array();//列名数组
		if($_POST['selectCheck']=="contract"){
			include_once ("model/hr/contract/contractFieldArr.php");
		}else if($_POST['selectCheck']=="project"){
			include_once ("model/hr/project/projectFieldArr.php");
		}else{
			include_once ("model/hr/personnel/".$_POST['selectCheck']."FieldArr.php");
		}
		$fieldArrStr=$_POST['selectCheck']."FieldArr";
		if(is_array($postField)){
			foreach($postField as $key=>$val){
					foreach($$fieldArrStr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
		$newColArr=array_combine($postField,$colNameArr);//合并数组
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($postField);
		if(is_array($modelRows)){
			foreach ($modelRows as $key => $row) {

			switch($_POST['selectCheck']){
				case 'education':
					if($row['beginDate']=='0000-00-00'||$row['beginDate']==''){
						$row['beginDate']='';
					}else{
						$row['beginDate']=date('Ymd',strtotime($row['beginDate']));
					}
					if($row['closeDate']=='0000-00-00'||$row['closeDate']==''){
						$row['closeDate']='';
					}else{
						$row['closeDate']=date('Ymd',strtotime($row['closeDate']));
					}
				break;
				case 'work':
					if($row['beginDate']=='0000-00-00'||$row['beginDate']==''){
						$row['beginDate']='';
					}else{
						$row['beginDate']=date('Ymd',strtotime($row['beginDate']));
					}
					if($row['closeDate']=='0000-00-00'||$row['closeDate']==''){
						$row['closeDate']='';
					}else{
						$row['closeDate']=date('Ymd',strtotime($row['closeDate']));
					}
				break;
				case 'certificate':
					if($row['certifyingDate']=='0000-00-00'||$row['certifyingDate']==''){
						$row['certifyingDate']='';
					}else{
						$row['certifyingDate']=date('Ymd',strtotime($row['certifyingDate']));
					}
				break;
				case 'project':
					if($row['beginDate']=='0000-00-00'||$row['beginDate']==''){
						$row['beginDate']='';
					}else{
						$row['beginDate']=date('Ymd',strtotime($row['beginDate']));
					}
					if($row['closeDate']=='0000-00-00'||$row['closeDate']==''){
						$row['closeDate']='';
					}else{
						$row['closeDate']=date('Ymd',strtotime($row['closeDate']));
					}
				break;
				case 'contract':
					if($row['beginDate']=='0000-00-00'||$row['beginDate']==''){
						$row['beginDate']='';
					}else{
						$row['beginDate']=date('Ymd',strtotime($row['beginDate']));
					}
					if($row['closeDate']=='0000-00-00'||$row['closeDate']==''){
						$row['closeDate']='';
					}else{
						$row['closeDate']=date('Ymd',strtotime($row['closeDate']));
					}
					if($row['trialBeginDate']=='0000-00-00'||$row['trialBeginDate']==''){
						$row['trialBeginDate']='';
					}else{
						$row['trialBeginDate']=date('Ymd',strtotime($row['trialBeginDate']));
					}
					if($row['trialEndDate']=='0000-00-00'||$row['trialEndDate']==''){
						$row['trialEndDate']='';
					}else{
						$row['trialEndDate']=date('Ymd',strtotime($row['trialEndDate']));
					}
				break;
			}
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		switch($_POST['selectCheck']){
			case 'education':return model_hr_personnel_personnelExcelUtil::excelOutEducation($newColArr,$dataArr);break;
			case 'work':return model_hr_personnel_personnelExcelUtil::excelOutWork($newColArr,$dataArr);break;
			case 'society':return model_hr_personnel_personnelExcelUtil::excelOutSociety($newColArr,$dataArr);break;
			case 'certificate':return model_hr_personnel_personnelExcelUtil::excelOutCertificate($newColArr,$dataArr);break;
			case 'project':return model_hr_personnel_personnelExcelUtil::excelOutProject($newColArr,$dataArr);break;
			case 'contract':return model_hr_personnel_personnelExcelUtil::excelOutContract($newColArr,$dataArr);break;
		}
	}

	/******************* E 导入导出系列 ************************/

	/**
	 * 跳转到附件管理页面
	 */
	function c_toFileLimits() {
		$this->permCheck (); //安全校验
		$this->assign("id" ,$_GET ['id']);
		$this->view ( 'fileLimits' );
	}

	/**
	 * 附件权限管理
	 */
	function c_fileLimits() {
		$obj = $_POST[$this->objName];

		if ($this->service->fileLimits_d($obj)) {
			msg( '修改成功！' );
		} else {
			msg( '修改失败！' );
		}
	}

	/**
	 * 跳转到关联职位申请页面
	 */
	function c_toAssociatePosition() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isMedicalHistory'] == '是') {
			$this->assign ( 'isYes', 'checked' );
		} else if ($obj ['isMedicalHistory'] == '否') {
			$this->assign ( 'isNo', 'checked' );
		}
		if ($obj ['isAddScheme'] == '是') {
			$this->assign ( 'isAddYes', 'checked' );
		} else if ($obj ['isAddScheme'] == '否') {
			$this->assign ( 'isAddNo', 'checked' );
		}
		 //获取照片
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str ,0 ,1) == "/") {
			$str=substr($str,1);
		}
        $this->show->assign("file",$this->service->getFilesByObjId( $_GET ['id'],true,'personnel_staff'));
		$this->assign("photoUrl",$str);
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select());
		$this->showDatadicts ( array ('healthStateCode' => 'HRJKZK' ), $obj ['healthStateCode'] );
		$this->showDatadicts ( array ('politicsStatusCode' => 'HRZZMM' ), $obj ['politicsStatusCode'] );
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ), $obj ['highEducation'] );
		$this->showDatadicts ( array ('englishSkill' => 'HRYYDJ' ), $obj ['englishSkill'] );
		$this->showDatadicts ( array ('employeesState' => 'HRYGZT' ), $obj ['employeesState'] );
		$this->showDatadicts ( array ('personnelType' => 'HRYGLX' ), $obj ['personnelType'] );
		$this->showDatadicts ( array ('position' => 'HRGWFL' ), $obj ['position'] );
		$this->showDatadicts ( array ('personnelClass' => 'HRRYFL' ), $obj ['personnelClass'] );
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] );
		$this->showDatadicts ( array ('functionCode' => 'HRYGZN' ), $obj ['functionCode']);
		$this->showDatadicts ( array ('technologyCode' => 'HRJSLY' ), $obj ['technologyCode']);
		$this->showDatadicts ( array ('networkCode' => 'HRFWWL' ), $obj ['networkCode']);
		$this->showDatadicts ( array ('deviceCode' => 'HRSBDJ' ), $obj ['deviceCode']);
		$this->showDatadicts ( array ('outsourcingCode' => 'HTWBFS' ), $obj ['outsourcingCode']);
		if($obj ['employeesState']=="YGZTZZ"){
			$this->showDatadicts ( array ('staffState' => 'YGZTZZ' ), $obj ['staffState'] );
		}else if($obj ['employeesState']=="YGZTLZ"){
			$this->showDatadicts ( array ('staffState' => 'YGZTLZ' ), $obj ['staffState'] );
		}
		$this->view ( 'associatePosition' );
	}

	/**
	 * 关联职位申请
	 */
	function c_associatePosition() {
		$obj = $_POST[$this->objName];

		if ($this->service->associatePosition_d($obj)) {
			msg( '保存成功！' );
		} else {
			msg( '保存失败！' );
		}
	}

	/**
	 * 重写检查对象是否重复
	 */
	function c_checkRepeat() {
		$checkId = "";
		$service = $this->service;
		if (isset ( $_REQUEST ['id'] )) {
			$checkId = $_REQUEST ['id'];
			unset ( $_REQUEST ['id'] );
		}

		$validateId = $_POST['validateId'];
		$validateValue = $_POST['validateValue'];
		$service->searchArr = array(
			'employeesState' => "YGZTZZ", //员工状态为在职
			$validateId."Eq" => $validateValue
		);
		$isRepeat = $service->isRepeat($service->searchArr ,$checkId);
		$result = array(
			'jsonValidateReturn' => array($_POST['validateId'] ,$_POST['validateError'])
		);
		if($isRepeat){
			$result['jsonValidateReturn'][2] = "false";
		}else{
			$result['jsonValidateReturn'][2] = "true";
		}
		echo util_jsonUtil::encode ( $result );
	}
}
?>