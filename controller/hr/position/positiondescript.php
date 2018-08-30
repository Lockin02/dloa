<?php
/**
 * @author Administrator
 * @Date 2012年7月9日 星期一 14:15:37
 * @version 1.0
 * @description:职位说明书控制层
 */
class controller_hr_position_positiondescript extends controller_base_action {

	function __construct() {
		$this->objName = "positiondescript";
		$this->objPath = "hr_position";
		parent::__construct ();
	 }

	/**
	 * 跳转到职位说明书列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到自己定义的职位说明书列表
	 */
    function c_mypage() {
      $this->view('mylist');
    }

   /**
	 * 跳转到新增职位说明书页面
	 */
	function c_toAdd() {
		//组件添加所需
		if (empty ( $_GET ['valPlus'] )) {
			$this->assign ( 'valPlus', '' );
		} else {
			$this->assign ( 'valPlus', $_GET ['valPlus'] );
		}
	     $this->view ( 'add' );
   }
	/**
	 * 跳转到查看审批职位说明书页面
	 */
   function c_toRead() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
     $this->view ( 'read' );
   }
   /**
	 * 跳转到编辑职位说明书页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
	$this->showDatadicts ( array ('rewardGradeCode' => 'HRGZJB' ),$obj['rewardGradeCode']);
	$this->showDatadicts ( array ('education' => 'HRJYXL' ),$obj['education']);
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看职位说明书页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

	/**
	 * 职位说明书添加
	 */
	function c_add($isAddInfo = false){
		$id = $this -> service -> add_d($_POST[$this -> objName], $isAddInfo);
		$position ['name']=$_POST[$this -> objName]['positionName'];
		$position ['id']=$id;
		if ($id) {
			if($position['valPlus']!=null)
				echo "<script>window.opener.jQuery('#valHidden" . $_POST ['valPlus'] . "').val('" . util_jsonUtil::encode ( $position ) . "');</script>";
			msg ( '保存成功！' );
			echo "<script>window.close();</script>";
		} else {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312'' /><script>alert('新增失败!');window.close();</script></html>";
		}
	}
	/**
	 * 职位说明书修改
	 */
	function c_edit() {
		$object = $_POST[$this -> objName];
		$id = $this -> service -> edit_d($object, true);
		if ($id) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312'' /><script >alert('修改成功!');window.close();</script></html>";
		} else {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312' /><script>alert('修改失败!');</script></html>";
		}
	}
	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$this->service->searchArr['createId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * 检查对象是否重复
	 */
	function c_checkRepeat() {
		$checkId = "";
		$service = $this->service;
		if (isset ( $_REQUEST ['id'] )) {
			$checkId = $_REQUEST ['id'];
			unset ( $_REQUEST ['id'] );
		}
		if(!isset($_POST['validateError'])){
			$service->getParam ( $_REQUEST );
			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );
			echo $isRepeat;
		}else{
			//新验证组件
			$validateId=$_POST['validateId'];
			$validateValue=$_POST['validateValue'];
			$service->searchArr=array(
				$validateId."Eq"=>$validateValue
			);
			$result=array(
				'jsonValidateReturn'=>array($_POST['validateId'],$_POST['validateError'])
			);
			unset($_POST['validateId']);
			unset($_POST['validateValue']);
			if(is_array($_REQUEST)){
				foreach($_REQUEST as $key=>$val)
					$service->searchArr[$key]=$val;
			}

			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );

			if($isRepeat){
				$result['jsonValidateReturn'][2]="false";
			}else{
				$result['jsonValidateReturn'][2]="true";
			}
			echo util_jsonUtil::encode ( $result );
		}
	}

 }
?>