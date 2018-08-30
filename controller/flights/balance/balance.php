<?php
/**
 * @author Show
 * @Date 2013年7月1日 星期五 13:50:47
 * @version 1.0
 */
class controller_flights_balance_balance extends controller_base_action {
	function __construct() {
		$this->objName = "balance";
		$this->objPath = "flights_balance";
		parent::__construct ();
	}
	/**
	 * 显示结算订单现有记录
	 */
	function c_list(){
		$this->view('list');
	}

	/**
	 * 获取ID查询订单信息表并存放
	 */
	function c_toAddBatch(){
		$msgId = isset($_GET['msgId'])?$_GET['msgId']:'';
		$this->assign('msgId', $msgId);
		$object = $this->service->msgInfo_d($_GET['msgId']);
		foreach( $object as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('addbatch');
	}

	/**
	 * 添加从表信息
	 */
	function c_addBatch(){
		$object = $_POST [$this->objName];
		$id = $this->service->addBatch_d ( $object );
		if($id){
			msgRf("添加成功");
		}else{
			msgRf("添加失败");
		}
	}

	/**
	 * 生成结算单 - 查询生成
	 */
	function c_toSubAdd(){
		$beginDate = date('Y-m-01', strtotime(day_date));
		$this->assign('beginDate',$beginDate);
		$this->assign('endDate',date('Y-m-d', strtotime("$beginDate +1 month -1 day")));
		$this->view('addsub');
	}

	/**
	 * 添加结算订单
	 */
	function c_add(){
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object);
		$this->assign('creator',$_SESSION['USERNAME']);
		if($id){
			msgRf("添加成功");
		}else{
			msgRf("添加失败");
		}
	}

	/**
	 * 跳转到编辑页面
	 */
	function c_toEdit(){
		$this->permCheck(); //安全校验
		$object = $this->service->get_d($_GET['id']);
		foreach ($object as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 修改结算订单
	 */
	function c_edit(){
		$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object);
		if($id){
			msgRf("修改成功");
		}else{
			msgRf("修改失败");
		}
	}

	/**
	 *查看结算信息
	 */
	function c_toView(){
		$id = $_GET['id'];
		$obj = $this->service->get_d($id); //结算内容
		$billObj = $this->service->getBillInfo_d($id); //发票内容
		if(!empty($billObj)){
			$infoArr = array_merge($obj,$billObj);
			$this->assignFunc($infoArr);
		}else{
			$billObj = array(
				"billMoney"=>'',
				"billDate"=>'',
				"billTypeName"=>'',
				"billContent"=>''
			);
			$infoArr = array_merge($obj,$billObj);
			$this->assignFunc($infoArr);
		}
		$this->assign('balanceStatus',$this->service->rtStatus_d($obj['balanceStatus']));
		$this->assign('id',$id);
        //附件添加{file}
        $this->assign('file',$this->service->getFilesByObjId ( $billObj['id'], false,'oa_flights_balance_bill' )) ;
		$this->view('view');
	}

    /**
     * 重写delete方法
     */
	function c_delete() {
		$id = $_POST['id'];
	   	$rs = $this->service->delete_d($id);
	   	if($rs){
	   		echo 1;
	   	}else{
	   		echo 0;
	   	}
	   	exit();
	}

	/**
	 * 结算单金额统计
	 */
	function c_costForShow(){
		$id = !empty($_GET['id']) ? $_GET['id'] : exit();
		$costDetail = $_GET['costDetail'];
		switch($costDetail){
			case '0' : $datas = $this->service->getCostForShow_d($id);$str = $this->service->showCost_d($datas);break;
			case '1' : $datas = $this->service->getCostForDept_d($id);$str = $this->service->showCostDept_d($datas);break;
			case '2' : $datas = $this->service->getCostForProject_d($id,$costDetail);$str = $this->service->showCostProject_d($datas,$costDetail);break;
			case '3' : $datas = $this->service->getCostForProject_d($id,$costDetail);$str = $this->service->showCostProject_d($datas,$costDetail);break;
			case '4' : $datas = $this->service->getCostForSale_d($id);$str = $this->service->showCostSale_d($datas);break;
			case '5' : $datas = $this->service->getCostForContract_d($id);$str = $this->service->showCostContract_d($datas);break;
		}
		exit(util_jsonUtil::iconvGB2UTF($str));
	}
}