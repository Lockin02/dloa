<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 19:40:09
 * @version 1.0
 * @description:导师考核表控制层
 */
class controller_hr_tutor_scheme extends controller_base_action {

	function __construct() {
		$this->objName = "scheme";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }

	/**
	 * 跳转到导师考核表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
    * 导师考核
    */
   function c_toTutorAssess(){
       $tutorId = $_GET['id'];
		//判断是否有导师考核表
		$sql = "select id  from oa_hr_tutor_scheme where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		if (empty ($flagArr[0]['id'])) {
			$tutorrecordDao = new model_hr_tutor_tutorrecords();
			$obj = $tutorrecordDao->get_d ( $_GET ['id'] );
			//根据账号获取人资基本信息（试用开始/结束日期）
			$dao = new model_hr_personnel_personnel();
			$perInfo = $dao->getPersonnelInfo_d($obj['studentAccount']);
			$obj['beginDate'] = $perInfo['entryDate'];
	        $obj['endDate'] = $perInfo['becomeDate'];
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
	     	$this->view ( 'add' );
		} else {
            $obj=$this->service->get_d($flagArr[0]['id']);
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
	        $this->view ( 'view' );
		}
   }
   /**
    * 查看导师考核
    */
   function c_toTutorAssessView(){
       $tutorId = $_GET['id'];
		//判断是否有导师考核表
		$sql = "select id  from oa_hr_tutor_scheme where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		if (empty ($flagArr[0]['id'])) {
			 echo "<br><div style='text-align:center'><b>暂无导师考核表</b></div>";
		} else {
            $obj=$this->service->get_d($flagArr[0]['id']);
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
	        $this->view ( 'view' );
		}
   }
   /**
	 * 跳转到新增导师考核表页面
	 */
	function c_toAdd() {
		$this->permCheck (); //安全校验
		$tutorrecord=new controller_hr_tutor_tutorrecords();
		$obj = $tutorrecord->service->get_d ( $_GET ['id'] );
		//根据账号获取人资基本信息（试用开始/结束日期）
		$dao = new model_hr_personnel_personnel();
		$perInfo = $dao->getPersonnelInfo_d($obj['studentAccount']);
		$obj['beginDate'] = $perInfo['entryDate'];
        $obj['endDate'] = $perInfo['becomeDate'];
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
     	$this->view ( 'add' );
   }

   /**
	 * 跳转到编辑导师考核表页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看导师考核表页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj=$this->service->get_d($_GET[id]);
	if(empty($obj)){
		echo "<br><div style='text-align:center'><b>该导师暂无导师考核信息</b></div>";
		exit;
	}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }


   /**
	 * 跳转到查看导师考核表页面
	 */
	function c_toRewardView() {
      $this->permCheck (); //安全校验
      $schemeId=$this->service->get_table_fields($this->service->tbl_name, "tutorId='".$_GET['id']."'", 'id');
		$obj=$this->service->get_d($schemeId);
	if(empty($obj)){
		echo "<br><div style='text-align:center'><b>该导师暂无导师考核信息</b></div>";
		exit;
	}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

	/*
	* 跳转到查看导师考核表页面(hr)
	 */
	function c_toHRView() {
      $this->permCheck (); //安全校验
      $tutorrecordDao=new controller_hr_tutor_tutorrecords();
		$obj=$tutorrecordDao->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }


   	/**
	 * 新增对象操作
	 */
	function c_add() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object, true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '发起考核成功！';
		if ($id) {
			msg ( $msg );
		}
	}
   	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = true) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d( $object, $isEditInfo )) {
			if($this->service->checkComplete_d($object)){
               //计算导师考核评分并反写评分和状态
               if($this->service->gradeEdit_d($object)){
                  msg ( '评分完成！' );
               }
			}else{
               msg ( '保存成功！' );
			}
		}
	}
	/*
	 * 跳转到导师考核列表
	 */
	 function c_toExaminelist(){
	 	$this->assign( 'userId',$_SESSION['USER_ID'] );
	 	$this->view('examine-list');
	 }

	 /*
	  * 评分页面
	  */
	function c_toScore(){
		$this->permCheck (); //安全校验
		$tutorId = isset ($_GET['tutorId']) ? $_GET['tutorId'] : null;
		$role = $_GET['role'];
	  if(!empty($tutorId)){
         $sql = "select id  from oa_hr_tutor_scheme where tutorId=" . $tutorId . "";
		 $flagArr = $this->service->_db->getArray($sql);
		 if(empty ($flagArr[0]['id'])){
	      	 echo "<br><div style='text-align:center'><b>暂无导师考核表信息</b></div>";
	      	 break;
	     }else{
	     	$id = $flagArr[0]['id'];
	     }
	  }else{
	  	 $id = $_GET['id'];
	  }
		$obj=$this->service->get_d($id);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign( 'userId',$_SESSION['USER_ID'] );
		switch($role){
			case "stu" : $urlType = "addscore-stu";break;
			case "tut" : $urlType = "addscore-tut";break;
			case "sup" : $urlType = "addscore-sup";break;
			case "dept" : $urlType = "addscore-dept";break;
			case "HR" : $urlType = "addscore-hr";break;
		}
		$this->view($urlType);
//		$this->view("addscore");
	}


	//处理评分计算
	function c_toScoreInfo() {
		 $id = $_POST['schemeId'];
         $service = $this->service;
         $service->searchArr['id'] = $id;
		 $rows = $service->list_d("select_default");
         echo util_jsonUtil::encode ( $rows );
	}


	 function c_pageJson() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';

		$rows = $service->pageBySqlId("select_examinelist");
       $schemeinfoDao=new model_hr_tutor_schemeinfo();


		if(is_array($rows)){
	        foreach($rows as $k => $v){
				$schemeinfo= $schemeinfoDao->find(array('tutorassessId'=>$v['id']));
				if($schemeinfo['selfgraded']>0){
					$rows[$k]['tutorScore']='是';//导师已评分
				}else{
					$rows[$k]['tutorScore']='否';
				}
				if($schemeinfo['staffgraded']>0){
					$rows[$k]['staffScore']='是';//学员已评分
				}else{
					$rows[$k]['staffScore']='否';
				}
				if($schemeinfo['superiorgraded']>0){
					$rows[$k]['supScore']='是';//上级已评分
				}else{
					$rows[$k]['supScore']='否';
				}
				if($schemeinfo['assistantgraded']>0){
					$rows[$k]['assistantScore']='是';//部门已评分
				}else{
					$rows[$k]['assistantScore']='否';
				}
				if($schemeinfo['hrgraded']>0){
					$rows[$k]['hrScore']='是';//部门已评分
				}else{
					$rows[$k]['hrScore']='否';
				}
	        }

		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

 }
?>