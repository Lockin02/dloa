<?php
/**
 * @author Administrator
 * @Date 2012年7月17日 星期二 10:43:19
 * @version 1.0
 * @description:增员申请简历库控制层
 */
class controller_hr_recruitment_applyResume extends controller_base_action {

	function __construct() {
		$this->objName = "applyResume";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到增员申请简历库列表
	 */
	function c_page() {
		$this->assign("id",$_GET['id']);
		$this->assign("stateC",$_GET['stateC']);
		$this->assign("ExaStatus",$_GET['ExaStatus']);
		$this->view('list');
	}

	/**
	 * 跳转到新增增员申请简历库页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	/**
	 * 跳转到新增增员申请简历库页面
	 */
	function c_toSelect() {
	 $this->assign("id",$_GET['id']);
	 $this->view ( 'select' );
	}
	/**
	 * 添加数据到黑名单
	 */
	function c_toBlack(){
		$applyresume = $this->service->get_d($_POST['id']);
		$resume = new model_hr_recruitment_resume();
		if($applyresume['state']==$this->service->statusDao->statusEtoK("black")){echo 3;return;}
		try{
			$getinfo = $resume->get_d($applyresume['resumeId']);
			$getinfo['resumeType'] = 3;
			$resume->edit_d($getinfo);
			$applyresume['state'] = $this->service->statusDao->statusEtoK ( 'black' );
			$this->service->edit_d($applyresume);
		}catch(Exception $e){
			echo 0;
		}
		echo 1;
	}
	/*
	 * ajax方式批量操作对象（应该把成功标志跟消息返回）
	 */
	function c_ajaxadds() {
		$info = explode( ",",$_POST ['id'] );
		$applyid = $_POST['applyid'];
		$resume = new model_hr_recruitment_resume();
		try{
			for ($i=0; $i < count($info); $i++) {
				if($this->service->find(array("resumeId"=>$info[$i],'parentId'=>$applyid))){
					echo 2;return;
				}
				$temp = $resume->get_d($info[$i]);
				$temp['formCode'] = date ( "YmdHis" );
				$temp['parentId'] = $applyid;
				$temp['resumeId'] = $temp['id'];
				$temp['state'] = $this->service->statusDao->statusEtoK ( 'begin' );
				$temp['id'] = NULL;
				$this->service->add_d($temp);

			}
		}catch(Exception $e){
			echo 0;
		}
		echo 1;

	}
	/**
	 * 跳转到编辑增员申请简历库页面
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
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_applyd ( $_POST ['resume'], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}
	/**
	 * 跳转到查看增员申请简历库页面
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
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		//var_dump($rows);
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
}
?>