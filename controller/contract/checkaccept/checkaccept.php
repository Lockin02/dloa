<?php
/**
 * @author tse
 * @Date 2014年4月1日 11:53:04
 * @version 1.0
 * @description:合同验收单控制层
 */
class controller_contract_checkaccept_checkaccept extends controller_base_action {
	function __construct() {
		$this->objName = "checkaccept";
		$this->objPath = "contract_checkaccept";
		parent::__construct ();
	}

	/**
	 * 跳转到合同验收单列表
	 */
	function c_page() {
		if($_GET['identify']=='contractTool'){
			$this->assign ( 'checkStatus', '未验收' );
		}
		else{
			$this->assign ( 'checkStatus', '' );
		}
		$this->view ( 'list' );
	}

	/**
	 * 重写pageJson
	 */
	function c_pageJson(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ();

		/**
		 * 附件显示
		 */
		foreach ($rows as $key => $val){
			$rows[$key]['checkFile'] = $this->service->getFilesByObjId ( $val['id'], false,$this->service->tbl_name );
            //获取预计时间
            $checkDate = $this->service->handleCheckDT($rows[$key]['clause'],$rows[$key]['days'],$rows[$key]['contractId']);

			if($rows[$key]['checkDate'] == '' || $rows[$key]['checkDate'] == '0000-00-00'){
//
				$rows[$key]['checkDate'] = $checkDate;
//                if(!empty($checkDate)){
//                    $rows[$key]['realEndDateView'] = date("Y-m-d",strtotime($checkDate)-$rows[$key]['days']*24*60*60);
//                }
//
			}
//else{
//                $rows[$key]['realEndDateView'] = $val['realEndDate'];
//            }
            $rows[$key]['realEndDateView'] = $val['completeDate'];
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
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
	 * 载入更新验收条款
	 */
	function c_editlistJson(){
		$this->service->getParam($_POST);
		$this->service->asc = false;
		$arr = $this->service->list_d("select_clause");
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 跳转到新增合同验收单页面
	 */
	function c_toAdd() {
		$this->assign ( 'contractCode', $_GET ['contractCode'] );
		$this->assign ( 'contractId', $_GET ['contractId'] );
		$this->view ( 'add' );
	}

	/**
	 * 重写新增方面(non-PHPdoc)
	 *
	 * @see controller_base_action::c_add()
	 */
	function c_add() {
		$msg= $this->service->add_d($_POST);
		if($msg){
			msg('添加成功!');
		}
	}

	/**
	 * 跳转到编辑合同验收单页面
	 */
	function c_toEdit() {
		$this->assign ( 'contractCode', $_GET ['contractCode'] );
		$this->assign ( 'contractId', $_GET ['contractId'] );
		$this->view ( 'edit' );
	}

	/**
	 * 重写edit方法(non-PHPdoc)
	 * @see controller_base_action::c_edit()
	 */
	function c_edit(){
		$msg = $this->service->edit_d($_POST);
		if($msg){
			msg("更新成功");
		}else{
			msg("更新失败");
		}
	}

	/**
	 * 跳转到查看合同验收单页面
	 */
	function c_toView() {
		$this->permCheck (); // 安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * 确认
	 */
	function c_confirm(){
		echo $this->service->confirm_d($_POST);
	}

/**
	 * 变更
	 */
	function c_change(){
		echo $this->service->change_d($_POST);
	}


	/**
	 * 跳转到上传附件页面
	 */
	function c_toUploadFile(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('file',$this->service->getFilesByObjId ( $obj['id'], true,$this->service->tbl_name )) ;
		$this->assignFunc($obj);
		$this->view('uploadfile');
	}

	/**
	 * 验收
	 */
	function c_check(){
		echo $this->service->check_d(util_jsonUtil::iconvUTF2GBArr($_POST));
	}

	//获取变更信息
	function c_showChanceHistory(){
		$id = $_GET['id'];
		$info = $this->service->getChanceHistory_d($id);
		$this->assign("info", $info);
		$this->view("showChanceHistory");
	}

}
?>