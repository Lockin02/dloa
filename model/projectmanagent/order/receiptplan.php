<?php
/**
 * @author LiuBo
 * @Date 2011��3��4�� 15:13:34
 * @version 1.0
 * @description:�����տ�ƻ� Model��
 */
 class model_projectmanagent_order_receiptplan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_order_receiptplan";
		$this->sql_map = "projectmanagent/order/receiptplanSql.php";
		parent::__construct ();
	}


	/**
	 * ��Ⱦ�鿴ҳ���ڴӱ�
	 */
	function initTableView($object){

		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
				$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[payDT]</td>
						<td>$val[pType]</td>
						<td>$val[collectionTerms]</td>


					</tr>
EOT;
		}
		return $str;
	}
    /**
     * ��Ⱦ�༭ҳ��ӱ�
     */
     function initTableEdit($rows){
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$value1 = $value2 = $value3 = $value4 = $value5 = "";
				if($val['pType']=="���") {
					$value1 = "selected";
				}elseif($val['pType']=="�ֽ�"){
					$value2 = "selected";
				}elseif($val['pType']=="���л�Ʊ") {
					$value3 = "selected";
				}else{
					$value4 = "selected";
				}
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td>
					 		<input type="text" name="order[receiptplan][$i][money]" id="PayMoney$i" value="$val[money]" size="10" class="txtshort formatMoney" maxlength="40"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="order[receiptplan][$i][payDT]" id="PayDT$i"  size="12" onfocus="WdatePicker()" value="$val[payDT]">
					    </td>
					 	<td>
							<select name="order[receiptplan][$i][pType]" id="PayStyle$i" class="txtshort">
								<option value="���" $value1>���</option>
								<option value="�ֽ�" $value2>�ֽ�</option>
								<option value="���л�Ʊ" $value3>���л�Ʊ</option>
								<option value="��ҵ��Ʊ" $value4>��ҵ��Ʊ</option>
							</select>
					    </td>
					 	<td>
					 		<input type="text" name="order[receiptplan][$i][collectionTerms]" id="collectionTerms$i" value="$val[collectionTerms]" size="70" maxlength="70" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mypay')" title="ɾ����">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}

	/**�����̬�б�
	*author can
	*2011-6-1
	*/
     function initTableChange($rows){
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$value1 = $value2 = $value3 = $value4 = $value5 = "";
				if($val['pType']=="���") {
					$value1 = "selected";
				}elseif($val['pType']=="�ֽ�"){
					$value2 = "selected";
				}elseif($val['pType']=="���л�Ʊ") {
					$value3 = "selected";
				}else{
					$value4 = "selected";
				}
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="order[receiptplan]['.$i.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="order[receiptplan]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td>
					 		<input type="text" name="order[receiptplan][$i][money]" id="PayMoney$i" value="$val[money]" size="10" class="txtshort formatMoney" maxlength="40"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="order[receiptplan][$i][payDT]" id="PayDT$i"  size="12" onfocus="WdatePicker()" value="$val[payDT]">
					    </td>
					 	<td>
							<select name="order[receiptplan][$i][pType]" id="PayStyle$i" class="txtshort">
								<option value="���" $value1>���</option>
								<option value="�ֽ�" $value2>�ֽ�</option>
								<option value="���л�Ʊ" $value3>���л�Ʊ</option>
								<option value="��ҵ��Ʊ" $value4>��ҵ��Ʊ</option>
							</select>
					    </td>
					 	<td>
					 		<input type="text" name="order[receiptplan][$i][collectionTerms]" id="collectionTerms$i" value="$val[collectionTerms]" size="70" maxlength="70" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mypay','receiptplan')" title="ɾ����">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
/*******************************ҳ����ʾ��*********************************/

	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($orderId){
		$this->searchArr['orderID'] = $orderId;
		return $this->list_d();
	}
 }
?>