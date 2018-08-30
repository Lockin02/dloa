<?php
/**
 * @author show
 * @Date 2014��09��01��
 * @version 1.0
 * @description:����ת�ʲ�������ϸ Model��
 */
 class model_asset_require_requireinitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requireinitem";
		$this->sql_map = "asset/require/requireinitemSql.php";
		parent::__construct ();
	}

	/**
	 * �������ϳ�������
	 */
	 function updateOutNum($id,$outNum){
         $sql = "update ".$this->tbl_name." set executedNum = executedNum + " . $outNum . " where id = ".$id;
		 $this->_db->query($sql);
	 }
	 
	 /**
	  * ����������������
	  */
	 function updateReceiveNum($id,$receiveNum){
	 	$sql = "update ".$this->tbl_name." set receiveNum = receiveNum + " . $receiveNum . " where id = ".$id;
	 	$this->_db->query($sql);
	 }
	 
	 /**
	  * �������ɿ�Ƭ����
	  */
	 function updateCardNum($id,$cardNum){
	 	$sql = "update ".$this->tbl_name." set cardNum = cardNum + " . $cardNum . " where id = ".$id;
	 	$this->_db->query($sql);
	 }

	 /**
	  * ����mainId�����Ƿ������ɵĿ�Ƭ����-�����������ƺ��ֳ���
	  */
 	 function isCardExist_d($mainId){
	 	$sql = "select sum(cardNum) as cardNum from ".$this->tbl_name." where mainId = ".$mainId;
	 	$rs = $this->_db->getArray($sql);
	 	if($rs[0]['cardNum'] > 0){
	 		return true;
	 	}else{
	 		return false;
	 	}
	 }
	 
	 /**
	  * ��ȡ���������ϸ����
	  */
	 function getApplyedNum_d($requireItemId){
	 	$sql = "select sum(number) as applyAmount from ".$this->tbl_name." where requireItemId = '$requireItemId' group by requireItemId";
	 	$rs = $this->_db->getArray($sql);
	 	if($rs[0]['applyAmount']){
	 		return $rs[0]['applyAmount'];
	 	}else{
	 		return 0;
	 	}
	 }
	 
	 /**
	  * �ʲ�����ʱ���������뵥id��ȡ��Ʒ�б�
	  */
	 function getOutStockDetail_d($requireId) {
	 	$this->searchArr['mainId'] = $requireId;
	 	//ֻ��ʾ��Ӧ������������0�Ĳ�Ʒ����
	 	$sqlStr = "sql: and (c.number-c.executedNum)>0";
	 	$this->searchArr['numCondition'] = $sqlStr;
	 	return $this->list_d();
	 }
	 
	 /**
	  * ��д���յ�ʱ���������뵥id��ȡ��Ʒ�б�
	  */
	 function getReceiveDetail_d($requireId) {
	 	$this->searchArr['mainId'] = $requireId;
	 	//ֻ��ʾ������������������0�Ĳ�Ʒ����
	 	$sqlStr = "sql: and (c.executedNum-c.receiveNum)>0";
	 	$this->searchArr['numCondition'] = $sqlStr;
	 	return $this->list_d();
	 }
	 /*******************************************ҳ����ʾ��*****************************************/	 
	 /**
	  * �ʲ�����-��Ʒ�б�
	  */
	 function showProAtEdit($rows) {
	 	$str = "";
	 	if ($rows) {
	 		$i = 0;
	 		foreach ( $rows as $key => $val ) {
	 			$sNum = $i + 1;
	 			$subCost = $val[productPrice] * $val[shouldOutNum];
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
					  <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="$val[productName]" readonly/>
					</td>
    				<td>
    				   <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="$val[spec]" readonly/>
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]" readonly/>
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtItem" value="$val[shouldOutNum]" readonly/>
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" value="$val[shouldOutNum]" onfocus="exploreProTipInfo('$i')" ondblclick="chooseSerialNo($i)"  onchange="subTotalPrice1(this)" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')"/>
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
						<input type="text" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoney" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[productPrice]"  />
					</td>
                     <td>
                        <input type="text" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="$subCost" readonly/>
					</td>
                     <td>
						<input type="text" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')"   />
					</td>
    				<td>
    					<input type="text" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtItem formatMoney" readonly/>
					</td>
	 
EOT;
	 			$i ++;
	 		}
	 	}
	 	return $str;
	 }
 }