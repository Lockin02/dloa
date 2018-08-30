<?php
/**
 * @author huangzf
 * @Date 2011年11月27日 14:36:58
 * @version 1.0
 * @description:零配件订单清单 Model层
 */
class model_service_accessorder_accessorderitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_accessorder_item";
		$this->sql_map = "service/accessorder/accessorderitemSql.php";
		parent::__construct ();
	}

	/**
	 *
	 * 根据订单id,产品id 获取已出库信息
	 * @param  $orderId
	 * @param  $productId
	 */
	function getOutNum($orderId, $productId) {
		$shipSql = "select  ifnull(sum(oi.`actOutNum`),0) as actOutNum from `oa_stock_outstock_item` oi
			inner join `oa_stock_outstock` o on (o.`id`=oi.`mainId`)
	    		where  (o.`relDocType`='XSCKFHD' or o.relDocType='QTCKFHD') and o.docStatus='YSH' and `oi`.relDocId in(
	        		select sp.id
				from oa_stock_ship si
	        		inner join `oa_stock_ship_product` `sp`
	            	on(sp.`mainId`=`si`.`id`)  where si.`docId`=$orderId and `sp`.`productId`=$productId and si.docType='oa_service_accessorder'
        )";
		return $this->findSql ( $shipSql );
	}

	/**
	 *
	 * 判断是否已经出库完毕
	 * @param  $mainId
	 */
	function isShipAll_d($mainId) {
		$accessorderDao = new model_service_accessorder_accessorder ();
		$accessorderArr = $accessorderDao->get_d ( $mainId );
		$checkResult = "1"; //0是还有未出库的,1是已经出库完毕

		foreach ( $accessorderArr['items'] as $key => $value ) {
			$outNumArr = $this->getOutNum ( $mainId, $value ['productId'] );
			if ($value ['proNum'] - $outNumArr [0] ['actOutNum'] > 0) {
				$checkResult = "0";
				break;
			}
		}
		return $checkResult;
	}
}
?>