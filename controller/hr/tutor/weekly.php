<?php
/**
 * @author Administrator
 * @Date 2012-08-24 14:38:04
 * @version 1.0
 * @description:新员工周报表控制层
 */
class controller_hr_tutor_weekly extends controller_base_action {

	function __construct() {
		$this->objName = "weekly";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }

	/**
	 * 跳转到新员工周报表列表
	 */
    function c_page() {
       $this->assign('role' , $_GET['role']);
       $this->assign('tutorId',$_GET['tutorId']);
      $this->view('list');
    }

   /**
	 * 跳转到新增新员工周报表页面
	 */
	function c_toAdd() {
		$this->assign('tutorId',$_GET['tutorId']);
	    $dao = new model_hr_tutor_tutorrecords();
	    $this->service->searchArr['tutorId']=$_GET['tutorId'];
	    $rows=$this->service->list_d();  // 新员工的所有周报
	    $tutorInfo = $dao->get_d($_GET['tutorId']);

	    $newweek=$rows[0];  // 最后提交的周报
	    $nullWeek=array("lastweekSummary"=>"","nextweekSummary"=>"","problem"=>"");   //员工没有提交过周报时，为了防止页面显示{XXXX},弄了个空的周报
	    if($rows){
	    	foreach ($newweek as $key => $val) {
				$this->assign($key, $val);
			}
	    }else{
	    	foreach ($nullWeek as $key => $val) {
				$this->assign($key, $val);
			}
	    }
	    foreach ($tutorInfo as $key => $val) {
				$this->assign($key, $val);
			}
    	 $this->view ('add' ,true);
   }

	/**
	 * 跳转到辑新员工周报表页面
	 */
	function c_toEditWeekly() {
	    $dao = new model_hr_tutor_tutorrecords();
	    $row= $this->service->get_d($_GET['id']);
	    $nullWeek=array("lastweekSummary"=>"","nextweekSummary"=>"","problem"=>"");   //员工没有提交过周报时，为了防止页面显示{XXXX},弄了个空的周报
	    if($row){
	    	foreach ($row as $key => $val) {
				$this->assign($key, $val);
			}
	    }else{
	    	foreach ($nullWeek as $key => $val) {
				$this->assign($key, $val);
			}
	    }
	    $tutorInfo = $dao->get_d($row['tutorId']);
	    unset($tutorInfo['id']);
	    foreach ($tutorInfo as $key => $val) {
				$this->assign($key, $val);
		}
    	 $this->view ('weekly-edit' ,true);
   }

	/**
	 * 跳转到编辑新员工周报表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$dao = new model_hr_tutor_tutorrecords();
		$tutorInfo = $dao->get_d($obj['tutorId']);

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
	  $this->assign('signDate',date('Y-m-d'));
	  $this->assign('studentSuperior',$tutorInfo['studentSuperior']);
      $this->view ('edit' ,true);
   }

	/**
	 * 跳转到查看新员工周报表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['createTime'] = date("Y-m-d",strtotime($obj['createTime']));
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
   }

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		$this->checkSubmit(); //检查是否重复提交
		$addType=isset($_GET['addType'])?$_GET['addType']:"";
		if($addType=='submit'){
			$_POST [$this->objName]['state'] = 1;
			$_POST [$this->objName]['submitDate'] = date("Y-m-d");
		}
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			if($addType=='submit'){
				msg ( '提交成功' );
			}else{
				msg ( '保存成功' );
			}
		}else{
			if($addType=='submit'){
				msg ( '提交失败' );
			}else{
				msg ( '保存失败' );
			}

		}
	}

	/**
	 * 编辑对象操作
	 */
	function c_editWeekly($isAddInfo = true) {
		$this->checkSubmit(); //检查是否重复提交
		$addType=isset($_GET['addType'])?$_GET['addType']:"";
		if($addType=='submit'){
			$_POST [$this->objName]['state'] = 1;
			$_POST [$this->objName]['submitDate'] = date("Y-m-d");
		}
		$id = $this->service->editWeekly_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			if($addType=='submit'){
				msg ( '提交成功' );
			}else{
				msg ( '保存成功' );
			}
		}else{
			if($addType=='submit'){
				msg ( '提交失败' );
			}else{
				msg ( '保存失败' );
			}

		}
	}

	/**
	 * 获取员工辅导周报数据 添加一列:是否按期批复
	 */
	 function c_pageForRead(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$rows = $service->page_d();
		if(is_array($rows)){
			//添加 是否按期批复 一列
			$rows = $service->pageForRead($rows);
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
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = true) {
		$this->checkSubmit(); //检查是否重复提交
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '提交成功！' );
		}
	}
 }
?>