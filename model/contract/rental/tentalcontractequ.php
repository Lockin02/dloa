<?php
/**
 * @author Administrator
 * @Date 2011��5��5�� 14:44:17
 * @version 1.0
 * @description:����Ʒ�嵥 Model��
 */
 class model_contract_rental_tentalcontractequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_lease_equ";
		$this->sql_map = "contract/rental/tentalcontractequSql.php";
		parent::__construct ();
	}
/**
     * ���ֳ��� ���ۺ�ͬ�ӱ���Ⱦ
     */
     function showItemAtLease($orderId) {
     	$rows = $this->getDetail_d($orderId);
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
                if($val['number'] - $val['executedNum'] > 0){
                 $sNum = $i + 1;
				$deexecutedNum = $val['number'] - $val['executedNum'];
				$str .= <<<EOT
				<tr align="center">
					<td>
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
					</td>
                   <td>
                   		$sNum
                   </td>
                   <td>
                      <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productNo]"  />
					  <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                    </td>
					<td>
					  <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="$val[productName]"  />
					</td>
    				<td>
    				   <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="$val[productModel]"  />
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]"  />
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtItem" value="$deexecutedNum"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"
							onblur="javascript:FloatMul('actOutNum$i','salecost$i','saleSubCost$i');FloatMul('actOutNum$i','cost$i','subCost$i');"  value="$deexecutedNum"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value=""  />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value=""  />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" value=""  />
					</td>
					<td>
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="ѡ�����к�">
						 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
						 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="" />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoney" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="0"  />
					</td>
                     <td>
                        <input type="text" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="0"  />
					</td>
                     <td>
						<input type="text" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')" value="$val[price]"  />
					</td>
    				<td>
    					<input type="text" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtItem formatMoney" value="$val[money]"  />
					</td>
			</tr>
EOT;
				$i ++;
			}
		}
		return $str;
	}
	}

	/**
	 * ���Ψһ��־
	 */
	function updateUniqueCode_d($orderId){
		$thisUnique = 'rentalcontractequ-';
		$sql = 'update ' . $this->tbl_name . " set uniqueCode = concat('" . $thisUnique . "' , id) where orderId = " . $orderId;
		$this->query($sql);
	}


	/**
	 * ��Ⱦ�鿴ҳ���ڴӱ�
	 */
	function initTableView($object,$objId){
		 //��ȡ���һ�α����������ϸ��¼
			   $dao = new model_common_changeLog();
			   $changeInfo = $dao->getLastDetails("rentalcontract",$objId);

               $detailId =array();
			   foreach($changeInfo as $k => $v){
			   	  if(!empty($v['detailId'])){
                      $detailId[$v['detailId']] = $k;
			   	  }
			   }
			   if(!empty($detailId)){
                 foreach($detailId as $k => $v){
	                $sql = "select * from oa_lease_equ where id = '".$k."' ";
	              	$chIn = $this->_db->getArray($sql);
                    foreach($chIn as $k => $v){
                       if($v['isDel'] == '1'){
                            $object = array_merge($object,$chIn);
                       }
                    }
			    }
			   }
		$str = "";
		$equipDatadictDao = new model_system_datadict_datadict ();
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
               if(empty($val['license'] )){
               		$license = "";
               }else{
               		$license = "<input type='button' class='txt_btn_a' value='����' onclick='" .
               				"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=".$val['license']."" .
               						"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
               }
               if(!empty($val['isSell'])){
					$checked = '��';
				}else{
					$checked = '��';
				}
				$produceInfoDao = new model_stock_productinfo_productinfo();
				 if(!empty($val[productNo])){
			 	$warranty = $produceInfoDao->compareWarranty($val[productNo],$val[warrantyPeriod]);
			 	  if( $val[warrantyPeriod] - $warranty > 0){
						$warrantyPeriod = "<span class='red'>$val[warrantyPeriod]</span>&nbsp<img src='images/cx.gif' onclick='warranty($warranty);' title='�鿴������Ϣ'/>";
					}else{
						$warrantyPeriod = "$val[warrantyPeriod]";
					}
				 }else{
	                  $warrantyPeriod = $val[warrantyPeriod];
				 }
		      //�ж������Ƿ��ڱ��������
              $Osql = "select isBecome from oa_sale_lease where id= '".$val['orderId']."' ";
              $isBecome = $this->_db->getArray($Osql);
              foreach($isBecome as $k => $v){
              	 $isBecome = $v['isBecome'];
              }
            if($val['changeTips'] != '0'){
				if($val['isDel'] == '1'){
					$str .=<<<EOT
					<tr style="background:#C8C8C8">
						<td width="5%">$i</td>
						<td>$val[productNo]</td>
						<td><span class="red" onclick="beColor('lease',$val[id],$val[orderId]);">$val[productName]</span>&nbsp<img src="images/icon/icon105.gif" onclick="conInfo($val[productId],$val[orderId]);" title="�鿴������Ϣ"/></td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="fromatMoney">$val[money]</td>
						<td>$warrantyPeriod</td>
						<td>$val[proBeginTime]</td>
						<td>$val[proEndTime]</td>
						<td>$license</td>
                        <td>$checked</td>
					</tr>
EOT;
				}else{
					$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNo]</td>
						<td><span class="red" onclick="beColor('lease',$val[id],$val[orderId]);">$val[productName]</span>&nbsp<img src="images/icon/icon105.gif" onclick="conInfo($val[productId],$val[orderId]);" title="�鿴������Ϣ"/></td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="fromatMoney">$val[money]</td>
						<td>$warrantyPeriod</td>
						<td>$val[proBeginTime]</td>
						<td>$val[proEndTime]</td>
						<td>$license</td>
                        <td>$checked</td>
					</tr>
EOT;
				}
            }else{
    				$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNo]</td>
						<td>$val[productName]&nbsp<img src="images/icon/icon105.gif" onclick="conInfo($val[productId],$val[orderId]);" title="�鿴������Ϣ"/></td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="fromatMoney">$val[money]</td>
						<td>$warrantyPeriod</td>
						<td>$val[proBeginTime]</td>
						<td>$val[proEndTime]</td>
						<td>$license</td>
                        <td>$checked</td>
					</tr>
