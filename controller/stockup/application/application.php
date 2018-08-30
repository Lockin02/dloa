<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:22:42
 * @version 1.0
 * @description:产品备货申请控制层
 */
class controller_stockup_application_application extends controller_base_action {

	function __construct() {
		$this->objName = "application";
		$this->objPath = "stockup_application";
		parent::__construct ();
	 }

	/**
	 * 跳转到产品备货申请列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增产品备货申请页面
	 */
	function c_toAdd() {
     $this->view ( 'add',true );
   }

   /**
	 * 跳转到编辑产品备货申请页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ((array) $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
	$this->assign ( "appList", $this->service->details( $_GET ['id']));
      $this->view ( 'edit',true);
   }

   /**
	 * 跳转到查看产品备货申请页面
	 */
	function c_toView() {
      	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "appList", $this->service->details( $_GET ['id']));

		if($_GET['actType']=='audit'){
			$this->assign ( 'button', '' );
		}else{
			$this->assign ( 'button', '<input  type="button" class="txt_btn_a" value=" 关  闭 " onclick="closeFun();"/>' );
		}
      $this->view ( 'view' );
   }
    /**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //验证是否重复提交
		$object = $_POST[$this->objName];
		$id = $this->service->add_d($object,$object['auditType'] == 'audit'?true:false);
		if ($id) {
            if($object['auditType'] == 'audit'){
            	succ_show('controller/stockup/application/ewf_index.php?actTo=ewfSelect&billId=' . $id .'&flowMoney=6&billDept='.$object['appDeptId'] );
            	}else{
                msgGo("保存成功","?model=stockup_apply_apply&action=appList");
            }
		} else {
			msgGo("保存失败");
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //验证是否重复提交
		$object = $_POST[$this->objName];
		$rs = $this->service->edit_d($object);
		if ($rs) {
            if($object['auditType'] == 'audit'){
            	succ_show('controller/stockup/application/ewf_index.php?actTo=ewfSelect&billId=' .$object['id'] .'&flowMoney=6&billDept='.$object['appDeptId'] );

            }else{
                msg("保存成功");
            }
		} else {
			msg('保存失败');
		}
	}

	/**
    *个人表格
    */
   function c_personList(){
   		$this->view('personList');
   }

   /**
	 * 表格方法
	 */
	function c_personListJson(){
		$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->pageBySqlId('personList');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 *
	 * delete
	 */
   function c_delete(){
		$service = $this->service;
		echo $service->deletes_d($_POST);

	}
	/**
	 * 跳转到交付备货申请导出页面
	 */
	 function c_toExport(){
		$this->view('export');
	 }
	 /**
	  * 交付备货申请导出
	  */
	  function c_export(){
		$row = $_POST['application'];
	   	set_time_limit(0);
	   	$service = $this->service;
	 	if(trim($row['listNo'])){ //申请编号
			$service->searchArr['listNoS'] = $row['listNo'];
	 	}
	 	if(trim($row['createName'])){ //申请人名
			$service->searchArr['createNameS'] = $row['createName'];
	 	}
	 	if(trim($row['batchNum'])){ //批次号
			$service->searchArr['batchNumS'] = $row['batchNum'];
	 	}
	 	if(trim($row['createTimeS'])){ //申请时间
			$service->searchArr['createTimeS'] = $row['createTimeS'];
	 	}
	 	if(trim($row['createTimeE'])){ //申请时间
			$service->searchArr['createTimeE'] = $row['createTimeE'];
	 	}
	 	if(trim($row['ExaStatus'])){ //审批状态
			$service->searchArr['ExaStatus'] = $row['ExaStatus'];
	 	}
	 	$rows = $service->list_d('personList');
	 	$exportDatas = array();
		foreach($rows as $key =>$val){
			$exportDatas[$key]['listNo'] = $val['listNo'];
			$exportDatas[$key]['createName'] = $val['createName'];
			$exportDatas[$key]['batchNum'] = $val['batchNum'];
			$exportDatas[$key]['createTime'] = substr($val['createTime'],0,10);
			$exportDatas[$key]['ExaStatus'] = $val['ExaStatus'];
		}
		$colArr  = array(
		);
		$modelName = '交付备货申请导出';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$exportDatas, $modelName);
	  }

	 /**
	 * 跳转到交付备货列表导出页面
	 */
	 function c_toExportList(){
		$this->view('exportList');
	 }
	 /**
	  * 交付备货申请导出
	  */
	  function c_exportList(){
		$row = $_POST['application'];
	   	set_time_limit(0);
	   	$service = $this->service;
	 	if(trim($row['listNo'])){ //申请编号
			$service->searchArr['listNoS'] = $row['listNo'];
	 	}
	 	if(trim($row['createName'])){ //申请人名
			$service->searchArr['createNameS'] = $row['createName'];
	 	}
	 	if(trim($row['batchNum'])){ //批次号
			$service->searchArr['batchNumS'] = $row['batchNum'];
	 	}
	 	if(trim($row['createTimeS'])){ //申请时间
			$service->searchArr['createTimeS'] = $row['createTimeS'];
	 	}
	 	if(trim($row['createTimeE'])){ //申请时间
			$service->searchArr['createTimeE'] = $row['createTimeE'];
	 	}
	 	if(trim($row['ExaStatus'])){ //审批状态
			$service->searchArr['ExaStatus'] = $row['ExaStatus'];
	 	}
	 	$rows = $service->list_d();
	 	$exportDatas = array();
		foreach($rows as $key =>$val){
			$exportDatas[$key]['listNo'] = $val['listNo'];
			$exportDatas[$key]['createName'] = $val['createName'];
			$exportDatas[$key]['batchNum'] = $val['batchNum'];
			$exportDatas[$key]['createTime'] = substr($val['createTime'],0,10);
			$exportDatas[$key]['ExaStatus'] = $val['ExaStatus'];
		}
		$colArr  = array(
		);
		$modelName = '交付备货列表导出';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$exportDatas, $modelName);
	  }
 }
?>