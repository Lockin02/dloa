<?php
class model_stock_productinfo_serial extends model_base{
	function __construct() {
		$this->tbl_name = "oa_stock_product_serial";
		$this->sql_map = "stock/productinfo/serialSql.php";
		parent :: __construct();
	}

	/**
	 * 入库形式添加序列号-批量
	 * 数组中相关变量需求
	 * 1.序列号 serial (形式为字符串，其中以","间隔，以","结束，首位不需要",") 如： a,b,c,d,
	 * 2.业务编号 businessNo (主表单的编号，非从表)
	 * 3.业务类型 businessType (主表业务类型，一般为表名)
	 * 4.产品ID productId
	 * 5.产品名称 productName
	 * 6.仓库ID stockId
	 * 7.仓库名称 stockName
	 */
	function batchIn($rows){

	}

	/**
	 * 出库形式添加序列号-批量
	 * 数组中相关变量需求
	 * 1.序列号 serial (形式为字符串，其中以","间隔，以","结束，首位不需要",") 如： a,b,c,d,
	 * 2.业务编号 businessNo (主表单的编号，非从表)
	 * 3.业务类型 businessType (主表业务类型，一般为表名)
	 * 4.产品ID productId
	 * 5.产品名称 productName
	 * 6.仓库ID stockId
	 * 7.仓库名称 stockName
	 */
	function batchOut($rows){
		if(!empty($rows['serial'])){
			$rows['serial'] = substr($rows['serial'],0,-1);
			$arr = explode(',',$rows['serial']);
			$sql = " insert into ".$this->tbl_name ."( serial ,businessId,businessType,productId,productName,stockId,isInStock,outOrIn) values " ;
			try{
				foreach($arr as $val ){
					$sql .= " ('$val','$rows[businessId]','$rows[businessType]','$rows[productId]','$rows[productName]','$rows[stockId]','0','0'),";
				}
				$sql = substr($sql,0,-1);
				$this->query($sql);
//			echo $sql;
			}catch(exception $e){
				throw $e;
			}
		}
	}

	/**
	 * 根据业务编号，业务类型，和产品ID获取序列号列表
	 */
	function getSerial($businessId,$businessType,$productId=null){
		$rows = $this->findAll(array('businessId' => $businessId,'businessType' => $businessType),null,'serial,productId');
//		$arr = array();
		if($rows){
			foreach($rows as $val ){
				if(!isset($arr[$val['productId']])){
					$arr[$val['productId']] = $val['serial'].",";
				}else{
					$arr[$val['productId']] .= $val['serial'].",";
				}
			}
//			print_r($arr);
			return $arr;
		}
	}

	/**
	 * 功能同上，待换行
	 */
	function getSerialForRead($businessId,$businessType){
		$rows = $this->findAll(array('businessId' => $businessId,'businessType' => $businessType),null,'serial,productId');
//		$arr = array();
		if($rows){
			foreach($rows as $val ){
				if(!isset($arr[$val['productId']])){
					$arr[$val['productId']] = $val['serial'].",<br />";
				}else{
					$arr[$val['productId']] .= $val['serial'].",<br />";
				}
			}
//			print_r($arr);
			return $arr;
		}
	}

	/**
	 * 为设备列表加载序列号
	 */
	function addSerial($rows,$srows){
		if($rows){
			foreach($rows as $key => $val){
				if(isset($srows[$val['productId']])){
					$rows[$key]['serial'] = $srows[$val['productId']];
				}
			}
			return $rows;
		}

	}

	/**
	 * 根据业务ID和业务类型删除序列号
	 */
	function delByOutStockId($businessId,$businessType){
		$this->delete(array ( 'businessId' => $businessId,'businessType' =>$businessType));
	}

}
?>
