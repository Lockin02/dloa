<?php
/**
 * @author Show
 * @Date 2012年3月31日 星期六 11:13:43
 * @version 1.0
 * @description:非销售类合同付款申请信息 Model层
 */
class model_contract_otherpayapply_otherpayapply  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_otherpayapply";
		$this->sql_map = "contract/otherpayapply/otherpayapplySql.php";
		parent::__construct ();
	}

	/**
	 * 插入对应付款信息
	 */
	function dealInfo_d($object){
		$rs = $this->find(array('contractId' => $object['contractId'] , 'contractType' => $object['contractType']));
		if(is_array($rs)){
			$this->update(array('contractId' =>$object['contractId'],'contractType'=> $object['contractType']),$object);
		}else{
			return $this->add_d($object);
		}
	}

	/**
	 * 返回付款信息数组
	 */
	function getPayapplyInfo_d($contractId,$contractType){
		$rtArr = array(
			'applyMoney' => '','formDate' => '','feeDeptName' => '','feeDeptId' => '',
			'bank' => '','account' => '','payFor' => '',
			'payType' => '','remark' => '','payDesc' => '', 'isEntrust' => '',
			'place' => '','id as payApplyId'=>'',   //add chenrf 增加id，编辑其他合同时，分摊费用需要
			'payee' =>  '','isInvoice' =>'','comments' => ''
		);

        // 其他合同已经取消在付款信息中处理币种
        if ($contractType != 'oa_sale_other') {
            $rtArr['currency'] = '人民币';
            $rtArr['currencyCode'] = 'CNY';
            $rtArr['rate'] = '1';
        }

		$obj = $this->find(array('contractId' => $contractId , 'contractType' => $contractType),null,implode(array_keys($rtArr),','));
		if(is_array($obj)){
			return $obj;
		}else{
			$rtArr['payApplyId'] = '';
			unset($rtArr['id as payApplyId']);
			return $rtArr;
		}
	}
}