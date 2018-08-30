<?php
/**
 * @author Administrator
 * @Date 2011年5月31日 14:51:11
 * @version 1.0
 * @description:销售退货设备表 Model层
 */
class model_projectmanagent_return_returnequ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_return_equ";
		$this->sql_map = "projectmanagent/return/returnequSql.php";
		parent :: __construct();
	}

	/**
	 * 渲染查看页面内从表
	 */
	function initTableView($object) {

		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			$i++;
			$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNo]</td>
						<td>$val[productName]</td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td>$val[unitName]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
					</tr>
EOT;
		}

		return $str;
	}

	/**
	 * 渲染编辑页面从表
	 */
	function initTableEdit($object) {

		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			$str .=<<<EOT
					<tr><td width="5%">$i</td>
						<td><input type="text" name="return[equipment][$i][productNo]" id="productNo$i" class="txtshort" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="return[equipment][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="return[equipment][$i][productName]" id="productName$i" class="txt" readonly="readonly" value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="查看配置信息"/></td>
			            <td><input type="text" name="return[equipment][$i][productModel]"  id="productModel$i" class="readOnlyTxtItem" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="return[equipment][$i][number]" id="number$i" class="txtshort" value="$val[number]" ondblclick="chooseSerialNo($i)"/>
			                <input type="hidden" name="return[equipment][$i][serialnoName]" id="serialnoName$i" value="$val[serialnoName]" />
			                <input type="hidden" name="return[equipment][$i][serialnoId]" id="serialnoId$i"  value="$val[serialnoId]" /></td>
			            <td><input type="text" name="return[equipment][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]" /></td>
			            <td><input type="text" name="return[equipment][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="return[equipment][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="删除行"/></td>
					</tr>
EOT;
		}
		return array ($i,$str);
	}

/**
 * 合同退货从表
 */
 function orderReturnEqu($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			//产品线数据字典
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			$str .=<<<EOT
					<tr><td width="5%">$i</td>
						<td><input type="text" name="return[equipment][$i][productNo]" id="productNo$i" class="txtshort" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="return[equipment][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="return[equipment][$i][productName]" id="productName$i" class="txt" readonly="readonly" value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="查看配置信息"/></td>
			            <td><input type="text" name="return[equipment][$i][productModel]"  id="productModel$i" class="readOnlyTxtItem" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="return[equipment][$i][number]" id="number$i" class="txtshort" value="$val[number]" ondblclick="chooseSerialNo($i)"/>
			                <input type="hidden" name="return[equipment][$i][serialnoName]" id="serialnoName$i" value="$val[serialnoName]" />
			                <input type="hidden" name="return[equipment][$i][serialnoId]" id="serialnoId$i"  value="$val[serialnoId]" /></td>
			            <td>$val[executedNum]</td>
			            <td><input type="text" name="return[equipment][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]" /></td>
			            <td><input type="text" name="return[equipment][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="return[equipment][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="删除行"/></td>
					</tr>
EOT;
		}
		return array ($i,$str);
	}

	/**
	 * 销售退货入库 产品模板
	 * @param  $rows
	 */
	function showProAtEdit($rows) {
		$str = "";
		if ($rows) {
            $productinfoDao = new model_stock_productinfo_productinfo();
			$i = 0;
			foreach ( $rows as $key => $val ) {
				$sNum = $i + 1;
                $proType="";
                $typeRow=$productinfoDao->getParentType($val['productId']);
                if(!empty($typeRow)){
                    $proType=$typeRow['proType'];
                }
				$exeNum = $val['qPassNum'] - $val['qPassExeNum'];
				$str .= <<<EOT
				<tr align="center">
					<td>
			          <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
			      </td>
                  <td>
                   $sNum
                  </td>
                   <td>
                     <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productCode]" readonly/>
					  <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]" readonly/>
                    </td>
					<td>
					    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
					</td>
					<td>
					  <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="$val[productName]" readonly/>
					</td>
    				<td>
    				   <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="$val[productModel]" readonly/>
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]" readonly/>
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtItem" value="$exeNum" readonly/>
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"  onchange="subTotalPrice1(this)"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort"   />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i"   />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i"   />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" value=""  />
					</td>
					<td>
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
						 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value="{$val[serialnoId]}"/>
						 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="$val[serialnoName]" />
					</td>
					<td>
					******
						<input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoney" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[price]"  />
					</td>
                     <td>
					******
                        <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="$val[money]" readonly/>
					</td>
                     <td>
					******
						<input type="hidden" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')"   />
					</td>
    				<td>
					******
    					<input type="hidden" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtItem formatMoney" readonly/>
					</td>

