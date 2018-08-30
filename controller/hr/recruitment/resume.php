<?php
/**
 * @author Administrator
 * @Date 2012-07-06 15:20:28
 * @version 1.0
 * @description:简历管理控制层
 */
class controller_hr_recruitment_resume extends controller_base_action {

	function __construct() {
		$this->objName = "resume";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到简历管理列表
	 */
	function c_page() {
		$this->view('list');
	}

	function c_pageJson(){
		foreach ($_REQUEST as $key=>&$val){
			$val=urldecode($val);
		}

		parent::c_pageJson();
	}

	/**
	 * 跳转到我的简历管理列表
	 */
	function c_mypage() {
		$this->view('mylist');
	}

	/**
	 * 跳转到新增简历管理页面
	 */
	function c_toAdd() {
		//获取照片
		$this->assign("photo","images/no_pic.gif");
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
		$this->assign("parentId",$_GET['id']);
		if($_GET['type']=='apply')
			$this->assign("actionurl","hr_recruitment_applyResume");
		else if($_GET['type']=='recommend'){
			$recommend = new model_hr_recruitment_recomResume();
			if(($recommend->findAll(array("parentId"=>$_GET['id'])))==false)
				$this->assign("actionurl","hr_recruitment_recomResume");
			else
				msg("已经存在一个简历");
		}
		else{
			$this->assign('actionurl','hr_recruitment_resume');
		}
		$this->view ('add' ,true);
	}

	/**
	 * 跳转到编辑简历管理页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['sex'] == '男') {
			$this->assign ( 'sexM', 'checked' );
		} else if ($obj ['sex'] == '女') {
			$this->assign ( 'sexW', 'checked' );
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
		//附件
		$file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_hr_recruitment_resume2');
		$this->assign("file2",$file2);

		$this->showDatadicts ( array ('post' => 'YPZW' ), $obj ['post'] );
		$this->showDatadicts ( array ('sourceA' => 'JLLY' ), $obj ['sourceA'] );
		$this->showDatadicts ( array ('languageGrade' => 'WYSP' ), $obj ['languageGrade'] );
		$this->showDatadicts ( array ('language' => 'HRYZ' ), $obj ['language'] );
		$this->showDatadicts ( array ('computerGrade' => 'JSJSP' ), $obj ['computerGrade'] );
		$this->showDatadicts ( array ('education' => 'HRJYXL' ), $obj ['education'] );
		$this->view ('edit' ,true);
	}

	/**
    *简历查看 TAB
    */
	function c_toViewTab(){
		$this->assign("resumeId" ,$_GET['id']);
		$this->view('view-tab');
	}

	/**
	 * 跳转到查看简历管理页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		//获取照片
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		//附件
		$file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_hr_recruitment_resume2');
		$this->assign("file2",$file2);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//更新简历最后一次浏览人
		$this->service->updateViewer($_GET ['id']);

		$this->assign('education', $this->getDataNameByCode($obj['education']));
		$this->view ( 'view' );
	}

	function c_toRead() {
		$this->permCheck (); //安全校验
		$obj = $this->service->find (array("resumeCode"=>$_GET ['code']));
		//获取照片
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		//附件
		$file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_hr_recruitment_resume2');
		$this->assign("file2",$file2);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('education', $this->getDataNameByCode($obj['education']));
		$this->view ( 'view' );
	}

	/**
	* 转为员工简历
	*/
	function c_ajaxTurnType() {
		//$this->permDelCheck ();
		try {
			$this->service->turnType_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * 加入黑名单
	 */
	function c_ajaxBlacklist() {
		//$this->permDelCheck ();
		try {
			$this->service->ajaxBlacklist_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	function c_ajaxReservelist() {
		//$this->permDelCheck ();
		try {
			$this->service->ajaxReservelist_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	function c_ajaxCompanyResume() {
		//$this->permDelCheck ();
		try {
			$this->service->ajaxCompanyResume_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	function c_ajaxChangeResume() {
			//$this->permDelCheck ();
		$flag=$this->service->ajaxChangeResume_d ( $_POST ['id'],$_POST ['resumeType'] );
		if($flag){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * 高级搜索
	 */
	function c_search() {
		$this->assign("gridName", $_GET['gridName']);
		$this->display('search');
	}

	/******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}


	/**
	 * 新增对象操作
	 */
	function c_applyadd($isAddInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		$title = '简历信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		$objNameArr = array (
			0 => 'applicantName', //应聘者姓名
			1 => 'sex', //性别
			2 => 'birthdate', //出生日期
			3 => 'phone', //联系电话
			4 => 'email', //电子邮箱
		    5 => 'politics', //政治面貌
		    6 => 'marital', //婚姻状况
			7 => 'education', //学历
			8 => 'postName', //应聘职位
			9 => 'educationExp', //教育经历
			10 => 'workExp', //工作经历
			);
		$resultArr = $this->service->addExecelData_d ($objNameArr);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}


	/**
	 * 导入excel
	 * 2013-03-30 duanlh
	 */
	function c_excelInTwo(){
		$title = '简历信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		$objNameArr = array (
			0 => 'applicantName', //应聘者姓名
			1 => 'sex', //性别
			2 => 'birthdate', //出生日期
			3 => 'phone', //联系电话
			4 => 'email', //电子邮箱
		    5 => 'graduateDate', //毕业时间
			6 => 'workSeniority', //工作年限
			7 => 'marital', //婚姻状况
			8 => 'educationName', //学历
			9 => 'postName', //应聘职位
			10 => 'reserveA', //应聘职位小类
			11 => 'wishAdress', //期望工作地点
			12 => 'computerGradeName', //计算机水平
			13 => 'languageName', //语种
			14 => 'languageGradeName', //外语水平
		    15 => 'college', //毕业院校
			16=> 'major', //毕业专业
			17 => 'wishSalary', //期望薪水
			18 => 'prevCompany', //上家公司名称
			19 => 'hillockDate', //到岗时间
			20 => 'specialty', //特长
			21=> 'sourceAName', //应聘渠道大类
			22 => 'sourceB', //应聘渠道小类
			23=> 'selfAssessment', //简历内容
			24 => 'remark', //备注
			25 => 'post', //应聘职位名称
			);
		$resultArr = $this->service->addExecelData_d ($objNameArr);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}



	/******************* E 导入导出系列 ************************/
	/*********************************************人才库***********************************************************************/
	/**
	 * 人才库列表
	 */
	function c_talentPoolList(){
		$this->view('talentPoolList');
	}

	/**
	 * ajax方式批量删除对象（应该把成功标志跟消息返回）
	 */
	function c_ajaxdeletes() {
		//$this->permDelCheck ();
		try {
			$flag = $this->service->deletes_d ( $_POST ['id'] );
			echo $flag;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * 人才库列表
	 */
	function c_invitationList(){
		$this->view('invitationList');
	}


	/*******************add chenrf 20130515*********************/

 	/**
	 * 检查是否走了面试评估等流程
	 */
	function c_checkInvitation(){
		$this->permCheck();
		$id=$_REQUEST['resumeId'];
		$re=$this->service->getInvaitation($id);
		print_r($re);
		if($re)
			echo 1;
		else
			echo 0;
	}

 	/**
	 *
	 * 发送入职通知
	 */
	function c_toSendNotifi(){
		$this->permCheck(); //安全校验
		$resumeRow=$this->service->get_d($_GET['resumeId']);
		$this->assignFunc($resumeRow);
		$this->assign("SignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$resumeRow['post']);//职位类型
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->findAll();
		$select='';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName",$select);
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select());
		$this->showDatadicts ( array ('hrSourceType1Name' => 'HRBCFS' ));
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ));
		$this->assign("userId",$_SESSION['USER_ID']);
		$this->assign("user",$_SESSION['USERNAME']);
		$this->assign("useSignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ));
		$this->assign("managerId",$_SESSION['USER_ID']);
		$this->assign("manager",$_SESSION['USERNAME']);
		$this->assign("SignDate",date('Y-m-d'));
		$entryNoticeDao=new model_hr_recruitment_entryNotice();
		$this->assign('toccMail',$entryNoticeDao->mailArr['sendName']);
		$this->assign('toccMailId',$entryNoticeDao->mailArr['sendUserId']);
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ));//增员类型
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' )); //工资级别

		$interviewDao = new model_hr_recruitment_interview();//关联指定导师
		$interviewObj = $interviewDao -> find(array("resumeId" => $_GET['resumeId']));
		$this->assign("tutor", $interviewObj['tutor']);
		$this->assign("tutorId", $interviewObj['tutorId']);

		$entryNoticeRow = $entryNoticeDao->find("resumeId='".$_GET['resumeId']."'");
		if($entryNoticeRow){
			unset($entryNoticeRow['id']);
			foreach($entryNoticeRow as $key=>$val){
				$this->assign($key,$val);
			}
			//获取邮件内容及附件
			$content = $this->service->get_table_fields('oa_hr_recruitment_entrynotice',"resumeId='".$_GET['resumeId']."'",'content');
			    $this->show->assign("file",$this->service->getFilesByObjId($resumeRow['id'],true,'oa_hr_entryNotice_email')); //显示附件信息
			    $this->assign('content',$content);
		 	$this->showDatadicts ( array ('postType' => 'YPZW' ),$entryNoticeRow['postType']);//职位类型
			$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ),$entryNoticeRow['useHireType']);//录用形式
			$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ),$entryNoticeRow['hrSourceType1']);	//简历来源大类
			$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ),$entryNoticeRow['wageLevelCode']); //工资级别
			$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$entryNoticeRow['addTypeCode']);//增员类型
			$this->view("sendNotify-edit" ,true);
		}
		else{
			$this->view("sendNotify-add" ,true);
		}
	}

	/**
	 * 根据姓名+邮箱检查对象是否重复
	 */
	function c_checkRepeat() {
		$applicantName = util_jsonUtil::iconvUTF2GB ( $_POST['applicantName'] );
		$email = util_jsonUtil::iconvUTF2GB ( $_POST['email'] );
		$num = $this->service->findCount(array('applicantName' => $applicantName ,'email' => $email));
		if ($num > 0) {
			$result = "false";
		} else {
			$result = "true";
		}
		echo $result;
	}
}
?>