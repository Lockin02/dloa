<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/stock/outplan/strategy/planStrategy.php';
/**
 * @author zengzx
 * @Date 2011��5��5�� 17:03:12
 * @version 1.0
 * @description:���÷���֪ͨ������
 */
class model_stock_outplan_strategy_borrowplan  implements planStrategy {

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
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//���´�����
				$planNum = $rows[$key]['issuedShipNum'];
				//��ͬ����
				$contNum = $rows[$key]['number'];
				//�˿�����
				$backNum = 0;
				//����������
				$contRemain = $contNum - $planNum;
				if( $contRemain <= 0 ){
					continue;
				}
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>
							<input type="text" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[id]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal'/></td>
						<td>
							<input type="text" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtNormal'/></td>
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
							<input type="hidden" id="backNum$j" value='backNum<' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$contRemain</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j" value='$planNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" name="outplan[productsdetail][$j][number]" id="number$j" value="$contRemain" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<!--td width="8%"><font>��</font></td-->
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
				$str = '<tr><td colspan="12"><input type="hidden" id="noRows" value="1"/>���������Ϣ</td></tr>';
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
				$contNumArr = $docDao->getEquInfo($rows[$key]['contEquId'],'oa_borrow_borrow');
				$contNum = $contNumArr['number'];//��ͬ����
				$backNum = 0;//�˻�����
				$planNum =  $contNumArr['issuedShipNum'];//���´﷢������
				$planNum = $planNum - $val['number'];//��ȥ���η���������
				$contRemain = $contNum - $planNum;
				if( $contRemain <= 0 ){
					continue;
				}
				$str .= <<<EOT
					<tr><td>$j</td>
 						<td>
							<input type="text" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal'/></td>
						<td>
							<input type="text" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtNormal'/></td>
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
				$contNumArr = $docDao->getEquInfo($rows[$key]['contEquId'],'oa_borrow_borrow');
				$contNum = $contNumArr['number'];//��ͬ����
				$backNum = 0;//�˻�����
				$planNum =  $contNumArr['issuedShipNum'];//���´﷢������
				$planNum = $planNum - $val['number'];//��ȥ���η���������
				$contRemain = $contNum - $planNum;
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
							<input type="text" id="productNo$j" readonly="true" name="outplan[details][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="outplan[details][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="outplan[details][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="outplan[details][$j][productName]" value="$val[productName]" class='readOnlyTxtNormal'/></td>
						<td>
							<input type="text" id="productModel$j" name="outplan[details][$j][productModel]" value="$val[productModel]" class='readOnlyTxtNormal'/></td>
						<td>
							<input type="text" name="outplan[details][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
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
						<!--td width="8%"><font>��</font></td-->
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
		$j = 0;
		$str = ""; //���ص�ģ���ַ���
		if (is_array ( $rows )) {
			$i = 0; //�б��¼���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
						<tr height="30px">
							<td width="8%">$j</td>
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
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//��ͬ���� �� ��ͬ���´﷢������
				if( $rows[$key]['contEquId'] ){
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_sale_order_equ where orderId=" . $rows[$key]['docId']
						. " and id=" . $rows[$key]['contEquId'];
				}else{
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_sale_order_equ where orderId=" . $rows[$key]['docId']
						. " and productId=" . $rows[$key]['productId'];
				}
				$contNumArr = $planDao->_db->getArray( $contNumSql );
				$contNum = $contNumArr[0]['contNum'];//��ͬ����
				$planNum =  $contNumArr[0]['issuedShipNum'];//���·���������
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
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//��ͬ���� �� ��ͬ���´﷢������
				if( $rows[$key]['contEquId'] ){
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_sale_order_equ where orderId=" . $rows[$key]['docId']
						. " and id=" . $rows[$key]['contEquId'];
				}else{
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_sale_order_equ where orderId=" . $rows[$key]['docId']
						. " and productId=" . $rows[$key]['productId'];
				}
				$contNumArr = $planDao->_db->getArray( $contNumSql );
				$contNum = $contNumArr[0]['contNum'];//��ͬ����
				$planNum =  $contNumArr[0]['issuedShipNum'];//���·���������
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
					<tr><td>$j</td>
						<td>$val[productNo]
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
				$line=$j-$rowNum;
				$str .= <<<EOT
					<tr><td>$i</td>
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
		$str="";
		$str .= ' <img src="images\icon\view.gif" onclick="showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&id='.$paramArr['docId'].'&perm=view&skey='.$skey.'\')">';
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
		$sql = "update oa_borrow_equ oe left join oa_stock_outplan_product op on oe.id = op.contEquId ".
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
		foreach ( $relItemArr as $key=>$vqal ){
		    // ��ȡ���µı�����ϼ�¼
            $tmpEquIdSql = "select max(id) as id,borrowId from oa_borrow_equ where (originalId = '{$relItemArr[$key]['contEquId']}') and istemp = 1;";
            $tmpEquIdArr = $outplanDao->_db->get_one($tmpEquIdSql);

			if( $vqal['isDel']==1 ){
			    $equIds = ($tmpEquIdArr && isset($tmpEquIdArr['id']))? " id in ({$relItemArr[$key]['contEquId']},{$tmpEquIdArr['id']})" : " id=" . $relItemArr[$key]['contEquId'];
				$sql = " update oa_borrow_equ set issuedShipNum = " . $relItemArr[$key]['issuedShipNum'] . " where ". $equIds;
				$outplanDao->_db->query( $sql );
			}else{
                $equIds = ($tmpEquIdArr && isset($tmpEquIdArr['id']))? " id in ({$relItemArr[$key]['contEquId']},{$tmpEquIdArr['id']})" : " id=" . $relItemArr[$key]['contEquId'];
				$issuedShipNum = $relItemArr[$key]['issuedShipNum'] + $relItemArr[$key]['number'];
                $sql = " update oa_borrow_equ set issuedShipNum = " . $issuedShipNum . " where ". $equIds;
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
	 	$docDao = new model_projectmanagent_borrow_borrow();
	 	$rows = $docDao -> getBorrowInfo( $id,array('equ') );
	 	if(!isset($rows['address'])){
	 		$rows['address'] = '';
	 	}
		$rows['docApplicant'] = $rows['createName'];
		$rows['docApplicantId'] = $rows['createId'];
		$rows['planDocId'] = $rows['id'];
	 	$rows['docCode'] = $rows['Code'];
	 	$rows['docName'] = "";
	 	$rows['docType'] = 'oa_borrow_borrow';
	 	$rows['contractType'] = "oa_borrow_borrow";
	 	$rows['contractTypeName'] = "���÷���";
		return $rows;
	 }

	/**
	 * ��ȡ��ͬ�����˷���
	 */
	 function getSaleman( $id ){
	 	$docDao = new model_projectmanagent_borrow_borrow();
	 	$tablename=$docDao->tbl_name;
	 	$condition="id=".$id;
	 	$salemanKey = "salesName as responsible,salesNameId as responsibleId,createName,createId";
	 	$sql="select $salemanKey from $tablename where $condition";
	 	$rows = $docDao->_db->getArray($sql);
	 	$salemanArr = array();
		if( $rows['responsibleId'] != '' ){
			$salemanArr=$rows;
		}else{
			$salemanArr[0]['responsible'] = $rows[0]['createName'];
			$salemanArr[0]['responsibleId'] = $rows[0]['createId'];
		}
		return $salemanArr[0];
	 }

	 	/**
	 * ��ȡ��ͬ�����˷���
	 */
	 function getCreateman( $id ){
	 	$docDao = new model_projectmanagent_borrow_borrow();
	 	$tablename=$docDao->tbl_name;
	 	$condition="id=".$id;
	 	$salemanKey = "createName as responsible,createId as responsibleId";
	 	$sql="select $salemanKey from $tablename where $condition";
	 	$salemanArr = $docDao->_db->getArray($sql);
		return $salemanArr[0];
	 }

	 /**
	  * ���ݷ�������ı��ͬ״̬
	  */
	  function dealupdateOrderShipStatus_d( $id ){
	  	$docDao = new model_projectmanagent_borrow_borrow();
	 	$orderRemainSql = " select (sum(number)-sum(executedNum)) as remainNum,sum(number) as allNum from oa_borrow_equ where borrowId=" . $id;
	 	$remainNum = $docDao->_db->getArray( $orderRemainSql );
	 	if( $remainNum[0]['remainNum'] <= 0 ){
	 		$DeliveryStatus = 1;
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => $DeliveryStatus,
		 	);
		 	$docDao->updateById( $statusInfo );
	 	}elseif( $remainNum[0]['remainNum'] == $remainNum[0]['allNum'] ){
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => '0',
		 	);
		 	$this->updateById( $statusInfo );
		} else {
	 		$DeliveryStatus = 2;
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => $DeliveryStatus,
		 	);
		 	$docDao->updateById( $statusInfo );
	 	}
	 	return 0;
	  }


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
		$contSql = " update oa_borrow_equ set backNum=backNum-".$rows['outNum']." where borrowId= " . $contProInfo['docId'] . " and id= " . $rows['contEquId'];
		$contDao->_db->query( $contSql );
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