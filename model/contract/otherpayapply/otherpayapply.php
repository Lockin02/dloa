<?php
/**
 * @author Show
 * @Date 2012��3��31�� ������ 11:13:43
 * @version 1.0
 * @description:���������ͬ����������Ϣ Model��
 */
class model_contract_otherpayapply_otherpayapply  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_otherpayapply";
		$this->sql_map = "contract/otherpayapply/otherpayapplySql.php";
		parent::__construct ();
	}

	/**
	 * �����Ӧ������Ϣ
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
	 * ���ظ�����Ϣ����
	 */
	function getPayapplyInfo_d($contractId,$contractType){
		$rtArr = array(
			'applyMoney' => '','formDate' => '','feeDeptName' => '','feeDeptId' => '',
			'bank' => '','account' => '','payFor' => '',
			'payType' => '','remark' => '','payDesc' => '', 'isEntrust' => '',
			'place' => '','id as payApplyId'=>'',   //add chenrf ����id���༭������ͬʱ����̯������Ҫ
			'payee' =>  '','isInvoice' =>'','comments' => ''
		);

        // ������ͬ�Ѿ�ȡ���ڸ�����Ϣ�д������
        if ($contractType != 'oa_sale_other') {
            $rtArr['currency'] = '�����';
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