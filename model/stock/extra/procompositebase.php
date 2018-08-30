<?php
/**
 * @author huangzf
 * @Date 2012年6月1日 星期五 11:27:45
 * @version 1.0
 * @description:产品物料库存采购销售综合表基本信息 Model层 
 */
class model_stock_extra_procompositebase extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_stock_extra_procompositebase";
		$this->sql_map = "stock/extra/procompositebaseSql.php";
		parent::__construct ();
	}
	
	/**
	 * 
	 * Enter 查看页面清单显示模板 
	 * @param  $obj
	 */
	function showItemAtView($obj) {
		//		if (is_array ( $obj ['items'] )) {
		//			$i = 0; //列表记录序号
		//			$str = ""; //返回的模板字符串
		//			$itemArr = array ();
		//			foreach ( $obj ['items'] as $key => $val ) {
		//				if (isset ( $itemArr [$val ['goodsId']] )) {
		//					array_push ( $itemArr [$val ['goodsId']], $val ); //= $val;
		//				} else {
		//					$itemArr [$val ['goodsId']] = array ($val );
		//				}
		//			}
		//			//			echo "<pre>";
		//			//			print_r ( $itemArr );
		//			
		//
		//			foreach ( $itemArr as $proId => $proObj ) {
		//				$seNum = $i + 1;
		//				if (($seNum % 2) == "0") {
		//					$bgcolor = "#FFFFE0";
		//				} else {
		//					$bgcolor = "#fffef9";
		//				}
		//				
		//				$rowSpanNum = count ( $proObj );
		//				foreach ( $proObj as $key => $val ) {
		//					if ($val ['isProduce'] == "0") {
		//						$isProduceName = "在产";
		//					} else {
		//						$isProduceName = "停产";
		//					}
		//					if ($key == "0") {
		//						$str .= <<<EOT
		//				<tr align="center" bgcolor="$bgcolor">
		//                               <td rowspan="$rowSpanNum">
		//                                    $seNum
		//                                </td>
		//                                <td  rowspan="$rowSpanNum">
		//                                   $val[goodsName]
		//                                </td>
		//                                <td>
		//									 $val[productCode]
		//                                </td>
		//                              <td align="left">
		//									 $val[productName]
		//                                </td>                                
		//                                <td>
		//                                    $val[pattern]
		//                                </td>
		//                                <td>
		//                                    $val[unitName]
		//                                </td>
		//                                <td>
		//                                   $val[forecastSaleNum]
		//                                </td>
		//                                <td >
		//                                    $val[availableNum]
		//                                </td>
		//                                <td >
		//                                    $val[planPurchNum]
		//                                </td>
		//                                <td>
		//                                    $val[purchDays]
		//                                </td>
		//                                <td>
		//                                   $isProduceName
		//                                </td>
		//                                <td>
		//                                    $val[remark]
		//                                </td>
		//                </tr>
		//EOT;
		//						$i ++;
		//					} else {
		//						$str .= '<tr align="center" bgcolor="' . $bgcolor . '"><td>' . $val [productCode] . '</td><td align="left">' . $val [productName] . '</td><td>' . $val [pattern] . '</td><td>' . $val [unitName] . '</td><td>' . $val [forecastSaleNum] . '</td><td>' . $val [availableNum] . '</td><td>' . $val [planPurchNum] . '</td><td>' . $val [purchDays] . '</td><td>' . $isProduceName . '</td><td>' . $val [remark] . '  </td></tr>';
		//					}
		//				
		//				}
		//			}
		//			return $str;
		//		}
		$str = ""; //返回的模板字符串
		if (is_array ( $obj ['items'] )) {
			$i = 0; //列表记录序号
			foreach ( $obj ['items'] as $key => $val ) {
				
				//$contractExeNum = $this->getConEquNum ( $val ['goodsId'] );
				//$inventoryNum=$this->getProActNum($val ['goodsId']);
				$seNum = $i + 1;
				$seNum = $i + 1;
				if (($seNum % 2) == "0") {
					$bgcolor = "#FFFFE0";
				} else {
					$bgcolor = "#fffef9";
				}
				if ($val ['isProduce'] == "0") {
					$isProduceName = "在产";
				} else {
					$isProduceName = "停产";
				}
				$str .= <<<EOT
				<tr align="center" bgcolor="$bgcolor">
                               <td >
                                    $seNum
                                </td>
                                <td>
									 $val[goodsName]
                                </td>
                             	 <td >
									 $isProduceName
                                </td>                                
                                <td>
                                   $val[forecastSaleNum]
                                </td>
                               <td >
                               		 $val[exeNum]
                                </td>
                                <td >
                                	 $val[availableNum]
                                </td>
                                <td >
                                    $val[planPurchNum]
                                </td>
                                <td>
                                    $val[purchDays]
                                </td>
                                <td>
                                    $val[deliverDays]
                                </td>                                
                                <td>
                                    $val[remark]
                                </td>
                </tr>
EOT;
				$i ++;
			}
		}
		return $str;
	}
	
	/*--------------------------------------------业务操作--------------------------------------------*/
	
	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			
			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				
				switch ($object ['periodType']) {
					case "0" :
						$monthSe = $object ['periodSeNum'] / 2;
						$monthMo = $object ['periodSeNum'] % 2;
						
						if ($monthMo > 0) {
							$month = round ( $monthSe ) . "月份上半月";
						} else {
							$month = $monthSe . "月份下半月";
						}
						$object ['reportName'] = "(" . $object ['activeYear'] . "年$month" . "常用设备备货时间及库存信息)";
						break;
					case "1" :
						$object ['reportName'] = "(" . $object ['activeYear'] . "年" . $object ['periodSeNum'] . "月份常用设备备货时间及库存信息)";
						break;
					case "2" :
						$object ['reportName'] = "(" . $object ['activeYear'] . "年第" . $object ['periodSeNum'] . "季度常用设备备货时间及库存信息)";
						break;
				}
				
				$id = parent::add_d ( $object, true );
				$procompositebaseitemDao = new model_stock_extra_procompositebaseitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $id, $object ['items'] );
				$itemsObj = $procompositebaseitemDao->saveDelBatch ( $itemsArr );
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
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				switch ($object ['periodType']) {
					case "0" :
						$monthSe = $object ['periodSeNum'] / 2;
						$monthMo = $object ['periodSeNum'] % 2;
						
						if ($monthMo > 0) {
							$month = round ( $monthSe ) . "月份上半月";
						} else {
							$month = $monthSe . "月份下半月";
						}
						$object ['reportName'] = "(" . $object ['activeYear'] . "年$month" . "常用设备备货时间及库存信息)";
						break;
					case "1" :
						$object ['reportName'] = "(" . $object ['activeYear'] . "年" . $object ['periodSeNum'] . "月份常用设备备货时间及库存信息)";
						break;
					case "2" :
						$object ['reportName'] = "(" . $object ['activeYear'] . "年第" . $object ['periodSeNum'] . "季度常用设备备货时间及库存信息)";
						break;
				}
				
				$editResult = parent::edit_d ( $object, true );
				$procompositebaseitemDao = new model_stock_extra_procompositebaseitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $procompositebaseitemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $editResult;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 * 通过id获取详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		$procompositebaseitemDao = new model_stock_extra_procompositebaseitem ();
		$procompositebaseitemDao->sort = "goodsId";
		$procompositebaseitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $procompositebaseitemDao->listBySqlId ();
		return $object;
	
	}
	
	/**
	 * 
	 * 激活报表
	 */
	function activeReport($id) {
		$this->update ( array ("isActive" => 1 ), array ("isActive" => 0 ) );
		return $this->updateById ( array ("id" => $id, "isActive" => "1" ) );
	}
	
	/**
	 * 
	 * 获取合同执行中数量
	 * @param $equId
	 */
	function getConEquNum($equId) {
		//		echo $equId;
		$equipmentproDao = new model_stock_extra_equipmentpro ();
		$equipPros = $equipmentproDao->findAll ( array ("mainId" => $equId ) );
		//		
		$productIdStr = "";
		if (is_array ( $equipPros )) {
			$orderDao = new model_contract_contract_equ ();
			foreach ( $equipPros as $key => $value ) {
				$productIdStr .= "$value[productId]";
				if (($key + 1) < count ( $equipPros )) {
					$productIdStr .= ",";
				}
			
			}
			$proNum = $orderDao->getExeNum ( $productIdStr );
			//			$proNum = 0;
			//			if (is_array ( $contractProArr )) {
			//				foreach ( $contractProArr as $key => $value ) {
			//					$proNum += $value ['num'];
			//				}
			//			}
			//		    $contractEquDao=new	model_contract_contract_equ();
			//			return $contractEquDao->getExeNum($equId);
			return $proNum;
		} else {
			return "0";
		}
	}
	/**
	 * 
	 * 获取设备附属物料的库存数量
	 * @param unknown_type $equId
	 */
	function getProActNum($equId) {
		$equipmentproDao = new model_stock_extra_equipmentpro ();
		$equipPros = $equipmentproDao->findAll ( array ("mainId" => $equId ) );
		
		$productIdStr = "";
		if (is_array ( $equipPros )) {
			$inventDao = new model_stock_inventoryinfo_inventoryinfo ();
			foreach ( $equipPros as $key => $value ) {
				$productIdStr .= "$value[productId]";
				if (($key + 1) < count ( $equipPros )) {
					$productIdStr .= ",";
				}
			
			}
			$stockSystemDao = new model_stock_stockinfo_systeminfo ();
			$stockObj = $stockSystemDao->get_d ( 1 );
			$inventProArr = $inventDao->getInventoryInfos ( $stockObj ['salesStockId'], $productIdStr );
			$proNum = 0;
			if (is_array ( $inventProArr )) {
				foreach ( $inventProArr as $key => $value ) {
					$proNum += $value ['actNum'];
				}
			}
			return $proNum;
		} else {
			return "0";
		}
	}
}
?>