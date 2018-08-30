<?php
header ( "Content-type: text/html; charset=gb2312" );

//引入接口
include_once WEB_TOR . 'model/stock/outplan/strategy/shipStrategy.php';
/**
 * @author zengzx
 * @since 1.0 - 2011-12-1
 * @description:工程项目发货单策略(配件发货单策略)
 */
class model_stock_outplan_strategy_accessordership implements shipStrategy {
	
	function __construct() {
	
	}
	/**
	 * @description 发货单列表显示模板
	 * @param $rows
	 */
	function showList($rows) {
	
	}
	
	/**
	 * @description 新增发货时，清单显示模板
	 * @param $rows
	 */
	function showItemAddByPlan($rows) {
		$accessorderitemDao = new model_service_accessorder_accessorderitem ();
		if (is_array ( $rows ['items'] )) {
			$j = 0;
			foreach ( $rows ['items'] as $key => $val ) {
				$outNumArr = $accessorderitemDao->getOutNum ( $rows ['id'], $val ['productId'] );
				$notOutNum = $val ['proNum'] - $outNumArr[0]['actOutNum'];
				if ($notOutNum > 0) {
					$seNum = $j + 1;
					$str .= <<<EOT
					<tr>
						<td>
			                <img align="absmiddle" src="images/removeline.png" onclick='mydel(this,"invbody")' title="删除行" />
			            </td>
						<td>$seNum</td>
						<td>
							<input type="hidden" id="planId$j" name="ship[productsdetail][$j][planId]" value="$rows[id]" />
								<!--
							<input type="hidden" id="docType$j" name="ship[productsdetail][$j][docType]" value="oa_service_accessorder" />
						
							<input type="hidden" id="docId$j" name="ship[productsdetail][$j][docId]" value="$rows[id]" />
							<input type="hidden" id="docCode$j" name="ship[productsdetail][$j][docCode]" value="$rows[docCode]" />
							-->
							<input type="text" id="productNo$j" readonly="true" name="ship[productsdetail][$j][productNo]" value="$val[productCode]" class='readOnlyTxtItem'/>
						</td>
						<td>
							<input type="hidden" id="planEquId$j" name="ship[productsdetail][$j][planEquId]" value="$val[id]"/>
							<input type="hidden" id="productId$j" name="ship[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="ship[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal'/></td>
						<td>
							<input type="text" id="productModel$j" readonly="true" name="ship[productsdetail][$j][productModel]" value="$val[pattern]" class='readOnlyTxtItem'/>
						</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][contNum]" id="contNum$j" value="$notOutNum" class='readOnlyTxtItem'/>

						</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][number]" id="number$j" value="$notOutNum" onblur="checkThis('number$j','contNum$j');" class='txtshort'  />
						</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][remark]" id="remark" class='txtlong'/>
						</td>
					</tr>
EOT;
					$j ++;
				}
			}
		
		}
		$str .= '<input type="hidden" id="rowNum" value="' . $j . '"/>';
		return $str;
	}
	
	/**
	 * @description 新增发货时，清单显示模板
	 * @param $rows
	 */
	function showItemAdd($rows) {
	}
	
	/**
	 * @description 修改发货时，清单显示模板
	 * @param $rows
	 */
	function showItemEdit($rows) {
		if (is_array ( $rows )) {
			$j = 0;
			foreach ( $rows as $key => $val ) {
				$seNum = $j + 1;
				$str .= <<<EOT
					<tr>
						<td>
			                <img align="absmiddle" src="images/removeline.png" onclick='mydel(this,"invbody")' title="删除行" />
			            </td>
						<td>$seNum</td>
						<td>
							<input type="hidden" id="planId$j" name="ship[productsdetail][$j][planId]" value="$val[planId]" />
							<input type="hidden" id="docType$j" name="ship[productsdetail][$j][docType]" value="$val[docType]" />
							<input type="text" id="productNo$j" readonly="true" name="ship[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/>
						</td>
						<td>
							<input type="hidden" id="planEquId$j" name="ship[productsdetail][$j][planEquId]" value="$val[planEquId]"/>
							<input type="hidden" id="productId$j" name="ship[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="ship[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal'/></td>
						<td>
							<input type="text" id="productModel$j" readonly="true" name="ship[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem'/>
						</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][contNum]" id="contNum$j" value="$val[contNum]" class='readOnlyTxtItem'/>

						</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][number]" id="number$j" value="$val[number]" onblur="checkThis('number$j','contNum$j');" class='txtshort'  />
						</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][remark]" id="remark" class='txtlong' value="$val[remark]" />
						</td>
					</tr>
EOT;
				$j ++;
			}
		
		}
		$str .= '<input type="hidden" id="rowNum" value="' . $j . '"/>';
		return $str;
	}
	
	/**
	 * @description 查看发货时，清单显示模板
	 * @param $rows
	 */
	function showItemView($rows) {
		if (is_array ( $rows )) {
			$j = 0;
			foreach ( $rows as $key => $val ) {
				$seNum = $j + 1;
				$str .= <<<EOT
					<tr>
						<td>
			               
			            </td>
						<td>$seNum</td>
						<td>
							$val[productNo]
						</td>
						<td>
							$val[productName]
						<td>
							$val[productModel]
						</td>
						<td>
							$val[contNum]
						</td>
						<td>
							$val[number]
						</td>
						<td>
							$val[remark]
						</td>
					</tr>
EOT;
				$j ++;
			}
		
		}
		$str .= '<input type="hidden" id="rowNum" value="' . $j . '"/>';
		return $str;
	}
	
	/**
	 * @description 打印发货时，清单显示模板
	 * @param $rows
	 */
	function showItemPrint($rows) {
		$j = 0;
		$str = ""; //返回的模板字符串
		$contProDao = new model_projectmanagent_order_orderequ ();
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			foreach ( $rows as $key => $val ) {
				$productNameStr = model_common_util::mb_str_split ( $val ['productName'], 20 );
				$remarkStr = model_common_util::mb_str_split ( $val ['remark'], 40 );
				if ($rows [$key] ['contEquId']) {
					$contNumArr = $contProDao->_db->getArray ( " select c.number,c.isSell from " . $contProDao->tbl_name . " c where c.orderId = " . $rows [$key] ['docId'] . " and c.id = " . $rows [$key] ['contEquId'] );
				} else {
					$contNumArr = $contProDao->_db->getArray ( " select c.number,c.isSell from " . $contProDao->tbl_name . " c where c.orderId = " . $rows [$key] ['docId'] . " and c.productId = " . $rows [$key] ['productId'] );
				}
				$contNum = $contNumArr [0] ['number'];
				if (empty ( $contNumArr [0] ['isSell'] )) {
					$isPresent = "<font color='red'>是</font>";
				} else {
					$isPresent = "<font>否</font>";
				}
				$j = $i + 1;
				$str .= <<<EOT
						<tr>
							<td width="8%">$j</td>
							<td>$val[productNo]</td>
							<td>$productNameStr</td>
							<td>$val[productModel]</td>
							<td>
								<font color=green>$contNum</font>
							</td>
							<td width="10%">$val[number]</td>
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
	function viewRelInfo($paramArr = false) {
	}
	
	/**
	 * 根据发货计划新增发货单时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAddByPlan($paramArr = false, $relItemArr = false) {
	
	}
	/**
	 * 新增发货时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		//		if ($paramArr ['planId']) {
	//			$planDao = new model_stock_outplan_outplan ();
	//			$planDao->updateBusinessByShip ( $paramArr ['planId'] );
	//		}
	}
	/**
	 * 修改发货时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) {
		//		if ($paramArr ['planId']) {
	//			$planDao = new model_stock_outplan_outplan ();
	//			$planDao->updateBusinessByShip ( $paramArr ['planId'] );
	//		}
	}
	
	/**
	 * 删除发货时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtDel($paramArr = false) {
		//		if ($paramArr ['planId']) {
	//			$planDao = new model_stock_outplan_outplan ();
	//			$planDao->updateBusinessByShip ( $paramArr ['planId'] );
	//		}
	}
	
	/**
	 * 下推获取源单数据方法
	 */
	function getDocInfo($id) {
		$accessorderDao = new model_service_accessorder_accessorder ();
		$rows = $accessorderDao->get_d ( $id );
		//		$proNumSql = "select s.planEquId,sum(s.number) as shipNum from oa_stock_ship_product s where s.planId=". $id ." group by s.planEquId";
		//		$proNumArr = $accessorderitemDao->listBySql($proNumSql);
		//		if( isset($proNumArr) )
		//			foreach( $rows['details'] as $key=>$val ){
		//				foreach( $proNumArr as $index=>$value ){
		//					if( $rows['details'][$key]['id'] == $proNumArr[$index]['planEquId']){
		//						$rows['details'][$key]['shipNum']=$proNumArr[$index]['shipNum'];
		//						break;
		//					}else{
		//						$rows['details'][$key]['shipNum']=0;
		//					}
		//				}
		//			}
		return $rows;
	}
	
	/**
	 * 获取合同销售人员
	 */
	function getSaleman($docId, $docType) {
		//      	$planDao = new model_stock_outplan_outplan();
		//      	$relatedStrategy=$planDao->relatedStrategyArr[$docType];
		//      	$saleman = $planDao->getSaleman($docId,new $relatedStrategy);
		//     	 	return $saleman;
		return array ();
	}
}
?>