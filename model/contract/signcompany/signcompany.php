<?php
/**
 * @author Show
 * @Date 2012年2月21日 星期二 15:37:22
 * @version 1.0
 * @description:签约公司 Model层
 */
 class model_contract_signcompany_signcompany  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_signcompany";
		$this->sql_map = "contract/signcompany/signcompanySql.php";
		parent::__construct ();
	}

	/**
	 * 保存签约公司
	 */
	function saveCompanyInfo_d($object){
		//条件数组
		$conditionArr = array('signCompanyName' => $object['signCompanyName']);
		//查询是否存在一样的数据
		$rs = $this->find($conditionArr,null,'id');
		if(is_array($rs)){
			$object['id'] = $rs['id'];
			$this->edit_d($object,true);
		}else{
			$this->add_d($object,true);
		}
		return true;
	}
}
?>