EOT;
            }

		}

		return $str;
	}


/**
 * �������� ��Ⱦ
 */
function configTable($object,$Num) {
		$str = "";
		$i = $Num;

		foreach ( $object as $key => $val ) {
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts ( "CPX" );
			$i ++;
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i
						</td>
						<td><input type="text" class="readOnlyTxtShort" readonly="readonly"  name="rentalcontract[rentalcontractequ][$i][productNo]" id="productNo$i"  value="$val[productCode]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i" value="$val[unitName]">
			            </td>
			            <td><input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[id]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="productName$i" class="readOnlyTxtMiddle" readonly="readonly"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="�鿴������Ϣ"/>
			            </td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]"  id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[pattern]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[configNum]" onblur="FloatMul('number$i','price$i','money$i')"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[money]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warranty]" /></td>
				        <td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
				        <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
 			                <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
 			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="$val[isConfig]" value="$val[isCon]">
 			            </td>
				        <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" checked="checked"/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/> </td>
					</tr>
EOT;
		}
		             return array ($str, $i );
	}


/**
 * ���������޸� ���� ��Ⱦ
 */
function configTableEdit($object,$Num) {
		$str = "";
		$i = $Num;

		foreach ( $object as $key => $val ) {
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts ( "CPX" );
			$i ++;
			$str .= <<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]" ><td width="5%">$i
						</td>
						<td><input type="text" class="readOnlyTxtShort" readonly="readonly"  name="rentalcontract[rentalcontractequ][$i][productNo]" id="productNo$i"  value="$val[productCode]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i" value="$val[unitName]">
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isAdd]" id="isAdd$i" value="1" />
			            </td>
			            <td><input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[id]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="productName$i" class="readOnlyTxtMiddle" readonly="readonly"  value="$val[productName]"/>
			                &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="�鿴������Ϣ"/>
			            </td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]"  id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[pattern]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[configNum]" onblur="FloatMul('number$i','price$i','money$i')"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[money]"/></td>
			        	<td nowrap width="8%"><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warranty]" /></td>
				        <td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
				        <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
 			                <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
 			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="$val[isConfig]" value="$val[isCon]">
 			            </td>
				        <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" checked="checked"/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/> </td>
					</tr>
