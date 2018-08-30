<?php

/**
 * @author huangzf
 * @Date 2011年5月11日 16:54:05
 * @version 1.0
 * @description:入库单物料清单 Model层
 */
class model_stock_instock_stockinitem extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_stock_instock_item";
		$this->sql_map = "stock/instock/stockinitemSql.php";
		parent::__construct();
	}

	/**
	 * @param $rows
	 * @param $showpage
	 * @return string
	 */
	function showlist($rows, $showpage) {
		$i = 0;
		if ($rows) {
			$str = '';
			foreach ($rows as $key => $rs) {
				$i++;
				$str .= <<<EOT
					<tr>
						<td height="25" align="center"> $i </td>
						<td align="center" >$rs[docCode] </td>
						<td align="center" >$rs[inStockName] </td>
						<td align="center" >$rs[productCode]</td>
						<td align="center" >$rs[productName] </td>
						<td align="center" >$rs[storageNum] </td>
						<td align="center" >$rs[storageName] </td>
						<td align="center" >$rs[storageTime] </td>
					</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str . '<tr><td colspan="12" style="text-align:center;">' . $showpage->show(6) . '</td></tr>';
	}

	/**
	 * 待钩稽入库申请单模版
	 * @param $rows
	 * @return string
	 */
	function showHookInfo($rows) {
		$str = "";
		$datadictDao = new model_system_datadict_datadict();
		$productInfoDao = new model_stock_productinfo_productinfo();
		$productTypeDao = new model_stock_productinfo_producttype ();
		if ($rows) {
			foreach ($rows as $key => $storageapply) {
				$fontCorlor = "blue";
				if ($storageapply['isRed'] == "1") {
					$fontCorlor = "red";
				}

				if (isset($storageapply['products'])) {
					$storageapply['catchStatus'] = $datadictDao->getDataNameByCode($storageapply['catchStatus']);
					$storageappPros = $storageapply['products'];
					$everyProCount = count($storageappPros);
					$storageappPro = $storageappPros[0];
					$jsonApply = json_encode(util_jsonUtil::iconvGB2UTFArr($storageapply));

					/*s:----------------把物料类型完整展示出来----------------*/

					$productInfoObj = $productInfoDao->get_d($storageappPro['productId']);
					$proTypeObj = $productTypeDao->get_d($productInfoObj ['proTypeId']);
					$productTypeDao->searchArr = array("xlft" => $proTypeObj ['lft'], "drgt" => $proTypeObj ['rgt']);
					$productTypeDao->sort = "c.lft";
					$productTypeDao->asc = false;
					$proTypeParent = $productTypeDao->list_d();
					$allProType = "";

					foreach ($proTypeParent as $key => $val) {
						$allProType .= $val ['proType'] . " / ";
					}
					$allProType .= $productInfoObj ['proType'];
					/*e:----------------把物料类型完整展示出来----------------*/

					$str .= <<<EOT
						<tr>
						<td height="25" align="center" rowspan="$everyProCount"><input type="hidden" id="inproductdate$storageapply[id]" name="inproductdata" value='$jsonApply'  > <input type="checkbox" name="datacb" id="$storageapply[id]" onclick="checkThis($storageapply[id])"></td>
						<td align="center"  rowspan="$everyProCount" ><font color="$fontCorlor"><b>$storageapply[docCode]</b></font></td>
						<td align="center"  rowspan="$everyProCount">$storageapply[inStockName]</td>
						<td align="center"  rowspan="$everyProCount">$storageapply[catchStatus]</td>
						<td align="center"  rowspan="$everyProCount">$storageapply[auditDate]</td>
						<td align="center"  rowspan="$everyProCount">$storageapply[purOrderCode]</td>
						<td align="center" >$storageappPro[productName] </td>
						<td align="center" >$storageappPro[productCode]</td>
						<td align="center" >$allProType</td>
						<td align="center" >$storageappPro[actNum]</td>
						<td align="center" >$storageappPro[unHookNumber]</td>
						<td align="center" class="formatMoneySix" >$storageappPro[price]</td>
						<td align="center" class="formatMoney">$storageappPro[subPrice]</td>
						</tr>
EOT;
					for ($m = 1; $m < $everyProCount; $m++) {
						$storageappPro = $storageappPros[$m];
						$str .= <<<EOT
						<tr>
						<td align="center" >$storageappPro[productName] </td>
						<td align="center" >$storageappPro[productCode]</td>
						<td align="center" >$allProType</td>
						<td align="center" >$storageappPro[actNum]</td>
						<td align="center" >$storageappPro[unHookNumber]</td>
						<td align="center" class="formatMoneySix" >$storageappPro[price]</td>
						<td align="center" class="formatMoney" >$storageappPro[subPrice]</td>
						</tr>
EOT;
					}
				} else {//没有产品信息
					$str .= <<<EOT
						<tr>
						<td height="25" align="center"><input type="hidden" name="inproductdata" value=""  > <input type="checkbox" name="datacb" id=""></td>
						<td align="center"  >$storageapply[docCode]</td>
						<td align="center" colspan="4" >--没有产品信息-- </td>
						</tr>
EOT;
				}
			}
		} else {//没有对应供应商入库申请单
			$str = "<tr><td colspan=8>--没有对应供应商入库申请单--</td></tr>";
		}
		return $str;
	}


	/**
	 * 外购入库单下推采购发票时,清单模板
	 */
	function showItemAtInp($itemArr, $condition) {
		$str = "";
		if ($itemArr) {
			$invnumber = isset($condition['invnumber']) ? $condition['invnumber'] : 0;
			$pronumber = isset($condition['pronumber']) ? $condition['pronumber'] : 0;
			$objId = isset($condition['objId']) ? $condition['objId'] : 0;
			$objCode = isset($condition['objCode']) ? $condition['objCode'] : 0;
			$objType = isset($condition['objType']) ? $condition['objType'] : null;
			$stockinDao = new model_stock_instock_stockin();
			$stockinObj = $stockinDao->find(array("id" => $itemArr[0]['mainId']));
			foreach ($itemArr as $key => $val) {
				$pronumber++;
				$invnumber++;
				$str .= <<<EOT
                    <tr class="$objType">
                        <td>
                            <img src="images/removeline.png" onclick="mydel(this,'invbody')" title="删除行">
                        </td><td>
                            $pronumber
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][productNo]" id="productNo$invnumber" value="$val[productCode]" class="txtmiddle"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objId]"id="objId$invnumber" value="$objId" />
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objCode]" id="objCode$invnumber"   value="$objCode" />
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objType]" value="$objType"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][productName]" id="productName$invnumber" value="$val[productName]" class="txt"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][productId]" id="productId$invnumber" value="$val[productId]"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][productModel]" id="productModel$invnumber" value="$val[pattem]" class="readOnlyTxtItem" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][unit]" id="unit$invnumber" value="$val[unitName]" class="readOnlyTxtShort" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][number]" id="number$invnumber" value="$val[actNum]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][price]" id="price$invnumber" value="$val[price]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][taxPrice]" id="taxPrice$invnumber" value="$val[price]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][amount]" id="amount$invnumber" value="$val[subPrice]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][assessment]" id="assessment$invnumber" value="0" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][allCount]" id="allCount$invnumber" value="$val[subPrice]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][objCode]" id="objCode$invnumber" value="$objCode" class="readOnlyTxtNormal" readonly="readonly"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objId]"id="objId$invnumber" value="$objId" />
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objType]" value="$objType"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][contractCode]" id="contractCode$invnumber" value="$stockinObj[purOrderCode]" class="readOnlyTxtNormal" readonly="readonly"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][contractId]"id="contractId$invnumber" value="$stockinObj[purOrderId]" />
                        </td>
                    </tr>
