<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/stock/outplan/strategy/planStrategy.php';
/**
 * @author zengzx
 * @Date 2011��5��5�� 17:03:12
 * @version 1.0
 * @description:���۷���֪ͨ������
 */
class model_stock_outplan_strategy_contractplan  implements planStrategy {

	function __construct() {

	}
		/**
	 * @description ����֪ͨ���б���ʾģ��
	 * @param $rows
	 */
	function showList($rows){

	}

	/**
	 * @description ��������֪ͨʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAdd($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
//			echo "<pre>";
//			print_R($rows);
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//���´�����
				$planNum = $rows[$key]['issuedShipNum'];
				//��ͬ����
				$contNum = $rows[$key]['number'];
				//�˿�����
				$backNum = $rows[$key]['backNum'];
				//����������
				$contRemain = $contNum - $planNum + $backNum;
				if( $contRemain <= 0 ){
					continue;
				}
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>
							<input type="text" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productCode]" class='readOnlyTxtItem' readonly/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[id]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal' readonly/></td>
						<td>
							<input type="text" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtNormal' readonly/></td>
						<td>
							<input type="text" name="outplan[productsdetail][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>
							<input type="hidden" id="stockId$j" name="outplan[productsdetail][$j][stockId]" class='txtmiddle'/>
							<input type="hidden" id="stockCode$j" name="outplan[productsdetail][$j][stockCode]" class='txtmiddle'/>
							<input type="text" id="stockName$j" name="outplan[productsdetail][$j][stockName]" class='txtmiddle'/></td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="contNum$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$backNum</font>
							<input type="hidden" id="backNum$j" value='$backNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$contRemain</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j" value='$planNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" name="outplan[productsdetail][$j][number]" id="number$j" value="$contRemain" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<!--td width="8%">$isPresent</td-->
						<!--td>
							<input type="text" id="lockNum" name="outplan[productsdetail][$j][lockNum]" class='txtshort' value="$contRemain"/>
						</td-->
                        <td>
							<a href="#" onclick="showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');"><font color=blue>��ʱ���</font></a>
                        </td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='ɾ����'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="13"><input type="hidden" id="noRows" value="1"/>���������Ϣ</td></tr>';
			}

		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;

	}

	/**
	 * @description �޸ķ���֪ͨʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemEdit($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$docDao = new model_common_contract_allsource();
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//��ͬ���� �� ��ͬ���´﷢������
				$contNumArr = $docDao->getEquInfo($rows[$key]['contEquId'],'oa_contract_contract');
//				echo "<pre>";
//				print_R($contNumArr);
//				if(empty($contNumArr[0]['isSell'])){
//					$isPresent = "<font color='red'>��</font>";
//				}else{
//					$isPresent = "<font>��</font>";
//				}
				$contNum = $contNumArr['number'];//��ͬ����
				$backNum = $contNumArr['backNum'];//�˻�����
				$planNum =  $contNumArr['issuedShipNum'];//���´﷢������
				$planNum = $planNum - $val['number'];//��ȥ���η���������
				$contRemain = $contNum + $backNum - $planNum;
				if( $contRemain <= 0 ){
					continue;
				}
				$str .= <<<EOT
					<tr><td>$j</td>
<!--						<td>
							<input type="hidden" id="productLine$j" name="outplan[productsdetail][$j][productLine]" value="$val[productLine]" class='readOnlyTxtItem' readonly/>
							<input type="text" id="productLineName$j" name="outplan[productsdetail][$j][productLineName]" value="$val[productLineName]" class='readOnlyTxtItem' readonly/></td>
-->						<td>
							<input type="text" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal' readonly/></td>
						<td>
							<input type="text" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtNormal' readonly/></td>
						<td>
							<input type="text" name="outplan[productsdetail][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>
							<input type="hidden" id="stockId$j" name="outplan[productsdetail][$j][stockId]" value="$val[stockId]" class='txtmiddle'/>
							<input type="hidden" id="stockCode$j" name="outplan[productsdetail][$j][stockCode]" value="$val[stockCode]" class='txtmiddle'/>
							<input type="text" id="stockName$j" name="outplan[productsdetail][$j][stockName]" value="$val[stockName]" class='txtmiddle'/></td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="remain$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$backNum</font>
							<input type="hidden" id="backNum$j" value='$backNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$contRemain</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j"  name="outplan[productsdetail][$j][issuedShipNum]" value='$planNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" name="outplan[productsdetail][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<!--td width="8%">$isPresent</td-->
						<!--td>
							<input type="text" id="lockNum" name="outplan[productsdetail][$j][lockNum]" class='txtshort' value="$val[lockNum]"/>
						</td-->
                        <td>
							<a href="#" onclick="showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');"><font color=blue>��ʱ���</font></a>
                        </td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='ɾ����'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>���������Ϣ</td></tr>';
			}

		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;
	}


	/**
	 * @description �޸ķ���֪ͨʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemChange($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$docDao = new model_common_contract_allsource();
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//��ͬ���� �� ��ͬ���´﷢������
				$contNumArr = $docDao->getEquInfo($rows[$key]['contEquId'],'oa_contract_contract');
				//���´�����
				$planNum = $contNumArr['issuedShipNum'];
				$planNum = $planNum-$val['number'];
				//��ͬ����
				$contNum = $contNumArr['number'];
				//�˿�����
				$backNum = $contNumArr['backNum'];
				//����������
				$contRemain = $contNum - $planNum + $backNum;
				if( $contRemain <= 0 ){
					continue;
				}
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="outplan[details]['.$j.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="outplan[details]['.$j.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>
							<input type="text" id="productNo$j" readonly="true" name="outplan[details][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="outplan[details][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="outplan[details][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="outplan[details][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal' readonly/></td>
						<td>
							<input type="text" id="productModel$j" name="outplan[details][$j][productModel]" value="$val[productModel]" class='readOnlyTxtNormal' readonly/></td>
						<td>
							<input type="text" name="outplan[details][$j][unitName]" id="unitName$j" value="$val[unitName]" class='readOnlyTxtShort' readonly/>
						</td>
						<td>
							<input type="hidden" id="stockId$j" name="outplan[details][$j][stockId]" value="$val[stockId]" class='txtmiddle'/>
							<input type="hidden" id="stockCode$j" name="outplan[details][$j][stockCode]" value="$val[stockCode]" class='txtmiddle'/>
							<input type="text" id="stockName$j" name="outplan[details][$j][stockName]" value="$val[stockName]" class='txtmiddle'/></td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="remain$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$backNum</font>
							<input type="hidden" id="backNum$j" value='$backNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$contRemain</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j"  name="outplan[details][$j][issuedShipNum]" value='$planNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" name="outplan[details][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td><input type="hidden" id="executedNum$j" value='$val[executedNum]' class="txtmiddle"/>
							<font color=green>$val[executedNum]</font></td>
						<!--td>
							<input type="text" id="lockNum" name="outplan[details][$j][lockNum]" class='txtshort' value="$val[lockNum]"/>
						</td-->
                        <td>
							<a href="#" onclick="showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');"><font color=blue>��ʱ���</font></a>
                        </td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='ɾ����'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>���������Ϣ</td></tr>';
			}

		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;
	}


	/**
	 * @description �鿴����֪ͨʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemView($rows) {
		$planDao = new model_stock_outplan_outplan();
		$docDao = new model_common_contract_allsource();
		$j = 0;
		$str = ""; //���ص�ģ���ַ���
		if (is_array ( $rows )) {
			$i = 0; //�б��¼���
			foreach ( $rows as $key => $val ) {
				//��ͬ�豸�Ƿ�����
				$j = $i + 1;
				$str .= <<<EOT
						<tr height="30px">
							<td width="8%">$j<input type='hidden' name='isRed' id='isRed$j' value="$val[isRed]"/></td>
<!--							<td>$val[productLineName]</td>-->
							<td>$val[productNo]<input type='hidden' name='isDelete' id='isDelete$j' value="$val[isDelete]"/></td>
							<td>$val[productName]</td>
							<td>$val[productModel]</td>
							<td>$val[stockName]</td>
							<td width="8%">$val[number]</td>
							<td width="8%">$val[unitName]</td>
							<td><font color=green>$val[executedNum]</font></td>
							<td width="8%"><font>��</font></td>
							<!--td width="15%">$val[lockNum]</td-->
	                        <td>
								<a href="#" onclick="showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');"><font color=blue>��ʱ���</font></a>
	                        </td>
						</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11">���������Ϣ</td></tr>';
			}
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}


	/**
	 * ����ת���۷����ƻ��ӱ���Ⱦ
	 */
	 function shwoBToOEqu($rows,$rowNum){
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$docDao = new model_common_contract_allsource();
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//��ͬ���� �� ��ͬ���´﷢������
				$contNumArr = $docDao->getEquInfo($rows[$key]['contEquId'],'oa_contract_contract');
				$contNum = $contNumArr['number'];//��ͬ����
				$planNum =  $contNumArr['issuedShipNum'];//���·���������
				$planNum = $planNum - $val['number'];//��ȥ���η���������
				$contRemain = $contNum - $planNum;
//				if( $contRemain <= 0 ){
//					continue;
//				}
				$oldIdStr='';
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>$val[productNo]
							<input type="hidden" id="productNo_$j" readonly="true" name="outplan[bItem][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[productName]
							<input type="hidden" id="BToOTips_$j" name="outplan[bItem][$j][BToOTips]" value='1' class="txtmiddle"/>
							<input type="hidden" id="contEquId_$j" name="outplan[bItem][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId_$j" name="outplan[bItem][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName_$j" name="outplan[bItem][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel_$j" name="outplan[bItem][$j][productModel]" value="$val[productModel]" class='readOnlyTxtNormal' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[bItem][$j][unitName]" id="unitName_$j" value="$val[unitName]" class='readOnlyTxtShort' readonly/>
							<input type="hidden" id="planNum_$j"  name="outplan[bItem][$j][issuedShipNum]" value='$planNum' class="txtmiddle"/>
						</td>
						<td>$val[number]
							<input type="hidden" name="outplan[bItem][$j][number]" id="number_$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydelBorrow(this,"borrowbody")' title='ɾ����'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>���������Ϣ</td></tr>';
			}else{
				$str .='<input type="hidden" id="borrowNum" value="'.$j.'"/>';
			}

		}
		return $str;
	}


	/**
	 * ����ת���۷����ƻ��ӱ���Ⱦ
	 */
	 function shwoBToOEquChange($rows,$rowNum){
		$j = 0;
		$rowNum;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$docDao = new model_common_contract_allsource();
			$i = $rowNum; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$line=$j-$rowNum;
				//��ͬ���� �� ��ͬ���´﷢������
				$contNumArr = $docDao->getEquInfo($rows[$key]['contEquId'],'oa_contract_contract');
				$contNum = $contNumArr['number'];//��ͬ����
				$planNum =  $contNumArr['issuedShipNum'];//���·���������
				$planNum = $planNum - $val['number'];//��ȥ���η���������
				$contRemain = $contNum - $planNum;
//				if( $contRemain <= 0 ){
//					continue;
//				}
				$oldIdStr='';
				if(empty($val['originalId'])){
					$oldIdStr.='<input type="hidden" name="outplan[bItem]['.$j.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$oldIdStr.='<input type="hidden" name="outplan[bItem]['.$j.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$str .= <<<EOT
					<tr><td>$line</td>
						<td>$val[productNo]
							<input type="hidden" id="BToOTips_$j" name="outplan[bItem][$j][BToOTips]" value='1' class="txtmiddle"/>
							<input type="hidden" id="productNo_$j" readonly="true" name="outplan[bItem][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[productName]
							<input type="hidden" id="contEquId_$j" name="outplan[bItem][$j][contEquId]" value="$val[contEquId]"/>
							$oldIdStr
							<input type="hidden" id="productId_$j" name="outplan[bItem][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName_$j" name="outplan[bItem][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel_$j" name="outplan[bItem][$j][productModel]" value="$val[productModel]" class='readOnlyTxtNormal' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[bItem][$j][unitName]" id="unitName_$j" value="$val[unitName]" class='readOnlyTxtShort' readonly/>
							<input type="hidden" id="planNum_$j"  name="outplan[bItem][$j][issuedShipNum]" value='$planNum' class="txtmiddle"/>
						</td>
						<td>$val[number]
							<input type="hidden" name="outplan[bItem][$j][number]" id="number_$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydelBorrow(this,"invbody")' title='ɾ����'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>���������Ϣ</td></tr>';
			}else{
				$str .='<input type="hidden" id="borrowNum" value="'.$j.'"/>';
			}

		}
		return $str;
	}


	/**
	 * ����ת���۷����ƻ��ӱ���Ⱦ
	 */
	 function shwoBToOEquView($rows,$rowNum){
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
					<tr bgcolor='#7AD730'><td>$j</td>
						<td>$val[productNo]
							<input type="hidden" id="productNo_$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
							<input type="hidden" id="BToOTips_$j" name="outplan[productsdetail][$j][BToOTips]" value='1' class="txtmiddle"/></td>
						<td>$val[productName]
							<input type="hidden" id="contEquId_$j" name="outplan[productsdetail][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId_$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName_$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel_$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtNormal' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[productsdetail][$j][unitName]" id="unitName_$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>$val[number]
							<input type="hidden" name="outplan[productsdetail][$j][number]" id="number_$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>���������Ϣ</td></tr>';
			}else{
				$str .='<input type="hidden" id="borrowNum" value="'.$j.'"/>';
			}
		}
		return $str;
	}