EOT;
		}
		             return array ($str, $i );
	}


	/**
	 * ��Ⱦ�༭ҳ��ӱ�
	 */
	function initTableEdit($object){
		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts ( "CPX" );
			if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}
			$i ++ ;
                if(!empty($val['isConfig'])){
                					$str .=<<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]"><td width="5%">$i</td>
						<td><input type="text" name="rentalcontract[rentalcontractequ][$i][productNo]" id="productNo" class="readOnlyTxtShort" value="$val[productNo]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i"></td>
			            <td><input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="productName" class="readOnlyTxtNormal"  value="$val[productName]"/>
			                 &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i',$i);" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]" id="productModel" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[number]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
	                     <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
					    <td nowrap width="8%"><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
	                    <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" />
		 			        <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][remark]" id="remark$i" value="$val[remark]"></td>
		 			    <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" $checked/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/></td>
					</tr>
EOT;
                }else{
                					$str .=<<<EOT
					<tr id="equTab_$i" ><td width="5%">$i</td>
						<td><input type="text" name="rentalcontract[rentalcontractequ][$i][productNo]" id="productNo$i" class="txtmiddle" value="$val[productNo]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i"></td>
			            <td><input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="productName$i" class="txt"  value="$val[productName]"/>
			                 &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i',$i);" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]" id="productModel$i" class="txtmiddle" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[number]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
	                     <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
					    <td nowrap width="8%"><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
	                    <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" />
		 			        <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][remark]" id="remark$i" value="$val[remark]"></td>
		 			    <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" $checked/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/></td>
					</tr>
EOT;
                }
		}
		return array($i,$str);
	}

	/**
	 * ���������޸�
	 */
	function proTableEdit($object){
		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts ( "CPX" );
			if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}
			$i ++ ;
				if(!empty($val['isConfig'])){
                					$str .=<<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]"><td width="5%">$i</td>
						<td><input type="text" name="rentalcontract[rentalcontractequ][$i][productNo]" id="productNo" class="readOnlyTxtShort" value="$val[productNo]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i"></td>
			            <td><input type="hidden" id="isAdd$i" name="rentalcontract[rentalcontractequ][$i][proId]" value="$val[id]">
			                <input type="hidden" id="isDel$i" value="$val[productId]" />
			                <input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="productName" class="readOnlyTxtNormal"  value="$val[productName]"/>
			                 &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i',$i);" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]" id="productModel" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[number]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
	                     <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
					    <td nowrap width="8%"><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
	                    <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" />
		 			        <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][remark]" id="remark$i" value="$val[remark]"></td>
		 			    <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" $checked/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/></td>
					</tr>
EOT;
                }else{
                					$str .=<<<EOT
					<tr id="equTab_$i" ><td width="5%">$i</td>
						<td><input type="text" name="rentalcontract[rentalcontractequ][$i][productNo]" id="" class="readOnlyTxtShort" value="$val[productNo]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i"></td>
			            <td><input type="hidden" id="isAdd$i" name="rentalcontract[rentalcontractequ][$i][proId]" value="$val[id]">
			                <input type="hidden" id="isDel$i" value="$val[productId]" />
			                <input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="" class="readOnlyTxtNormal"  value="$val[productName]"/>
			                 &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i',$i);" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]" id="" class="readOnlyTxtNormal" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[number]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
	                     <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
					    <td nowrap width="8%"><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
	                    <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" />
		 			        <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][remark]" id="remark$i" value="$val[remark]"></td>
		 			    <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" $checked/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/></td>
					</tr>
