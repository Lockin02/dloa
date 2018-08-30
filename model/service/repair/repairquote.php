<?php
/**
 * @author huangzf
 * @Date 2011年12月3日 10:11:04
 * @version 1.0
 * @description:维修报价申报单 Model层
 */
class model_service_repair_repairquote extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_service_repair_quote";
		$this->sql_map = "service/repair/repairquoteSql.php";
		parent::__construct ();
	}
	/**
	 *
	 * 新增报价申报单页面从表显示模板
	 * @param  $rows
	 */
	function showItemAtAdd($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				if ($val ['isGurantee'] == '0') {
					$isGuranteeYes = "selected";
					$isGuranteeNo = "";
				} else {
					$isGuranteeYes = "";
					$isGuranteeNo = "selected";
				}
				
				$repairType0 = "";
				$repairType1 = "";
				$repairType2 = "";
				
				if ($val ['repairType'] == '0') {
					$repairType0 = "selected";
					$repairType1 = "";
					$repairType2 = "";
				} else if ($val ['repairType'] == '1') {
					$repairType0 = "";
					$repairType1 = "selected";
					$repairType2 = "";
				} else if ($val ['repairType'] == '2') {
					$repairType0 = "";
					$repairType1 = "";
					$repairType2 = "selected";
				}
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
                                    <input type="text" name="repairquote[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" readonly value="{$val['productCode']}" />
                                    <input type="hidden" name="repairquote[items][$i][id]" id="id$i" value="{$val['id']}" />
                                    <input type="hidden" name="repairquote[items][$i][productId]" id="productId$i" value="{$val['productId']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" readonly value="{$val['productName']}" />
                                </td>
                                 <td>
                                    <input type="text" name="repairquote[items][$i][productType]" id="productType$i" class="readOnlyTxtShort" readonly value="{$val['productType']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][pattern]" id="pattern" class="readOnlyTxtShort" readonly value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][unitName]" id="unitNames$i" class="readOnlyTxtShort" readonly value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][serilnoName]" id="serilnoName$i" class="readOnlyTxtNormal" readonly value="{$val['serilnoName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][fittings]" id="fittings$i" class="readOnlyTxtNormal" readonly  value="{$val['fittings']}"/>
                                </td>

                                <td>
                                    <input type="text" name="repairquote[items][$i][troubleInfo]" id="troubleInfo$i" class="readOnlyTxtNormal" readonly  value="{$val['troubleInfo']}"/>
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][checkInfo]" id="checkInfo$i" class="readOnlyTxtNormal" readonly  value="{$val['checkInfo']}" />
                                </td>
                                <td>
                                    <select  name="repairquote[items][$i][isGurantee]" id="isGurantee$i" value="{$val['isGurantee']}" class="txtshort ">
                                        <option $isGuranteeYes value="0">是</option>
                                        <option $isGuranteeNo value="1">否</option>
                                     </select>

                                </td>
                                <td>
                                    <select   name="repairquote[items][$i][repairType]" id="repairType$i" class="txtshort "  value="{$val['repairType']}">
                                          <option $repairType0 value="0">收费维修</option>
                                          <option $repairType1 value="1">保内维修</option>
                                          <option $repairType2 value="2">内部维修</option>
                                     </select>
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][repairCost]" id="repairCost$i" class="txtshort formatMoney"  value="{$val['repairCost']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][cost]" id="cost$i" class="txtshort formatMoney"  value="{$val['cost']}" />
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
	 * 编辑报价申报单页面从表显示模板
	 * @param  $rows
	 */
	function showItemAtEdit($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				if ($val ['isGurantee'] == '0') {
					$isGuranteeYes = "selected";
					$isGuranteeNo = "";
				} else {
					$isGuranteeYes = "";
					$isGuranteeNo = "selected";
				}
				
				$repairType0 = "";
				$repairType1 = "";
				$repairType2 = "";
				
				if ($val ['repairType'] == '0') {
					$repairType0 = "selected";
					$repairType1 = "";
					$repairType2 = "";
				} else if ($val ['repairType'] == '1') {
					$repairType0 = "";
					$repairType1 = "selected";
					$repairType2 = "";
				} else if ($val ['repairType'] == '2') {
					$repairType0 = "";
					$repairType1 = "";
					$repairType2 = "selected";
				}
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
                                    <input type="text" name="repairquote[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" readonly value="{$val['productCode']}" />
                                    <input type="hidden" name="repairquote[items][$i][id]" id="id$i" value="{$val['id']}" />
                                    <input type="hidden" name="repairquote[items][$i][productId]" id="productId$i" value="{$val['productId']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" readonly value="{$val['productName']}" />
                                </td>
                                 <td>
                                    <input type="text" name="repairquote[items][$i][productType]" id="productType$i" class="readOnlyTxtShort" readonly value="{$val['productType']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][pattern]" id="pattern" class="readOnlyTxtShort" readonly value="{$val['pattern']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][unitName]" id="unitNames$i" class="readOnlyTxtShort" readonly value="{$val['unitName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][serilnoName]" id="serilnoName$i" class="readOnlyTxtNormal" readonly value="{$val['serilnoName']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][fittings]" id="fittings$i" class="readOnlyTxtNormal" readonly  value="{$val['fittings']}"/>
                                </td>

                                <td>
                                    <input type="text" name="repairquote[items][$i][troubleInfo]" id="troubleInfo$i" class="readOnlyTxtNormal" readonly  value="{$val['troubleInfo']}"/>
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][checkInfo]" id="checkInfo$i" class="readOnlyTxtNormal" readonly  value="{$val['checkInfo']}" />
                                </td>
                                <td>
                                    <select  name="repairquote[items][$i][isGurantee]" id="isGurantee$i" value="{$val['isGurantee']}" class="txtshort ">
                                        <option $isGuranteeYes value="0">是</option>
                                        <option $isGuranteeNo value="1">否</option>
                                     </select>

                                </td>
                                <td>
                                    <select   name="repairquote[items][$i][repairType]" id="repairType$i" class="txtshort "  value="{$val['repairType']}">
                                          <option $repairType0 value="0">收费维修</option>
                                          <option $repairType1 value="1">保内维修</option>
                                          <option $repairType2 value="2">内部维修</option>
                                     </select>
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][repairCost]" id="repairCost$i" class="txtshort formatMoney"  value="{$val['repairCost']}" />
                                </td>
                                <td>
                                    <input type="text" name="repairquote[items][$i][cost]" id="cost$i" class="txtshort formatMoney"  value="{$val['cost']}" />
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
				
				if ($val [isGurantee] == "0") {
					$val [isGurantee] = "是";
				} else {
					$val [isGurantee] = "否";
				}
				
				if ($val [repairType] == "0") {
					$val [repairType] = "收费维修";
				} else if ($val [repairType] == "1") {
					$val [repairType] = "保内维修";
				} else if ($val [repairType] == "2") {
					$val [repairType] = "内部维修";
				}
				
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

                               <td>
									$val[troubleInfo]
                               </td>
                                <td>
									$val[place]
                               </td>
                               <td>
                                    $val[checkInfo]
                               </td>
                                <td>
									$val[isGurantee]
                               </td>
                               <td>
                                    $val[repairType]
                               </td>
                               <td class="formatMoney">
									$val[repairCost]

                               </td>
                               <td class="formatMoney">
									$val[cost]
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
			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode ( "oa_service_repair_quote", "WXBJ" );
				$id = parent::add_d ( $object, true );
				
				$quoteitemDao = new model_service_repair_applyitem ();
				$repairapplyDao = new model_service_repair_repairapply ();
				
				foreach ( $object ['items'] as $key => $value ) {
					//查找更新之前清单的维修费用
					$oldquoteitem = $quoteitemDao->get_d ( $value ['id'] );
					
					//更新之前和之后的清单的维修费用之差
					$changeCost = $value ['cost'] - $oldquoteitem ['cost'];
					
					//查找清单对应的申请单
					$repairObj = $repairapplyDao->get_d ( $oldquoteitem ['mainId'] );
					
					//把获取到修改之后的金额值累加到对应申请单的维修费用并更新申请单
					$repairObj ['subCost'] = $repairObj ['subCost'] + $changeCost;
					$repairapplyDao->updateById ( $repairObj );
					
					if (! isset ( $value ['isDelTag'] )) { //删掉 不提交报价的
						$value ['quoteId'] = $id;
						$quoteitemDao->updateById ( $value );
					}
				}
				$this->commit_d ();
				return $id;
			} else {
				throw new Excetion ( "没有确认清单!" );
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
			
			$id = parent::edit_d ( $object, true );
			$quoteitemDao = new model_service_repair_applyitem ();
			$repairapplyDao = new model_service_repair_repairapply ();
			
			foreach ( $object ['items'] as $key => $value ) {
				//查找更新之前清单的维修费用
				$oldquoteitem = $quoteitemDao->get_d ( $value ['id'] );
				$changeCost = $value ['cost'] - $oldquoteitem ['cost'];
				
				//查找清单对应的申请单
				$repairObj = $repairapplyDao->get_d ( $oldquoteitem ['mainId'] );
				$repairObj ['subCost'] = $repairObj ['subCost'] + $changeCost;
				$repairapplyDao->updateById ( $repairObj );
				if (isset ( $value ['isDelTag'] )) { //删掉 不提交报价的
					$value ['quoteId'] = null;
					$quoteitemDao->updateById ( $value );
				} else {
					$quoteitemDao->updateById ( $value );
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
	 * 通过id获取详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		$quoteitemDao = new model_service_repair_applyitem ();
		$quoteitemDao->searchArr ['quoteId'] = $id;
		$object ['items'] = $quoteitemDao->listBySqlId ();
		return $object;
	}
	
	/**
	 * 批量删除对象
	 */
	function deletes_d($ids) {
		try {
			$this->deletes ( $ids );
			//把清单信息的报价单id置空
			$quoteitemDao = new model_service_repair_applyitem ();
			$quoteitemDao->query ( "update oa_service_repair_applyitem set quoteId=null where quoteId in($ids)" );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
	
	/**
	 * 获取维修申请信息
	 */
	function getRepairItems_d($id) {
		$repairapplyDao = new model_service_repair_repairapply ();
		$repairapply = $repairapplyDao->get_d ( $id );
		return $repairapply;
	}
	
	/**
	 * 
	 * 获取报价审批清单最大费用
	 */
	function getItemMaxMoney($id) {
		$sql="select max(cost) as maxCost from oa_service_repair_applyitem where quoteId='$id'";
		$result= $this->findSql($sql);//
		return $result[0]['maxCost'];
	}
}
?>