EOT;
			}
		}
		return $str;
	}

	/*------------------------------------------------业务处理--------------------------------------------------------*/

	/**
	 * 根据根据id,钩稽状态，单据类型获取入库单据
	 * @param  $supplierId
	 * @param  $thisStatus
	 */
	function getInproBySupplier($searchCon, $thisStatus = 'CGFPZT-WGJ,CGFPZT-BFGJ', $docType = false) {
		$instockDao = new model_stock_instock_stockin();
		$searchArr = $searchCon;
		$searchArr['catchStatusIn'] = $thisStatus;
		$searchArr['docStatus'] = 'YSH';
		$instockDao->searchArr = $searchArr;
		if ($docType) {
			$instockDao->searchArr['docType'] = $docType;
		}
		$instockArr = $instockDao->listBySqlId();
		$result = array();
		foreach ($instockArr as $key => $instockObj) {
			$this->searchArr['mainId'] = $instockObj['id'];
			$instockObj['products'] = $this->listBySqlId('select_default');
			array_push($result, $instockObj);
		}
		return $result;
	}

	/**
	 * @desription 根据入库单Id判断入库单中的产品是否全部勾稽 全部返回0 否则返回未勾稽数量
	 * @param tags
	 * @date 2011-1-3 下午03:15:47
	 * @qiaolong
	 */
	function hookNumJudge($id) {
		$this->searchArr['mainId'] = $id;
		$storagepdInfo = $this->listBySqlId('select_default');
		$unhookNumber = 0;
		foreach ($storagepdInfo as $key => $val) {
			$unhookNumber = bcadd($unhookNumber, $val['unHookNumber'], 2);
		}
		return $unhookNumber;
	}

	/**
	 * 处理条目中的数值
	 * @param $object
	 * @return mixed
	 */
	function hookDeal_d($object) {
		$sql = "update " . $this->tbl_name . " set unHookNumber = unHookNumber - " . $object['number'] .
			", unHookAmount = unHookAmount - " . $object['amount'] . ", hookNumber = hookNumber + " .
			$object['number'] . ", hookAmount = hookAmount + " . $object['amount'] . " where id = " . $object['hookId'];
		return $this->query($sql);
	}

	/**
	 * 反钩稽处理条目中的数值
	 * @param $object
	 * @return mixed
	 */
	function unhookDeal_d($object) {
		$sql = "update " . $this->tbl_name . " set unHookNumber = unHookNumber + " . $object['number'] .
			", unHookAmount = unHookAmount + " . $object['amount'] . ", hookNumber = hookNumber - " .
			$object['number'] . ", hookAmount = hookAmount - " . $object['amount'] .
			" where id = " . $object['hookId'];
		return $this->query($sql);
	}

	/**
	 * 根据基本基本信息ID获取清单信息
	 * @author huangzf
	 */
	function getItemByMainId($mainId) {
		$this->searchArr['mainId'] = $mainId;
		return $this->listBySqlId();
	}
}