EOT;
                }
		}
		return array($i,$str);
	}
	/**�����̬�б�
	*author can
	*2011-6-2
	*/
	function initTableChange($object){
		$str = "";
		$i = 0;
		$equipDatadictDao = new model_system_datadict_datadict ();
		foreach($object as $key => $val ){
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts ( "CPX" );
			if (! empty ( $val ['isSell'] )) {
				$checked = 'checked="checked"';
			} else {
				$checked = null;
			}
			$i ++ ;
			if(empty($val['originalId'])){
				$str.='<input type="hidden" name="rentalcontract[rentalcontractequ]['.$i.'][oldId]" value="'.$val['id'].'" />';
			}else{
				$str.='<input type="hidden" name="rentalcontract[rentalcontractequ]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
			}
			if(!empty($val['isConfig'])){
				$str .=<<<EOT
					<tr><td width="5%">$i</td>
						<td><input type="text" name="rentalcontract[rentalcontractequ][$i][productNo]" id="" class="readOnlyTxtShort" value="$val[productNo]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i"></td>
			            <td><input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="" class="readOnlyTxtShort" readonly="readonly" value="$val[productName]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]" id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[number]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
				        <td nowrap width="8%"> <input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod] "/></td>
                        <td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
                        <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" />
		 			        <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]"></td>
				        <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" $checked/></td>
		 			    <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody','rentalcontractequ')" title="ɾ����" id="Del$i"/></td>


					</tr>
EOT;
			}else{
				$str .=<<<EOT
					<tr><td width="5%">$i</td>
						<td><input type="text" name="rentalcontract[rentalcontractequ][$i][productNo]" id="" class="readOnlyTxtMiddle" value="$val[productNo]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i"></td>
			            <td><input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="" class="readOnlyTxtMiddle" readonly="readonly" value="$val[productName]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]" id="productModel$i" class="readOnlyTxtItem" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[number]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
                        <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
				        <td nowrap width="8%"> <input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod] "/></td>
                        <td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
                        <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" />
		 			        <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]"></td>
				        <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" $checked/></td>
		 			    <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody','rentalcontractequ')" title="ɾ����" id="Del$i"/></td>


					</tr>
EOT;
			}
		}
		return array($i,$str);
	}

/**
 * �̻�ת���޺�ͬ
 */
function ChanceOrderEqu($object){
		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts ( "CPX" );
			if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = 'checked="checked"';
				}
			$i ++ ;
		if(!empty($val['isConfig'])){
                					$str .=<<<EOT
					<tr id="equTab_$i" parentRowId="$val[isCon]"><td width="5%">$i</td>
						<td><input type="text" name="rentalcontract[rentalcontractequ][$i][productNo]" id="productNo" class="readOnlyTxtShort" value="$val[productNumber]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i"></td>
			            <td><input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="productName" class="readOnlyTxtNormal"  value="$val[productName]"/>
			                 &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]" id="productModel" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[amount]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
	                     <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
					    <td nowrap width="8%"><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
	                    <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" />
		 			        <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]"></td>
		 			    <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" $checked/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/></td>
					</tr>
EOT;
                }else{
                					$str .=<<<EOT
					<tr id="equTab_$i" ><td width="5%">$i</td>
						<td><input type="text" name="rentalcontract[rentalcontractequ][$i][productNo]" id="productNo$i" class="txtmiddle" value="$val[productNumber]"/>
			                <input type="hidden" name="rentalcontract[rentalcontractequ][$i][unitName]" id="unitName$i"></td>
			            <td><input type="hidden" id="productId$i" name="rentalcontract[rentalcontractequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="rentalcontract[rentalcontractequ][$i][productName]" id="productName$i" class="txt"  value="$val[productName]"/>
			                 &nbsp<img src="images/icon/icon105.gif" onclick="conInfo('productId$i');" title="�鿴������Ϣ"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][productModel]" id="productModel$i" class="txtmiddle" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][number]" id="number$i" class="txtshort" value="$val[amount]"/></td>
			            <td><input type="text" name="rentalcontract[rentalcontractequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number$i','price$i','money$i')" value="$val[price]"/></td>
	                     <td><input type="text" name="rentalcontract[rentalcontractequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/></td>
					    <td nowrap width="8%"><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" /></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proBeginTime]" id="proBeginTime$i" value="$val[proBeginTime]" onfocus="WdatePicker()"/></td>
						<td><input type="text" class="txtshort" name="rentalcontract[rentalcontractequ][$i][proEndTime]" id="proEndTime$i" value="$val[proEndTime]" onfocus="WdatePicker()"/></td>
	                    <td><input type="hidden" id="licenseId$i" name="rentalcontract[rentalcontractequ][$i][license]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i','edit');" />
		 			        <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="rentalcontract[rentalcontractequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]"></td>
		 			    <td width="4%"><input type="checkbox" name="rentalcontract[rentalcontractequ][$i][isSell]" id="isSell$i" $checked/></td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i" /></td>
					</tr>
