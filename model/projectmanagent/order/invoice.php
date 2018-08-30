<?php
/**
 * @author LiuBo
 * @Date 2011��3��4�� 15:06:47
 * @version 1.0
 * @description:������Ʊ�ƻ� Model��
 */
 class model_projectmanagent_order_invoice  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_ordert_invoice";
		$this->sql_map = "projectmanagent/order/invoiceSql.php";
		parent::__construct ();
	}



	/**
	 * ��Ⱦ�鿴ҳ���ڴӱ�
	 */
	function initTableView($object){

		$str = "";
		$i = 0;
		$dataDictDao = new model_system_datadict_datadict();
		foreach($object as $key => $val ){
			$i ++ ;
			$iType = $dataDictDao->getDataNameByCode($val['iType']);
				$str .=<<<EOT
                   <tr>
						<td width="5%">$i</td>
						<td class="formatMoney">$val[money]</td>
						<td class="formatMoney">$val[softM]</td>
						<td>$iType</td>
						<td>$val[invDT]</td>
						<td>$val[remark]</td>

					</tr>
EOT;
		}

		return $str;
	}

   /**
    * ��Ⱦ�༭ҳ��ӱ�
    */
    function initTableEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			$datadictArr = $this->getDatadicts ( "FPLX" );
			foreach ($rows as $val) {
				$i++;
				$productLineStr = $this->getDatadictsStr ( $datadictArr ['FPLX'], $val ['iType'] );
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td align="center">
					 		<input type="text" name="order[invoice][$i][money]" id="InvMoney$i" value="$val[money]" class="txtshort formatMoney"/>
					 	</td>
					 	<td align="center">
					 		<input type="text" name="order[invoice][$i][softM]" id="InvSoftM$i" value="$val[softM]" class="txtshort formatMoney"/>
					 	</td>
					 	<td>
						  <select class="txtmiddle" name="order[invoice][$i][iType]">
						    $productLineStr
						  </select>
					 	</td>
					 	<td align="center">
					        <input type="text" name="order[invoice][$i][invDT]" id="InvDT$i" class="txtshort" onfocus="WdatePicker()" value="$val[invDT]">
					    </td>
					 	<td align="center">
					 		<input type="text" name="order[invoice][$i][remark]" id="InvRemark$i" value="$val[remark]" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'myinv')" title="ɾ����">
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
    function initTableChange($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			$datadictArr = $this->getDatadicts ( "FPLX" );
			foreach ($rows as $val) {
				$i++;
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="order[invoice]['.$i.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="order[invoice]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$productLineStr = $this->getDatadictsStr ( $datadictArr ['FPLX'], $val ['iType'] );
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td align="center">
					 		<input type="text" name="order[invoice][$i][money]" id="InvMoney$i" value="$val[money]" class="txtshort formatMoney"/>
					 	</td>
					 	<td align="center">
					 		<input type="text" name="order[invoice][$i][softM]" id="InvSoftM$i" value="$val[softM]" class="txtshort formatMoney"/>
					 	</td>
					 	<td>
						  <select class="txtmiddle" name="order[invoice][$i][iType]">
						    $productLineStr
						  </select>
					 	</td>
					 	<td align="center">
					        <input type="text" name="order[invoice][$i][invDT]" id="InvDT$i" class="txtshort" onfocus="WdatePicker()" value="$val[invDT]">
					    </td>
					 	<td align="center">
					 		<input type="text" name="order[invoice][$i][remark]" id="InvRemark$i" value="$val[remark]" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'myinv','invoice')" title="ɾ����">
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
		$this->searchArr['orderId'] = $orderId;
		return $this->list_d();
	}
 }
?>