EOT;
				$i ++;
			}
		}
		return $str;
	}


	/**
	 * 其他入库时从表显示模板
	 * @param  $rows   收料物料信息数组
	 *
	 */
	function showAddList($rows){
		$str="";
		$i=0;
		if($rows){
			foreach($rows as $key=>$val){
				$sNum=$i+1;
				$storageNum=$val['qBackNum']-$val['qBackExeNum'];
				$str.=<<<EOT
				    <tr align="center">
							<td>
		                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
		                    </td>
                            <td>
                                $sNum
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productCode]" readonly="readonly"/>
                                <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="$val[productId]" />
                                <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"   />
                                <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"   />
                                <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i"  />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" readonly="readonly"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem"  value="$val[productModel]" readonly/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort"  value="$val[unitName]" readonly/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][batchNo]"  id="batchNo$i" class="txtshort"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort"  value="$storageNum" readonly="readonly"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="txtshort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')"  value="$storageNum" />
								<input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"/>
								<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort"  value="$val[stockName]" />
                                <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i"  value="$val[stockId]" />
                                <input type="hidden" name="stockin[items][$i][inStockCode]" id="inStockCode$i"  value="$val[stockCode]" />
                                <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i" value="$val[id]"/>
                                <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  />
                                <input type="hidden" name="stockin[items][$i][relDocCode]" id="relCodeCode$i"  value="$val[arrivalCode]" />
                            </td>
                            <td>
                            ******
                                <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="readOnlyTxtShort formatMoneySix" readonly="readonly" onblur="FloatMul('price$i','actNum$i','subPrice$i')" value="0"/>
                            </td>
                            <td>
                            ******
                                <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" readonly="readonly" value="0" />
                            </td>
                        </tr>
EOT;
				$i++;
			}
		}
		return $str;
	}

	/*******************************************页面显示层*****************************************/

	/**
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($returnId) {
		$this->searchArr['returnId'] = $returnId;
		$this->searchArr['shouldOutNum'] = 0;//只显示【应发数量】大于0的产品数据
		return $this->list_d();
	}
	/**
	 * 根据项目id获取产品列表-退货红字入库单专用
	 */
	function getDetailbyWait_d($returnId) {
		$this->searchArr['returnId'] = $returnId;
		$this->searchArr['qPassNum'] = 0;//过滤质检合同数量大于0的数据
		$this->searchArr['shouldOutNum'] = 0;//只显示【应发数量】大于0的产品数据
		return $this->list_d();
	}
	/**
	 * 根据项目id获取产品列表-退货其他入库
	 */
	function getDetailbyOther_d($returnId) {
		$this->searchArr['returnId'] = $returnId;
		$this->searchArr['qBackNum'] = 0;//过滤质检合同数量大于0的数据
		$this->searchArr['shouldOutOtherNum'] = 0;//只显示【应发数量】大于0的产品数据
		return $this->list_d();
	}


	/*******************************************************************************************/



	/**
	 * 更新物料的质检数量.
	 * @param  $arrivalId 收料通知单ID
	 * @param  $productId 物料Id
	 * @param  $proNum   质检数量
	 */
	function editQualityInfo($arrivalId,$equId,$proNum) {
    	$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum+$proNum) where c.id=$equId";
    	$this->query($sql);
	}

    /**
     * 更新物料的收料数量 - 用于质检退回
     * @param  $arrivalId   收料通知单ID
     * @param  $productId   物料Id
     * @param  $proNum   质检数量
     */
    function editQualityBackInfo($arrivalId,$equId,$passNum,$receiveNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $passNum),c.qBackNum=(c.qBackNum+$receiveNum) where c.id=$equId";
        $this->query($sql);
    }

    /**
     * 更新物料的质检数量. - 用于质检让步接收
     * @param  $arrivalId   收料通知单ID
     * @param  $productId   物料Id
     * @param  $proNum   质检数量
     */
    function editQualityReceiceInfo($arrivalId,$equId,$proNum,$backNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $proNum),c.qBackNum=(c.qBackNum+$backNum) where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * 更新物料的质检数量. - 用于质检报告撤销
     * @param  $arrivalId   收料通知单ID
     * @param  $productId   物料Id
     * @param  $proNum   质检数量
     */
    function editQualityUnconfirmInfo($arrivalId,$equId,$proNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum-$proNum) where c.id=$equId";
        $this->query($sql);
    }

    /**
     * * 更新物料的质检数量. - 用于质检申请打回
     * @param  $mainId	收料通知单ID
     * @param  $equId	明细Id
     * @param  $proNum	质检数量
     */
    function editQualityInfoAtBack($mainId, $equId, $proNum) {
    	$sql = "update $this->tbl_name c set c.qualityNum=(c.qualityNum-$proNum) where c.id=$equId";
    	$this->query($sql);
    }


	/**
	 * 质检申请打回后对应的业务操作
	 */
	function updateBusinessByBack($id) {
		$proNumSql = "SELECT
		sum(op.qualityNum) AS qualityNum
		FROM
		oa_contract_return_equ op
		WHERE
		op.returnId = $id";
		$proNum = $this->_db->getArray($proNumSql);
		if ($proNum[0]['qualityNum'] == '0') {
			$disposeState = '0';
		} else {
			$disposeState = '1';
		}
		if (isset($disposeState)) {
			return $this->update(array('id' => $id), array('disposeState' => $disposeState));
		} else {
			return true;
		}
	}


	/**
	 * 更新物料的入库数量.
	 * @param  $arrivalId   收料通知单ID
	 * @param  $productId   物料Id
	 * @param  $proNum   入库数量
	 */
	function updateNumb_d($mainId,$id,$proNum) {
		$sql = "update $this->tbl_name c set c.executedNum=(c.executedNum+$proNum),c.qBackExeNum=(c.qBackExeNum+$proNum) where  c.id=$id";
		$this->query($sql);
	}
}
?>