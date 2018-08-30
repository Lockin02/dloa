<?php
/**
 * @author Administrator
 * @Date 2011��5��31�� 14:51:11
 * @version 1.0
 * @description:�����˻��豸�� Model��
 */
class model_projectmanagent_return_returnequ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_return_equ";
		$this->sql_map = "projectmanagent/return/returnequSql.php";
		parent :: __construct();
	}

	/**
	 * ��Ⱦ�鿴ҳ���ڴӱ�
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
	 * ��Ⱦ�༭ҳ��ӱ�
	 */
	function initTableEdit($object) {

		$str = "";
		$i = 0;

		foreach ($object as $key => $val) {
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			$str .=<<<EOT
					<tr><td width="5%">$i</td>
						<td><input type="text" name="return[equipment][$i][productNo]" id="productNo$i" class="txtshort" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="return[equipment][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="return[equipment][$i][productName]" id="productName$i" class="txt" readonly="readonly" value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="return[equipment][$i][productModel]"  id="productModel$i" class="readOnlyTxtItem" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="return[equipment][$i][number]" id="number$i" class="txtshort" value="$val[number]" ondblclick="chooseSerialNo($i)"/>
			                <input type="hidden" name="return[equipment][$i][serialnoName]" id="serialnoName$i" value="$val[serialnoName]" />
			                <input type="hidden" name="return[equipment][$i][serialnoId]" id="serialnoId$i"  value="$val[serialnoId]" /></td>
			            <td><input type="text" name="return[equipment][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]" /></td>
			            <td><input type="text" name="return[equipment][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="return[equipment][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="ɾ����"/></td>
					</tr>
EOT;
		}
		return array ($i,$str);
	}

/**
 * ��ͬ�˻��ӱ�
 */
 function orderReturnEqu($object) {
		$str = "";
		$i = 0;
		foreach ($object as $key => $val) {
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts("CPX");
			$i++;
			$str .=<<<EOT
					<tr><td width="5%">$i</td>
						<td><input type="text" name="return[equipment][$i][productNo]" id="productNo$i" class="txtshort" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="return[equipment][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="return[equipment][$i][productName]" id="productName$i" class="txt" readonly="readonly" value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="return[equipment][$i][productModel]"  id="productModel$i" class="readOnlyTxtItem" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="return[equipment][$i][number]" id="number$i" class="txtshort" value="$val[number]" ondblclick="chooseSerialNo($i)"/>
			                <input type="hidden" name="return[equipment][$i][serialnoName]" id="serialnoName$i" value="$val[serialnoName]" />
			                <input type="hidden" name="return[equipment][$i][serialnoId]" id="serialnoId$i"  value="$val[serialnoId]" /></td>
			            <td>$val[executedNum]</td>
			            <td><input type="text" name="return[equipment][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]" /></td>
			            <td><input type="text" name="return[equipment][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="return[equipment][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="ɾ����"/></td>
					</tr>
EOT;
		}
		return array ($i,$str);
	}

	/**
	 * �����˻���� ��Ʒģ��
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
			          <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="ѡ�����к�">
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
	 * �������ʱ�ӱ���ʾģ��
	 * @param  $rows   ����������Ϣ����
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
		                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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

	/*******************************************ҳ����ʾ��*****************************************/

	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($returnId) {
		$this->searchArr['returnId'] = $returnId;
		$this->searchArr['shouldOutNum'] = 0;//ֻ��ʾ��Ӧ������������0�Ĳ�Ʒ����
		return $this->list_d();
	}
	/**
	 * ������Ŀid��ȡ��Ʒ�б�-�˻�������ⵥר��
	 */
	function getDetailbyWait_d($returnId) {
		$this->searchArr['returnId'] = $returnId;
		$this->searchArr['qPassNum'] = 0;//�����ʼ��ͬ��������0������
		$this->searchArr['shouldOutNum'] = 0;//ֻ��ʾ��Ӧ������������0�Ĳ�Ʒ����
		return $this->list_d();
	}
	/**
	 * ������Ŀid��ȡ��Ʒ�б�-�˻��������
	 */
	function getDetailbyOther_d($returnId) {
		$this->searchArr['returnId'] = $returnId;
		$this->searchArr['qBackNum'] = 0;//�����ʼ��ͬ��������0������
		$this->searchArr['shouldOutOtherNum'] = 0;//ֻ��ʾ��Ӧ������������0�Ĳ�Ʒ����
		return $this->list_d();
	}


	/*******************************************************************************************/



	/**
	 * �������ϵ��ʼ�����.
	 * @param  $arrivalId ����֪ͨ��ID
	 * @param  $productId ����Id
	 * @param  $proNum   �ʼ�����
	 */
	function editQualityInfo($arrivalId,$equId,$proNum) {
    	$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum+$proNum) where c.id=$equId";
    	$this->query($sql);
	}

    /**
     * �������ϵ��������� - �����ʼ��˻�
     * @param  $arrivalId   ����֪ͨ��ID
     * @param  $productId   ����Id
     * @param  $proNum   �ʼ�����
     */
    function editQualityBackInfo($arrivalId,$equId,$passNum,$receiveNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $passNum),c.qBackNum=(c.qBackNum+$receiveNum) where c.id=$equId";
        $this->query($sql);
    }

    /**
     * �������ϵ��ʼ�����. - �����ʼ��ò�����
     * @param  $arrivalId   ����֪ͨ��ID
     * @param  $productId   ����Id
     * @param  $proNum   �ʼ�����
     */
    function editQualityReceiceInfo($arrivalId,$equId,$proNum,$backNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $proNum),c.qBackNum=(c.qBackNum+$backNum) where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * �������ϵ��ʼ�����. - �����ʼ챨�泷��
     * @param  $arrivalId   ����֪ͨ��ID
     * @param  $productId   ����Id
     * @param  $proNum   �ʼ�����
     */
    function editQualityUnconfirmInfo($arrivalId,$equId,$proNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum-$proNum) where c.id=$equId";
        $this->query($sql);
    }

    /**
     * * �������ϵ��ʼ�����. - �����ʼ�������
     * @param  $mainId	����֪ͨ��ID
     * @param  $equId	��ϸId
     * @param  $proNum	�ʼ�����
     */
    function editQualityInfoAtBack($mainId, $equId, $proNum) {
    	$sql = "update $this->tbl_name c set c.qualityNum=(c.qualityNum-$proNum) where c.id=$equId";
    	$this->query($sql);
    }


	/**
	 * �ʼ������غ��Ӧ��ҵ�����
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
	 * �������ϵ��������.
	 * @param  $arrivalId   ����֪ͨ��ID
	 * @param  $productId   ����Id
	 * @param  $proNum   �������
	 */
	function updateNumb_d($mainId,$id,$proNum) {
		$sql = "update $this->tbl_name c set c.executedNum=(c.executedNum+$proNum),c.qBackExeNum=(c.qBackExeNum+$proNum) where  c.id=$id";
		$this->query($sql);
	}
}
?>