/*************************************************************************************************************************************************************/



	/**
	 * �鿴���ҵ����Ϣ
	 * @param $paramArr
	 */
	function viewRelInfo( $paramArr,$skey ) {
		$str .= ' <img src="images\icon\view.gif" onclick="showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='.$paramArr['docId'].'&perm=view&skey='.$skey.'\',1)">';
		return $str;
	}

	/**
	 * ��������֪ͨʱ�������ҵ��
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$sourceDao = new model_common_contract_allsource();
		$sourceDao->updateIssuedInfo($paramArr,$relItemArr);
		$sourceDao->updateIssuedStatus($paramArr,$relItemArr);
	}


	/**
	 * ��������֪ͨʱ�������ҵ��
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealMailAtAdd($object = false) {
		$outplanDao = new model_stock_outplan_outplan();
		if(is_array($outplanDao->mailArr)){
			$mailArr=$outplanDao->mailArr;
		}else{
			$mailArr=array();
		}
		foreach($mailArr as $key=>$val){
			$nameArr[$key]=$val['responsible'];
			$idArr[$key]=$val['responsibleId'];
		}
		$outmailStr = implode(',',$idArr);
		$addMsg = $outplanDao->getAddMes($object);
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $outplanDao->tbl_name, '�´�', $object['planCode'], $outmailStr, $addMsg, '1');

	}


	/**
	 * �޸ķ���֪ͨʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) {
	}
	/**
	 * ɾ������֪ͨʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtDel($id) {
		$outplanDao = new model_stock_outplan_outplan();
		$sql = "update oa_contract_equ oe left join oa_stock_outplan_product op on oe.id = op.contEquId ".
				"set oe.issuedShipNum=oe.issuedShipNum-op.number where op.mainId=" . $id;
		$outplanDao->_db->query( $sql );
	}

	/**
	 * �������֪ͨʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtChange($paramArr = false, $relItemArr = false) {
		$outplanDao = new model_stock_outplan_outplan();
        $conSql = "select max(id) as contractTmpId,ExaStatus from oa_contract_contract where originalId = '{$paramArr['docId']}' and ExaStatus <> '���';";//�鿴����е�����
        $tmpConData = $outplanDao->_db->get_one($conSql);
        $contractIdSql = " and contractId=" . $paramArr['docId'];
        if(isset($tmpConData['contractTmpId']) && $tmpConData['contractTmpId'] != ""){// �����������,������������������ʱ��¼ID
            $contractIdSql = " and contractId in (" . $paramArr['docId'] . ",". $tmpConData['contractTmpId'] . ")";
        }
		foreach ( $relItemArr as $key=>$vqal ){
		    $equIdSql = " id=" . $relItemArr[$key]['contEquId'];
            // ������صı����¼,������������������ʱ��¼����ID
            if(isset($tmpConData['contractTmpId']) && $tmpConData['contractTmpId'] != ""){
                $equSql = "select id from oa_contract_equ where contractId = {$tmpConData['contractTmpId']} and originalId = {$relItemArr[$key]['contEquId']}";
                $tmpEquData = $outplanDao->_db->get_one($equSql);
                if(isset($tmpEquData['id']) && $tmpEquData['id'] != ''){
                    $equIdSql = " id in (" . $relItemArr[$key]['contEquId'] . "," . $tmpEquData['id'] . ") ";
                }
            }

			if( $vqal['isDel']==1 ){
				$sql = " update oa_contract_equ set issuedShipNum = " . $relItemArr[$key]['issuedShipNum'] . " where " . $equIdSql . $contractIdSql;
				$outplanDao->_db->query( $sql );
			}else{
				$issuedShipNum = $relItemArr[$key]['issuedShipNum'] + $relItemArr[$key]['number'];
				$sql = " update oa_contract_equ set issuedShipNum = " . $issuedShipNum . " where " . $equIdSql . $contractIdSql;
				$outplanDao->_db->query( $sql );
			}
		}
		$sourceDao = new model_common_contract_allsource();
		$sourceDao->updateIssuedStatus($paramArr,$relItemArr);
	}


	/**
	 * ���ƻ�ȡԴ�����ݷ���
	 */
	 function getDocInfo( $id ){
	 	$docDao = new model_contract_contract_contract();
	 	$rows = $docDao -> getContractInfo( $id,array('equ') );
//		echo "<pre>";
//		print_R($rows);
		$equArr = $rows['equ'];
		foreach( $equArr as $key=>$val ){
			if( $val['isBorrowToorder']==1 ){
				unset($rows['equ'][$key]);
			}
		}
		$rows['docApplicant'] = $rows['prinvipalName'];
		$rows['docApplicantId'] = $rows['prinvipalId'];
	 	$rows['docCode'] = $rows['contractCode'];
	 	$rows['docName'] = $rows['contractName'];
	 	$rows['docType'] = 'oa_contract_contract';
	 	$rows['contractType'] = $rows['contractType'];
	 	$rows['contractTypeName'] = $rows['contractTypeName'];
		return $rows;
	 }
	/**
	 * ��ȡ��ͬ�����˷���
	 */
	 function getSaleman( $id ){
	 	$docDao = new model_contract_contract_contract();
	 	$tablename=$docDao->tbl_name;
	 	$condition="id=".$id;
	 	$salemanKey = "prinvipalName as responsible,prinvipalId as responsibleId";
	 	$sql="select $salemanKey from $tablename where $condition";
	 	$salemanArr = $docDao->_db->getArray($sql);
		return $salemanArr[0];
	 }
	/**
	 * ��ȡ��ͬ�����˷���
	 */
	 function getCreateman( $id ){
	 	$docDao = new model_contract_contract_contract();
	 	$tablename=$docDao->tbl_name;
	 	$condition="id=".$id;
	 	$salemanKey = "createName as responsible,createId as responsibleId";
	 	$sql="select $salemanKey from $tablename where $condition";
	 	$salemanArr = $docDao->_db->getArray($sql);
		return $salemanArr[0];
	 }


	/**
	 * ��ɫ�������/����ͳһ�ӿ�
	 */
	  function updateContNumAsOut($planInfo,$rows){
	  	$contDao = new model_common_contract_allsource();
	  	//���º�ͬ��ִ������
	  	$contDao->updateOutStockInfo($planInfo,$rows);
		// ���ݺ�ͬId�޸ĺ�ͬ״̬��
		$contDao->updateOutStockStatus( $planInfo );
	  }


	/**
	 * ��ɫ��ɫ�������/����ͳһ�ӿ�
	 */
	  function updateContNumAsOutRed($contProInfo,$rows){
	  	$contDao = new model_common_contract_allsource();
	  	//���º�ͬ��ִ������
		$contSql = " update oa_contract_equ set backNum=backNum-".$rows['outNum']." where contractId= " . $contProInfo['docId'] . " and id= " . $rows['contEquId'];
		$contDao->query( $contSql );
		// ���ݺ�ͬId�޸ĺ�ͬ״̬��
		$contDao->updateOutStockStatus( $contProInfo );
	  }

	  /**
	   * ����Դ���嵥��Ϣ��ȡδ������Ϣ
	   */
	   function getNotExeNum($contEquInfo){
	  	$contDao = new model_common_contract_allsource();
		return $contDao->getNotExeNum_d( $contEquInfo );
	   }

}
?>