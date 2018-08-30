<?php
/**
 * @author huangzf
 * @Date 2011年11月27日 15:50:15
 * @version 1.0
 * @description:零配件订单 Model层
 */
class model_service_accessorder_accessorder extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_accessorder";
		$this->sql_map = "service/accessorder/accessorderSql.php";
		parent::__construct ();
	}
	/**
	 *
	 * 编辑页面从表显示模板
	 * @param  $rows
	 */
	function showItemAtEdit($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$seNum = $i + 1;
				$str .= <<<EOT
				<tr align="center" >
								<td>
			                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
			                    </td>
                               <td>
                                    $seNum
                                </td>
                                <td>
                                	<input type="hidden" name="accessorder[items][$i][productId]" id="productId$i" value="{$val['productId']}" />
                                    <input type="text" name="accessorder[items][$i][productCode]" id="productCode$i" class="txtshort" value="{$val['productCode']}" />
                                    <input type="hidden" name="accessorder[items][$i][id]" id="id$i" value="{$val['id']}" />
                                </td>
                                <td>
                                    <input type="text" name="accessorder[items][$i][productName]" id="productName$i" class="txt" value="{$val['productName']}" />
                                </td>
                                <td>
                                    <input type="text" name="accessorder[items][$i][pattern]" id="pattern" class="readOnlyTxtShort" value="{$val['pattern']}" readOnly/>
                                </td>
                                <td>
                                    <input type="text" name="accessorder[items][$i][unitName]" id="unitNames$i" class="readOnlyTxtShort" value="{$val['unitName']}" readOnly/>
                                </td>
                                <td>
                                    <input type="text" name="accessorder[items][$i][warranty]" id="warranty$i" class="txtshort" value="{$val['warranty']}" />
                                </td>
                                <td>
                                    <input type="text" name="accessorder[items][$i][proNum]" id="proNum$i" class="txtshort"  value="{$val['proNum']}"
                                    onblur="FloatMul('price$i','proNum$i','subCost$i')"/>
                                </td>
                                <td>
                                    <input type="text" name="accessorder[items][$i][price]" id="price$i" class="txtshort formatMoney"  value="{$val['price']}"
                                    onblur="FloatMul('price$i','proNum$i','subCost$i')"/>
                                </td>
                                <td>
                                    <input type="text" name="accessorder[items][$i][subCost]" id="subCost$i" class="readOnlyTxtShort formatMoney"  value="{$val['subCost']}" readOnly/>
                                </td>
		                </tr>
EOT;
				$i ++;
			}
			return $str;
		}
	}

	/**
	 *
	 * 查看页面从表显示模板
	 * @param  $rows
	 */
	function showItemAtView($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			$accessorderDao=new model_service_accessorder_accessorderitem();
			
			foreach ( $rows as $key => $val ) {
				$outNumArr=$accessorderDao->getOutNum($val['mainId'], $val['productId']);
				$seNum = $i + 1;
				$str .= <<<EOT
						<tr align="center" >
                               <td>
                                    $seNum
                               </td>
                               <td>
									$val[productCode]

                               </td>
                               <td>
									$val[productName]
                               </td>
                               <td>
                                    $val[pattern]
                               </td>
                               <td>
									$val[unitName]

                               </td>
                               <td>
									$val[warranty]
                               </td>
                               <td>
                                    $val[proNum]
                               </td>
                               <td>
                               		{$outNumArr[0][actOutNum]}
                               </td>
                               <td class="formatMoney">
									$val[price]
                               </td>
                               <td class="formatMoney">
									$val[subCost]
                               </td>
		                </tr>
EOT;
				$i ++;
			}
			return $str;
		}
	}

	/*--------------------------------------------业务操作--------------------------------------------*/

	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
				$sql = "SELECT MAX(docDate) as applyDate from ".$this->tbl_name;
				$applyDateArr = $this->_db->get_one($sql);
				$applyDate = $applyDateArr['applyDate'];
				$thisDate = day_date;
				$codeDao = new model_common_codeRule ();
				if( $applyDate!= $thisDate ){
					$object ['docCode'] = $codeDao->accessorderCode ( "oa_service_accessorder",$object['codePrefix'],$object['prov'],"零配件订单",true );
				}else{
					$object ['docCode'] = $codeDao->accessorderCode ( "oa_service_accessorder",$object['codePrefix'],$object['prov'],"零配件订单",false );
				}
				$id = parent::add_d ( $object, true );
				$accessorderitemDao = new model_service_accessorder_accessorderitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $id, $object ['items'] );
				$itemsObj = $accessorderitemDao->saveDelBatch ( $itemsArr );

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
			$this->start_d ();
//				echo "<pre>";
//				print_r($object);
			if (is_array ( $object ['items'] )) {
				$editResult = parent::edit_d ( $object, true );
				$accessorderitemDao = new model_service_accessorder_accessorderitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $accessorderitemDao->saveDelBatch ( $itemsArr );
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
		$accessorderitemDao = new model_service_accessorder_accessorderitem ();
		$accessorderitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $accessorderitemDao->listBySqlId ();
		return $object;

	}
		function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			$value [$mainIdName] = $mainIdValue;
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}
}
?>