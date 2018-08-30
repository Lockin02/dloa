<?php

/**
 * @author Administrator
 * @Date 2011年8月9日 19:37:07
 * @version 1.0
 * @description:盘点基本信息 Model层
 */
class model_stock_check_checkinfo extends model_base {
	public $checkinfoStrategyArr = array ();
	function __construct() {
		$this->tbl_name = "oa_stock_check_info";
		$this->sql_map = "stock/check/checkinfoSql.php";
		parent::__construct ();
		$this->checkPreArr = array (//单据编号前缀
"OVERAGE" => "YADJ", "SHORTAGE" => "KADJ" );
		$this->auditStatus = array (//单据状态
"WPD" => "未盘点", "YPD" => "已盘点" );
	}
	
	/*===================================页面模板======================================*/
	
	/**
	 * @desription 列表显示模板方法
	 * @param tags
	 * @date 2011-08-17
	 * @lizx
	 */
	function showViewDePro($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$seNum = $i = 1;
				$str .= <<<EOT
					<tr align="center">
					<td>
                     $seNum
					</td>
					<td>
						{$val['productCode']}
					</td>
					<td>
						{$val['productName']}
					</td>
					<td>
					{$val['pattern']}
					</td>
					 <td>
					{$val['unitName']}
					</td>
					<td>
					{$val['batchNum']}
					</td>
					<td>
					{$val['billNum']}
					</td>
					<td>
					{$val['actNum']}
					</td>
					<td>
					{$val['adjustNum']}
					</td>
					<td class="txtshort formatMoney">
					{$val['price']}
					</td>
					<td  class="txtshort formatMoney">
					{$val['subPrice']}
					</td>
					<td>
						{$val['stockName']}
					</td>
					<td>

					{$val['remark']}

					</td>
               </tr>
EOT;
				$i ++;
			}
			return $str;
		}
	}
	
	/**
	 * @desription 修改类表显示数据
	 */
	function showEditDePro($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$seNum = $i + 1;
				$str .= <<<EOT
						<tr align="center" >
					    <td class="tabledata">
					    $seNum
					    </td>
					    <td class="tabledata">
					   	 <input type="text" value="$val[productCode]" id="productCode$i" name="checkinfo[items][$i][productCode]"  class="txtshort"/>
					   	 <input type="hidden" value="$val[id]" id="id$i" name="checkinfo[items][$i][id]">
					   	 <input type="hidden" name="checkinfo[items][$i][productId]" id="productId$i" value="$val[productId]"/>
					    </td>
					    <td class="tabledata">
					    	<input type="text" value="$val[productName]" name="checkinfo[items][$i][productName]" id="productName$i"  class="txt"/>
					    </td>
					     <td class="tabledata">
					    <input type="text" value="$val[pattern]" name="checkinfo[items][$i][pattern]" id="pattern$i"  readOnly class="readOnlyTxtItem"/>
					    </td>
					     <td class="tabledata">
					    <input type="text" value="$val[unitName]" name="checkinfo[items][$i][unitName]" id="unitName$i" readOnly class="readOnlyTxtItem"/>
					    </td>
					     <td class="tabledata">
					    <input type="text" value="$val[batchNum]" name="checkinfo[items][$i][batchNum]" id="batchNum$i" readOnly class="readOnlyTxtItem"/>
					    </td>
					     <td class="tabledata">
					    <input type="text" value="$val[billNum]" name="checkinfo[items][$i][billNum]" id="billNum$i" onblur="countFun('billNum$i','actNum$i','adjustNum$i')" readOnly class="readOnlyTxtItem"/>
					    </td>
					     <td class="tabledata">
					    <input type="text" value="$val[actNum]" name="checkinfo[items][$i][actNum]" id="actNum$i"  onblur="countFun('billNum$i','actNum$i','adjustNum$i');FloatMul('price$i','adjustNum$i','subPrice$i')" class="txtshort" />
					    </td>
					     <td class="tabledata">
					    <input type="text" value="$val[adjustNum]" name="checkinfo[items][$i][adjustNum]" id="adjustNum$i" readOnly class="readOnlyTxtItem"/>
					    </td>
					     <td class="tabledata">
					    <input type="text" value="$val[price]" name="checkinfo[items][$i][price]" id="price$i" onblur="FloatMul('price$i','adjustNum$i','subPrice$i')" class="txtshort formatMoney"  />
					    </td>
					     <td class="tabledata">
					    <input type="text" value="$val[subPrice]" name="checkinfo[items][$i][subPrice]" id="subPrice$i"  readOnly class="readOnlyTxtItem formatMoney"/>
					    </td>
					    <td class="tabledata">
					    <input type="text" value="$val[stockName]" name="checkinfo[items][$i][stockName]" id="stockName$i" class="txtshort" />
					    <input type="hidden" value="$val[stockCode]" name="checkinfo[items][$i][stockCode]" id="stockCode$i"  />
					    <input type="hidden" value="$val[stockId]" name="checkinfo[items][$i][stockId]" id="stockId$i" />
					    </td>
					    <td class="tabledata">
					    <input type="text" value="$val[remark]" name="checkinfo[items][$i][remark]" id="remark$i" class="txtshort" />
					    </td>
					    <td>
							<img src="images/closeDiv.gif" onclick="delItem(this);" title="删除行">
						</td>
				</tr>
EOT;
				$i ++;
			}
			return $str;
		}
	}
	/*===================================业务处理======================================*/
	
	/**
	 * 设置关联从表的申请单id信息
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			$value [$mainIdName] = $mainIdValue;
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}
	
	/* @desription 根据id获取申请单所有产品信息
	 * @param tags
	 * @date 2011-8-17
	 */
	function get_d($id) {
		$checkitemDao = new model_stock_check_checkitem ();
		$checkitemDao->searchArr ['checkId'] = $id;
		$items = $checkitemDao->listBySqlId ();
		$checkInfo = parent::get_d ( $id );
		$checkInfo ['details'] = $items; //details被c层获取
		return $checkInfo;
	}
	/**
	 * @desription 添加保存方法
	 * @date 2011-8-17
	 * @chenzb
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
//				$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
//				if ("audit" == $actType) {
//					$object ['ExaDT'] = date ( 'y-m-d' );
//				}
				
				/*s:1.保存主表基本信息*/
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode ( "oa_stock_check_info", $this->checkPreArr [$object ['checkType']] );
				$id = parent::add_d ( $object, true );
				
				/*e:1.保存主表基本信息*/
				
				/*s:2.保存从表物料信息*/
				$checkItemDao = new model_stock_check_checkitem ();
				$itemsObjArr = $object ['items'];
				$itemsArr = $this->setItemMainId ( "checkId", $id, $itemsObjArr );
				$itemsObj = $checkItemDao->saveDelBatch ( $itemsArr );
				/*e:2.保存从表物料信息*/
				
				/*s:3.如果未单据状态为已盘点，则更新库存*/
				$inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //库存DAO
				if ("YPD" == $object ['auditStatus']) {
					foreach ( $itemsObj as $key => $value ) {
						$stockParamArr = array ("stockId" => $value ['stockId'], "productId" => $value ['productId'] );
						if ($object ['checkType'] == "OVERAGE") {
							
							$inventoryDao->updateInTimeInfo ( $stockParamArr, $value ['adjustNum'], "instock" );
						}
						if ($object ['checkType'] == "SHORTAGE") {
							$inventoryDao->updateInTimeInfo ( $stockParamArr, $value ['adjustNum'], "outstock" );
						
						}
					}
				}
				/*s:3.如果未单据状态为已盘点，则更新库存*/
				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 * @desription 修改保存方法
	 * @chenzb
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
				//				$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
				//				if ("audit" == $actType) {
				//				$object['ExaDT']=date('y-m-d');
				//			}
				//				$codeDao = new model_common_codeRule();
				/*s:1.保存主表基本信息*/
				$id = parent::edit_d ( $object, true );
				
				/*s:2.保存从表物料信息*/
				$checkItemDao = new model_stock_check_checkitem ();
				$itemsArr = $this->setItemMainId ( "checkId", $object ['id'], $object ['items'] );
				$itemsObj = $checkItemDao->saveDelBatch ( $itemsArr );
				/*s:3.如果未单据状态为已盘点，则更新库存*/
				
				$inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //库存DAO
				if ("YPD" == $object ['auditStatus']) {
					foreach ( $itemsObj as $key => $value ) {
						$stockParamArr = array ("stockId" => $value ['stockId'], "productId" => $value ['productId'] );
						if ($object ['checkType'] == "OVERAGE") {
							
							$inventoryDao->updateInTimeInfo ( $stockParamArr, $value ['adjustNum'], "instock" );
						}
						if ($object ['checkType'] == "SHORTAGE") {
							$inventoryDao->updateInTimeInfo ( $stockParamArr, $value ['adjustNum'], "outstock" );
						}
					
					}
				}
				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "单据信息不完整!" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	/**
	 * 反审核入库单
	 *
	 */
	function ctCancelAudit($id, $checkType) {
		$checkinfoObject = $this->get_d ( $id, $checkType );
		
		try {
			
			if ($checkinfoObject ['auditStatus'] == "YPD") { //确认单据时已审核入库
				$obj = array ("id" => $id, "auditStatus" => "WPD" );
				$this->updateById ( $obj );
				$inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //库存DAO
				

				foreach ( $checkinfoObject ['details'] as $key => $value ) {
					//还原库存
					$stockParamArr = array ("stockId" => $value ['stockId'], "productId" => $value ['productId'] );
					if ($checkinfoObject ['checkType'] == "OVERAGE") {
						$inventoryDao->updateInTimeInfo ( $stockParamArr, $value ['adjustNum'], "outstock" );
					}
					if ($checkinfoObject ['checkType'] == "SHORTAGE") {
						$inventoryDao->updateInTimeInfo ( $stockParamArr, $value ['adjustNum'], "instock" );
					}
				
				}
			
			} else {
				throw new Exception ( "单据状态有误，请确认！" );
			
			}
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	
	}

}
?>