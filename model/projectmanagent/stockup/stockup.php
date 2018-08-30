<?php

/**
 * @author Administrator
 * @Date 2012-09-25 09:54:07
 * @version 1.0
 * @description:销售备货 Model层
 */
class model_projectmanagent_stockup_stockup extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_stockup";
		$this->sql_map = "projectmanagent/stockup/stockupSql.php";
		parent :: __construct();
	}

   /**
    * 编号自动生成（临时）
    */
   function produceCode(){
        $billCode = "XSBH".date("Ymd");
//        $billCode = "JL201208";
		$sql="select max(RIGHT(c.stockupCode,4)) as maxCode,left(c.stockupCode,12) as _maxbillCode " .
				"from oa_sale_stockup c group by _maxbillCode having _maxbillCode='".$billCode."'";

		$resArr=$this->findSql($sql);
		$res=$resArr[0];
		if(is_array($res)){
			$maxCode=$res['maxCode'];
			$maxBillCode=$res['maxbillCode'];
			$newNum=$maxCode+1;
			switch(strlen($newNum)){
				case 1:$codeNum="000".$newNum;break;
				case 2:$codeNum="00".$newNum;break;
				case 3:$codeNum="0".$newNum;break;
				case 4:$codeNum=$newNum;break;
			}
			$billCode.=$codeNum;
		}else{
			$billCode.="0001";
		}

		return $billCode;
	}

	/**
	 * 重写add_d方法
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//编号
			$object['stockupCode'] = $this->produceCode($object);

			//插入主表信息
			$newId = parent :: add_d($object, true);

			//客户联系人
			if (!empty ($object['equ'])) {
				$equDao = new model_projectmanagent_stockup_equ();
				$equDao->createBatch($object['equ'], array ('stockupId' => $newId), 'productId');
			}
			$this->commit_d();
//						$this->rollBack();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
}
?>