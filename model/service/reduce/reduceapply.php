<?php
/**
 * @author huangzf
 * @Date 2011年12月3日 10:32:24
 * @version 1.0
 * @description:维修费用减免申请单 Model层
 */
class model_service_reduce_reduceapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_reduce_apply";
		$this->sql_map = "service/reduce/reduceapplySql.php";
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
                                    <input type="text" name="reduceapply[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" readOnly value="{$val['productCode']}" />
                                    <input type="hidden" name="reduceapply[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
                                    <input type="hidden" name="reduceapply[items][$i][id]" id="id$i" value="{$val['id']}"  />
                                    <input type="hidden" name="reduceapply[items][$i][applyItemId]" id="applyItemId$i" value="{$val['applyItemId']}"  />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][productName]" id="productName$i" class="readOnlyText" readOnly value="{$val['productName']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][productType]" id="productType$i" class="readOnlyTxtShort" readOnly value="{$val['productType']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" readOnly value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort" readOnly value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][serilnoName]" id="serilnoName$i" class="readOnlyText" readOnly value="{$val['serilnoName']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][fittings]" id="fittings$i" class="readOnlyText" readOnly value="{$val['fittings']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][cost]" id="cost$i" class="readOnlyTxtShort  formatMoney" readOnly value="{$val['cost']}" />
                                </td>
                                <td>
                                    <input type="text" name="reduceapply[items][$i][reduceCost]" id="reduceCost$i" class="txtshort  formatMoney" value="{$val['reduceCost']}" />
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
			foreach ( $rows as $key => $val ) {
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
                                    $val[productType]
                               </td>
                               <td>
                                    $val[pattern]

                               </td>
                               <td>
									$val[unitName]
                               </td>
                               <td>
                                    $val[serilnoName]
                               </td>
                               <td>
									$val[fittings]
                               </td>
                               <td class="formatMoney">
                                    $val[cost]
                               </td>
                               <td class="formatMoney">
                                    $val[reduceCost]
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
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode ( "oa_service_reduce_apply", "WXFY" );
				$id = parent::add_d ( $object, true );
				$reduceitemDao = new model_service_reduce_reduceitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $id, $object ['items'] );
				$itemsObj = $reduceitemDao->saveDelBatch ( $itemsArr );
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
				$editResult = parent::edit_d ( $object, true );
				$reduceitemDao = new model_service_reduce_reduceitem ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $reduceitemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return true;
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
		$reduceitemDao = new model_service_reduce_reduceitem ();
		$reduceitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $reduceitemDao->listBySqlId ();
		return $object;

	}

	/**
	 *
	 * 审批完成的时,更新对应申请单的减免费用
	 */
	function dealAfterAudit_d($id) {
		try {
			$this->start_d ();
			$reduceApplyObj = $this->get_d ( $id );
			if (! empty ( $reduceApplyObj ['applyId'] )) {
				//更新申请单减免费用
				$repairapplyDao = new model_service_repair_repairapply ();
				$repairapply = array ("id" => $reduceApplyObj ['applyId'], "subReduceCost" => $reduceApplyObj ['subReduceCost'] );
				$repairapplyDao->updateById ( $repairapply );

				//更新申请单清单减免费用
				if (is_array ( $reduceApplyObj ['items'] )) {
					$applyItemDao = new model_service_repair_applyitem ();
					foreach ( $reduceApplyObj ['items'] as $key => $value ) {
						$applyItemObj = array ("id" => $value ['applyItemId'], "reduceCost" => $value ['reduceCost'] );
						$applyItemDao->updateById ( $applyItemObj );
					}
				}
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * workflow callback
	 */
	 function workflowCallBack($spid){
	 	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];

		$reductObj = $this->get_d ( $objId );
		if ($reductObj ['ExaStatus'] != '打回') {
			$this->dealAfterAudit_d ( $objId );
		}
	 }

}
?>