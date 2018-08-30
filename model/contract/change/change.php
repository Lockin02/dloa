<?php
/*
 * Created on 2010-7-8
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_contract_change_change extends model_base{

	function __construct() {
		$this->tbl_name = "oa_contract_change";
		$this->sql_map = "contract/change/changeSql.php";
		parent :: __construct();
	}

	/**
	 * 初始化对象
	 */
	function initChange($id,$type = null){
		$rows = $this->find(array('id' => $id));

		$sales = new model_contract_sales_sales();
		if($type == 'new'){
			$rowsT = $sales->getContractInfo_d($rows['newContId']);
		}else if($type == 'old'){
			$rowsT = $sales->getContractInfo_d($rows['oldContId']);
		}
		$rowsTR = $sales->contractInfo($rowsT);
		$rowsTR['change'] =  $rows;
		$rowsTR['file'] = $sales->getFilesByObjNo($rows['contNumber'],false);
		return $rowsTR;
	}

	/**
	 * 初始化对象-无审批记录修改变更
	 */
	function initChangeEdit($id){
		$rows = $this->find(array('id' => $id));

		$sales = new model_contract_sales_sales();
		$rowsT = $sales->getContractInfo_d($rows['newContId']);
		$rows['file']=$sales->getFilesByObjNo($rows['contNumber']);

		$equipDao = new model_contract_equipment_equipment();
		$equipRow = $equipDao->findAll(array('contId' => $rows['oldContId']),null,'amount');

		$rowsTR = $sales->contractInfoForChange($rowsT,$equipRow);
		$rowsTR['change'] =  $rows;

		return $rowsTR;
	}

	/**
	 * 数据操作-编辑无审批记录的变更申请单
	 */
	function editChange($rows){
		$rowsC = $rows['change'];
//		print_r($rows);
		unset($rows['change']);

		$contract = new model_contract_sales_sales();
		try{
			$this->start_d();
			$contract->edit_d($rows);
			$this->edit_d($rowsC);

//			$this->rollBack();
			$this->commit_d();
			return $rowsC['id'];
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 数据操作-编辑有审批记录的变更申请单
	 */
	function backEditChange($rows){
		$contract = new model_contract_sales_sales();
		try{
			$this->start_d();
			$newid = $contract->contractChange($rows);
			$temp = $rows['change']['formId'];
			unset($rows['change']['formId']);
			$this->setContCloseAfterBack($temp);
			$this->commit_d();
			return $newid;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 数据操作-启动变更合同
	 */
	function beginThisChange($id){
		$rows = $this->find(array('id' => $id));
//		print_r($rows);
		try{
			$this->start_d();
			$this->closeAfterChange($id); //变更后改变变更申请单状态
			$contract = new model_contract_sales_sales();
			$object = $contract->getExecutorAndPrincipal($rows['oldContId']);//获取旧合同的执行人和负责人
			$contract->closeAfterChange($rows['oldContId']); //变更后改变旧合同的状态
//			echo "<pre>";
//			print_r($object);
			$object['id'] = $rows['newContId'];
			$object['isUsing'] = 1;
			$contract->contractBeginInChange($object);//变更后改变新合同的状态
//			$this->rollBack();
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 数据操作-启动变更时对合同变更表的一些更改
	 */
	function closeAfterChange($id)
	{
		$rows = $this->find(array( 'id' => $id));
		$initrows = $rows['newContId'];
		$object['id'] = $id;
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['ExaStatus'] = "变更完成";
		try{
//			$this->start_d();
			$this->updateById($object);
//			$this->commit_d();
		}catch(exception $e){
//			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 数据操作-验证当前合同是否有变更版本在审批中
	 */
	function checkExemining($contractId){
		$mark = $this->isChanging($contractId);
		if($mark){
			echo "<script>alert('当前合同已有一份变更申请单');history.back();</script>'";
		}else {
			return true;
		}
	}

	/**
	 * 待审批的合同变更申请
	 */
	function waitExaminingChange(){
		$this->searchArr['workFlowCode']= $this->tbl_name;
		return $this->pageBySqlId ('change_exemine');
	}

	/**
	 * 数据操作-已审批的变更申请
	 */
	function examinedChange(){
		$this->searchArr['workFlowCode']= $this->tbl_name;
		return $this->pageBySqlId ('change_exemined');
	}

	/**
	 * 数据操作-根据ID打回关闭变更申请单
	 * 改状态在变更申请单被打回后，执行重新编辑，生成新的申请单关闭旧的申请单
	 */
	function setContCloseAfterBack($id){
		$object['id'] = $id;
		$object['isUsing'] = 2;//状态2暂定为打回关闭
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['ExaStatus'] = "打回关闭";
		return $this->updateById($object);
	}

	/**
	 * 假删除操作
	 */
	function notDel($id){
		$object['id'] = $id;
		$rows = $this->find(array('id' => $id),null,'newContId,oldContId');
		$object['isUsing'] = 3;//状态3暂定为不删档删除
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['ExaStatus'] = "保留删除";

		$contract = new model_contract_sales_sales();
		try{
			$this->start_d();
			//修改变更申请单信息
			$this->updateById($object);

			//假删除新合同同时修改旧合同的变更状态
			$contract->notDel($rows['newContId']);
			$contract->cancelChange($rows['oldContId']);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 真删除操作
	 */
	function deletesT($id){
		$rows = $this->find(array('id' => $id),null,'newContId,oldContId');
		$contract = new model_contract_sales_sales();
		try{
			$this->start_d();
			//删除变更申请单
			$this->deletes_d($id);
			//删除新合同同时修改旧合同的变更状态
			$contract->deletes_d($rows['newContId']);
			$contract->cancelChange($rows['oldContId']);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据ID获取变更申请单编号最高版本号
	 */
	function getMaxVersion($formNumber){
		$max = $this->find(array('formNumber' => $formNumber),'version desc','version');
		if($max)
			return $max['version'];
		else
			return 0;
	}

	/**
	 * 根据合同ID判断合同是否曾经变更过
	 */
	function isChange($value,$style=1){
		if($style==1){
			$rows = $this->find(array('newContId' => $value,'ExaStatus' => '变更完成'));
			if($rows){
				return true;
			}else{
				return false;
			}
		}else{
			$rows = $this->find(array('contNumber' => $value,'ExaStatus' => '变更完成'));
			if($rows){
				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * 根据合同ID判断合同是否处于变更中状态
	 */
	function isChanging($id){
		$rows = $this->findSql("select 0 from ".$this->tbl_name." where oldContId = '$id' and ExaStatus in ('变更待审批','部门审批','完成','打回')");
		if($rows){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 根据合同ID获取变更前的版本的ID
	 */
	function getOldContId($value){
		$rows = $this->find(array('newContId' => $value),'','oldContId');
		return $rows['oldContId'];
	}
}
?>
