<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/produce/protask/strategy/taskstrategy.php';
/**
 * @author zengzx
 * @Date 2011��6��2�� 13:50:52
 * @version 1.0
 * @description:���������������
 */
class model_produce_protask_strategy_borrowprotask implements taskstrategy {

	function __construct() {

	}
		/**
	 * @description ���������б���ʾģ��
	 * @param $rows
	 */
	function showList($rows){

	}

	/**
	 * @description ������������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAdd($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$protaskDao = new model_produce_protask_protask();
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
//				$executedNumSql = " select sum(number) as allNum from oa_produce_protaskequ where relDocId=" . $rows[$key]['orderId']
//					. " and productId=" . $rows[$key]['productId'] . " and relDocType='oa_sale_order'";
//				$executedNum = $protaskDao->_db->getArray( $executedNumSql );
				//��ͬ����
				$contNum = $rows[$key]['number']*1;
				//���´���������
				$protaskNum = $rows[$key]['issuedProNum']*1;
				$contRemain = $contNum - $protaskNum;
				if( $contRemain <= 0 ){
					continue;
				}
				$str .= <<<EOT
					<tr><td>$j</td>
<!--						<td>
							<input type="hidden" id="productLine$j" name="protask[productsdetail][$j][productLine]" value="$val[productLine]" class='readOnlyTxtItem' readonly/>
							<input type="text" id="productLineName$j" name="protask[productsdetail][$j][productLineName]" value="$val[productLineName]" class='readOnlyTxtItem' readonly/></td>
-->						<td>
							<input type="text" id="productNo$j" readonly="true" name="protask[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="protask[productsdetail][$j][contEquId]" value="$val[id]"/>
							<input type="hidden" id="productId$j" name="protask[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="protask[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle'/></td>
						<td>
							<input type="text" id="productModel$j" name="protask[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem'/>
						</td>
						<td>
							<input type="text" id="unitName$j" name="protask[productsdetail][$j][unitName]" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="contNum$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$protaskNum</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j" value='$planNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" name="protask[productsdetail][$j][number]" id="number$j" value="$contRemain" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<input type="hidden" id="licenseId$j" name="protask[productsdetail][$j][license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$j');" />
		 			    </td>
                        <td>
                        	<input type="text" name="protask[productsdetail][$j][referDate]" id="referDate$j" class="txtmiddle" onfocus="WdatePicker()"/>
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
	 * @description �޸���������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemEdit($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//��ͬ����
				$contNumSql = " select number as contNum,issuedProNum from oa_borrow_equ where borrowId=" . $rows[$key]['relDocId']
					. " and id=" . $rows[$key]['contEquId'];
				//�ƻ�����
				$contNumArr = $planDao->_db->getArray( $contNumSql );
				$contNum = $contNumArr[0]['contNum']*1;
				$planNum = $contNumArr[0]['issuedProNum']*1;
				$planNum = $planNum - $val['number'];//��ȥ���η���������
				$contRemain = $contNum - $planNum;
				if( $contRemain <= 0 ){
					continue;
				}
				$str .= <<<EOT
					<tr><td>$j</td>
<!--						<td>
							<input type="hidden" id="productLine$j" name="protask[productsdetail][$j][productLine]" value="$val[productLine]" class='readOnlyTxtItem' readonly/>
							<input type="text" id="productLineName$j" name="protask[productsdetail][$j][productLineName]" value="$val[productLineName]" class='readOnlyTxtItem' readonly/></td>
-->						<td>
							<input type="text" id="productNo$j" readonly="true" name="protask[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="protask[productsdetail][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="protask[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="protask[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle'/></td>
						<td>
							<input type="text" id="productModel$j" name="protask[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="text" id="unitName$j" name="protask[productsdetail][$j][unitName]" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="remain$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$planNum</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j"  name="protask[productsdetail][$j][issuedProNum]" value='$planNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" name="protask[productsdetail][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<!--td>
							<input type="text" id="lockNum" name="protask[productsdetail][$j][lockNum]" class='txtshort' value="$val[lockNum]"/>
						</td-->
						<td>
							<input type="hidden" id="licenseId$j" name="protask[productsdetail][$j][license]"/>
		 			        <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$j');" />
		 			    </td>
                        <td>
                        	<input type="text" name="protask[productsdetail][$j][referDate]" id="referDate$j" class="txtmiddle" value="$val[referDate]" onfocus="WdatePicker()"/>
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
	 * @description �鿴��������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemView($rows) {
		$j = 0;
		$str = ""; //���ص�ģ���ַ���
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$i = 0; //�б��¼���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//��ͬ����
				$contNumSql = " select sum(number) as contNum from oa_borrow_equ where borrowId=" . $rows[$key]['relDocId']
					. " and id=" . $rows[$key]['contEquId'];
				//�ƻ�����
				$executedNumSql = " select sum(number) as allNum from oa_produce_protaskequ where relDocId=" . $rows[$key]['relDocId']
					. " and id=" . $rows[$key]['contEquId'] . " and relDocType='" . $rows[$key]['relDocType'] . "'";
				$contNumArr = $planDao->_db->getArray( $contNumSql );
				$executedNum = $planDao->_db->getArray( $executedNumSql );
				$contNum = $contNumArr[0]['contNum']*1;
				$planNum = $executedNum[0]['allNum']*1;
				$contRemain = $contNum - $planNum;
               if(empty($val['license'] )){
               		$license = "��������";
               }else{
               		$license = "<input type='button' class='txt_btn_a' value='����' onclick='" .
               				"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=".$val['license']."" .
               						"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
               }
				$str .= <<<EOT
						<tr height="30px">
							<td width="5%">$j</td>
<!--							<td>$val[productLineName]</td>-->
							<td>$val[productNo]</td>
							<td>$val[productName]</td>
							<td>$val[productModel]</td>
							<td width="8%">
								$val[unitName]
							</td>
							<!--td>
								<font color=green>$contNum</font>
							</td>
							<td>
								<font color=green>$planNum</font>
							</td-->
							<td width="8%">
								$val[number]
							</td>
	                        <td>
								$license
	                        </td>
	                        <td>
								$val[referDate]
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
	 * �鿴���ҵ����Ϣ
	 * @param $paramArr
	 */
	function viewRelInfo( $paramArr,$skey ) {
		$str .= ' <img src="images\icon\view.gif" onclick="showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&id=' . $paramArr['relDocId'].'&perm=view&skey='.$skey.'\')">';
		return $str;
	}