EOT;
                }
		};
		return array($str,$i);
	}


/**
 * ��Ⱦ���������ϴӱ�
 */
    function borrowTableEdit($object){
		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
				$str .=<<<EOT
					<tr id="equTab_$i" ><td width="5%">$i</td>
						<td><input type="text" name="borrow[borrowequ][$i][productNo]" id="productNo$i" class="readOnlyTxtShort" value="$val[productNo]"/></td>
			            <td><input type="hidden" id="productId$i" name="borrow[borrowequ][$i][productId]" value="$val[productId]"/><input type="text" name="borrow[borrowequ][$i][productName]" id="productName$i" class="readOnlyTxt"  value="$val[productName]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][productModel]" id="productModel$i" class="readOnlyTxtShort" readonly="readonly" value="$val[productModel]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][number]"  id="number$i" class="txtshort" value="$val[number]"/></td>
			            <td><input type="text" name="borrow[borrowequ][$i][unitName]" id="unitName$i" class="txtshort" value="$val[unitName]" /></td>
			            <td><input type="text" name="borrow[borrowequ][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('number1','price1','money1')" value="$val[price]"/></td>
                        <td><input type="text" name="borrow[borrowequ][$i][money]" id="money$i" class="txtshort formatMoney" value="$val[money]"/>
                        </td><td nowrap width="8%"> <input type="text" class="txtshort" name="borrow[borrowequ][$i][warrantyPeriod]" id="warrantyPeriod$i" value="$val[warrantyPeriod]" />
						</td>
                        <td><input type="hidden" id="licenseId$i" name="borrow[borrowequ][$i][License]" value="$val[license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
		 			        <input type="hidden" name="borrow[borrowequ][$i][isCon]" id="isCon$i" value="$val[isCon]">
				            <input type="hidden" name="borrow[borrowequ][$i][isConfig]" id="isConfig$i" value="$val[isConfig]">
		 			      </td>
			            <td><img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����" id="Del$i"/> </td>
					</tr>
EOT;
		}
		return array($i,$str);
	}
