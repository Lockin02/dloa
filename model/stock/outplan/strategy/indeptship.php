<?php
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/stock/outplan/strategy/shipStrategy.php';
/**
 * @author zengzx
 * @Date 2011年5月5日 17:03:12
 * @version 1.0
 * @description:销售发货单策略
 */
class model_stock_outplan_strategy_indeptship  implements shipStrategy {

	function __construct() {

	}
		/**
	 * @description 发货单列表显示模板
	 * @param $rows
	 */
	function showList($rows){

	}


	/**
	 * @description 新增发货时，清单显示模板
	 * @param $rows
	 */
	function showItemAddByPlan($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$contProDao = new model_projectmanagent_order_orderequ();
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$contNumArr = $contProDao->_db->getArray( " select c.number,c.isSell from " . $contProDao->tbl_name . " c where c.orderId = " . $rows[$key]['docId'] . " and c.productId = " . $rows[$key]['productId'] );
				$contNum = $contNumArr[0]['number'];
				$number = $val['number']-$val['executedNum'];
				if(empty($contNumArr[0]['isSell'])){
					$isPresent = "<font color='red'>是</font>";
				}else{
					$isPresent = "<font>否</font>";
				}
				if( $number == 0 ){
					continue;
				}
				$j = $i + 1;
				//if($val['notCarryAmount'] == 0) continue;
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>
							<input type="text" id="productNo$j" readonly="true" name="ship[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="productId$j" name="ship[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="ship[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="text" id="productModel$j" readonly="true" name="ship[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem'/>
						</td>
						<td>
							<font color=green>$contNum</font>
						</td>
						<td>
							<input type="hidden" name="ship[productsdetail][$j][contNum]" id="contNum$j" value="$contNum" class='txtshort'/>
							<input type="text" name="ship[productsdetail][$j][number]" id="number$j" value="$number" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td width="8%">$isPresent</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][remark]" id="remark" class='txtlong'/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i ++;
			}

		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;
	}

	/**
	 * @description 新增发货时，清单显示模板
	 * @param $rows
	 */
	function showItemAdd($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//if($val['notCarryAmount'] == 0) continue;
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>
							<input type="text" id="productNo$j" readonly="true" name="ship[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="productId$j" name="ship[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="ship[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="text" name="ship[productsdetail][$j][applyNum]" id="shipNum$j" value="$val[number]" class='readOnlyTxtItem' onblur="checkThis($j)"/>
							<input type="hidden" id="canShip$j" value="$val[notCarryAmount]"/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i ++;
			}

		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;

	}

	/**
	 * @description 修改发货时，清单显示模板
	 * @param $rows
	 */
	function showItemEdit($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>
							<input type="text" id="productNo$j" readonly="true" name="ship[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="productId$j" name="ship[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="ship[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="text" id="productModel$j" readonly="true" name="ship[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem'/>
						</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][remark]" id="remark" value="$val[remark]" class='txtlong'/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i ++;
			}

		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;
	}

	/**
	 * @description 查看发货时，清单显示模板
	 * @param $rows
	 */
	function showItemView($rows) {
		$j = 0;
		$str = ""; //返回的模板字符串
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			foreach ( $rows as $key => $val ) {
				$productNameStr = model_common_util::mb_str_split ( $val ['productName'], 20 );
				$remarkStr = model_common_util::mb_str_split ( $val ['remark'], 40);
				$j = $i + 1;
				$str .= <<<EOT
						<tr height="30px">
							<td width="8%">$j</td>
							<td>$val[productNo]</td>
							<td>$productNameStr</td>
							<td>$val[productModel]</td>
							<td width="15%">$val[number]</td>
							<td width="30%">$remarkStr</td>
						</tr>
EOT;
				$i ++;
			}
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}

	/**
	 * @description 打印发货时，清单显示模板
	 * @param $rows
	 */
	function showItemPrint($rows) {
		$j = 0;
		$str = ""; //返回的模板字符串
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			foreach ( $rows as $key => $val ) {
				$productNameStr = model_common_util::mb_str_split ( $val ['productName'], 20 );
				$remarkStr = model_common_util::mb_str_split ( $val ['remark'], 40);
				$j = $i + 1;
				$str .= <<<EOT
						<tr height="30px">
							<td width="8%">$j</td>
							<td>$val[productNo]</td>
							<td>$productNameStr</td>
							<td>$val[productModel]</td>
							<td width="15%">$val[number]</td>
							<td width="30%">$remarkStr</td>
						</tr>
EOT;
				$i ++;
			}
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}

	/**
	 * 查看相关业务信息
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr =false) {
	}

	/**
	 * 根据发货计划新增发货单时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAddByPlan($paramArr = false, $relItemArr = false){

	}
	/**
	 * 新增发货时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
  		if( $paramArr['planId'] ){
  			$planDao = new model_stock_outplan_outplan();
			$planDao->updateBusinessByShip($paramArr['planId']);
  		}
	}
	/**
	 * 修改发货时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) {
  		if( $paramArr['planId'] ){
  			$planDao = new model_stock_outplan_outplan();
			$planDao->updateBusinessByShip($paramArr['planId']);
  		}
	}
	/**
	 * 删除发货时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtDel($paramArr = false) {
  		if( $paramArr['planId'] ){
  			$planDao = new model_stock_outplan_outplan();
			$planDao->updateBusinessByShip($paramArr['planId']);
  		}
	}

	/**
	 * 下推获取源单数据方法
	 */
	 function getDocInfo( $id ){
	 	$docDao = new model_stock_outplan_outplan();
	 	$rows = $docDao -> get_d( $id );
		return $rows;
	 }

//	/**
//	 * 出库时处理相关业务操作
//	 */
//	 function dealOutStock_d( $rows ){
//		$outplanDao = new model_stock_outplan_outplan();
//		$detail = $rows['details'];
//		foreach ( $detail as $key => $val ){
//			//更新合同设备数量
//			$contEquSql = "update oa_sale_order_equ set executedNum=executedNum+" . $val['number']
//			  . " where orderId = " . $rows['docId'] . " and productId = " . $val['productId'];
//			$outplanDao->_db->query( $contEquSql );
//			//更新计划设备数量
//			$outplanSql = "update oa_stock_outplan_product set executedNum=executedNum+" . $val['number']
//			  . " where mainId = " . $rows['planId'] . " and productId = " . $val['productId'];
//			$outplanDao->_db->query( $outplanSql );
//		}
//		$outplanDao->updatePlanStatus_d( $rows['planId'],$rows );
//	 }


}
?>