	/**
	 * ������������ʱ�������ҵ��
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$protaskDao = new model_produce_protask_protask();
		foreach ( $relItemArr as $key=>$vqal ){
			$sql = " update oa_borrow_equ set issuedProNum = issuedProNum+" . $relItemArr[$key]['number'] . " where id=" . $relItemArr[$key]['contEquId'] . " and borrowId=" . $paramArr['relDocId'];
			$protaskDao->_db->query( $sql );
		}
	}
	/**
	 * �޸���������ʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) {
		$outplanDao = new model_produce_protask_protask();
		foreach ( $relItemArr as $key=>$vqal ){
			$issuedShipNum = $relItemArr[$key]['issuedProNum'] + $relItemArr[$key]['number'];
			$sql = " update oa_borrow_equ set issuedProNum = " . $issuedShipNum . " where id=" . $relItemArr[$key]['contEquId'] . " and borrowId=" . $paramArr['relDocId'];
			$outplanDao->_db->query( $sql );
		}
	}


	/**
	 * ���ƻ�ȡԴ�����ݷ���
	 */
	 function getDocInfo( $id ){
	 	$docDao = new model_projectmanagent_borrow_borrow();
	 	$rows = $docDao -> get_d( $id );
	 	$rows['orderequ']=$rows['borrowequ'];
		$rows['relDocName'] = '';
		$rows['relDocCode'] = $rows['Code'];
		$rows['relDocId'] = $rows['id'];
		return $rows;
	 }

	 /**
	  * ���ݷ�������ı��ͬ״̬
	  */
	  function dealupdateOrderShipStatus_d( $id ){
	  	$docDao = new model_projectmanagent_borrow_borrow();
	  	$docDao->updateOrderShipStatus_d( $id );
	  }


}
?>