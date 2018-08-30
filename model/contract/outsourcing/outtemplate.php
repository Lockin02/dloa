<?php
/**
 * @author Show
 * @Date 2013年10月8日 0:20:42
 * @version 1.0
 * @description:外包模板费用模板 Model层
 */
class model_contract_outsourcing_outtemplate extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing_template";
		$this->sql_map = "contract/outsourcing/outtemplateSql.php";
		parent :: __construct();
	}

	//保存模板
	function saveTemplate_d($itemArr){
		//实例化模板明细
		$outtemplateitemDao = new model_contract_outsourcing_outtemplateitem();
		try{
			$this->start_d();

			//查询当前用户是否已存在模板
			$obj = $this->find(array('createId' => $_SESSION['USER_ID']),null,'id');
			if($obj){
				$condition = array('mainId'=>$obj['id']);
				$outtemplateitemDao->delete($condition);
				$itemArr = util_arrayUtil::setArrayFn($condition,$itemArr);
				$outtemplateitemDao->saveDelBatch($itemArr);
			}else{
				$newArr = array('templateName' => '整包模板');
				$newId = $this->add_d($newArr,true);
				$itemArr = util_arrayUtil::setArrayFn(array('mainId'=>$newId),$itemArr);
				$outtemplateitemDao->saveDelBatch($itemArr);
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 获取模板
	 */
	function getTemplate_d(){
		//实例化模板明细
		$outtemplateitemDao = new model_contract_outsourcing_outtemplateitem();
		//查询当前用户是否已存在模板
		$obj = $this->find(array('createId' => $_SESSION['USER_ID']),null,'id');
		if($obj){
			return $outtemplateitemDao->findAll(array('mainId' => $obj['id']),'parent');
		}else{
			return false;
		}
	}
}
?>