/*******************************ҳ����ʾ��*********************************/

	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($orderId){
		$this->searchArr['orderId'] = $orderId;
		$this->searchArr ['isDel'] = 0;
		$this->searchArr ['isBorrowToorder'] = 0;
		$this->asc = false;
		return $this->list_d();
	}

	/**
	 * ������Ŀid��ȡ��Ʒ�б�(������)
	 */
	function getShip_d($orderId) {
		$this->searchArr ['orderId'] = $orderId;
		$this->searchArr ['isDel'] = 0;
		$this->asc = false;
		return $this->list_d ();
	}

	/**
	 *
	 * �豸�б�-������
	 * ���ݶ�����Ż�ȡ�豸�б�
	 */
	function showEquListInByOrder($orderId,$docType){
		$searchArr = array(
			'orderId' => $orderId
		 );
		$equs = $this->findAll( $searchArr,false,false,false );
		//��ȡ�豸����������
		$lockDao=new model_stock_lock_lock();
		foreach($equs as $key => $val){
			$lockNum=$lockDao->getEquStockLockNum($val['id'],null,$docType);
			$equs[$key]['lockNum']=$lockNum;
		}

		return $equs;
	}

	/**
	 * ������ʱ��ʾ�豸
	 */
	function showDetailByOrder($rows){
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $key => $val) {
				$val['lockNum']=isset($val['lockNum'])?$val['lockNum']:0;
				$canUseNum = $val['exeNum'] + $val['lockNum'];
				$proId=$val['productId'];
				if($val['isDel']==1){
					$productNo="<font color=red>".$val[productNo]."</font>";
					$productName="<font color=red>".$val[productName]."</font>";
				}else{
					$productNo=$val[productNo];
					$productName=$val[productName];
				}
				$lockNum = $val['number']- $val['lockNum'];
				$equId=$val['id'];
						$str .=<<<EOT
							<tr align="center">
							<td>
						<input type="hidden" id="productId$i" value="$val[productId]" />
								$productNo/<br/>
								$productName

							<input type="hidden" equId="$equId" proId="$proId" value="$val[orderId]" name="lock[$i][objId]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[id]" name="lock[$i][objEquId]" id="equId$i"/>
							<input type="hidden" equId="$equId" proId="$proId" value="oa_sale_lease" name="lock[$i][objType]"/></td>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productId]" name="lock[$i][productId]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productName]" name="lock[$i][productName]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productNo]" name="lock[$i][productNo]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="" id="inventoryId$i" name="lock[$i][inventoryId]"/>
								</td>

							<td width="8%"><div equId="$equId" proId="$proId" id="amount$i">$val[number]</div></td>
							<td width="8%">$val[executedNum]</td>

							<td width="8%"><font  color="red"><div equId="$equId" proId="$proId" id="actNum$i">0</div></td>
							<td width="8%"><font  color="red"><div equId="$equId" proId="$proId" id="exeNum$i">0</div></font></td>
							<td width="8%" title="��ǰ�ֿ����������">
								<font color="red">
							     	<a  href="javascript:toLockRecordsPage('$val[id]',true)" >
							     		<div equId="$equId" proId="$proId" id="stockLockNum$i"></div>
							     	</a>
							     </font>
							</td>
							<td width="8%" title="���вֿ�����������ܺ�">
								<font color="red">
							     	<a href="javascript:toLockRecordsPage('$val[id]',false)">
							     		<div equId="$equId" proId="$proId" id="lockNum$i"> $val[lockNum]</div>
							     	</a>
							     </font>
							</td>
							<td width="8%">0</td>
							<td width="8%">$val[issuedPurNum]</td>
							<td width="8%">$val[purchasedNum]</td>
							<td width="8%"><input type="text" equId="$equId" proId="$proId"  value="$lockNum" id="lockNumber$i" name="lock[$i][lockNum]" class="txtshort" onclick="$(this).css({'color':'black'})" onblur="checkLockNum(this,$i)"/></td>
							</tr>
EOT;
					$i++;

			}
			$str .= "<input type='hidden' id='rowNum' value='$i'/>";
		}

		return $str;
	}

	/**
	 * �����豸��ʶuniqueCode�����豸����
	 * ��������
	 * �������ӣ�add �������� minus
	 * ��ִ������ executedNum
	 * ���´������ƻ����� issuedProNum ��;���� onWayNum
	 * �Ѳɹ����� purchasedNum ���´�ɹ��ƻ����� issuedPurNum
	 *
	 */
	function updateEquipmentQuantity($object,$selectType='add'){
		$uniqueCode =  $object['uniqueCode'];
		unset($object['uniqueCode']);
		if($selectType == 'add'){
			foreach ( $object as $key => $value ) {
				$value = $this->__val_escape ( $value );
				$vals [] = "{$key} = {$key} + '{$value}'";
			}
		}else{
			foreach ( $object as $key => $value ) {
				$value = $this->__val_escape ( $value );
				$vals [] = "{$key} = {$key} - '{$value}'";
			}
		}
		$values = join ( ", ", $vals );
		$sql = "UPDATE {$this->tbl_name} SET {$values} where uniqueCode = '$uniqueCode'";
		return $this->_db->query ( $sql );
	}

	/**�������´�ɹ�����
	*author can
	*2011-3-22
	*/
	function updateAmountIssued($id,$issuedNum,$lastIssueNum=false){
		if(isset($lastIssueNum)&&$issuedNum==$lastIssueNum){
			return true;
		}else{
			if($lastIssueNum){
				$sql = " update ".$this->tbl_name." set issuedPurNum=(ifnull(issuedPurNum,0)  + $issuedNum - $lastIssueNum) where id='$id'  ";
			}else{
				$sql = " update ".$this->tbl_name." set issuedPurNum=ifnull(issuedPurNum,0) + $issuedNum where id='$id' ";
			}
			return $this->query($sql);
		}
	}

 }
 /******************************************************************************************/

?>