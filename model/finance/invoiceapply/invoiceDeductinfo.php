<?php
class model_finance_invoiceapply_invoiceDeductinfo extends model_base {
	function __construct() {
		$this->tbl_name = "oa_finance_invoiceapply_deductinfo";
		parent::__construct ();
	}
	
	//��ȡ�ۿ���Ϣ��������ֶ�
	function getDeductinfoByInvoiceApplyId($applyId){
		return $this->findAll(array('invoiceApplyId' => $applyId));
	}
	
	/**
	 * �༭�б���ʾ
	 */
	function rowsToEdit($rows){
		$i = 0;
		$str = "";
		if($rows){
			foreach($rows as $val){
				$i ++;
				$str .=<<<EOT
				<tr>
					<td>$i</td>
					<td>
						<input type="text" class="txtmiddle" name="invoiceapply[deductinfo][$i][grade]" id="grade$i" value="$val[grade]" onblur="countDetail(this)"/>
					</td>
					<td>
						<input type="text" class="txtmiddle formatMoney" name="invoiceapply[deductinfo][$i][deduction]" id="deduction$i" value="$val[deduction]" onblur="countDetail(this)"/>
					</td>
					<td>
						<img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����">
					</td>
				</tr>
EOT;
			}
		}
		return array($str,$i);
	}
	
	/**
	 * �鿴�б���ʾ
	 */
	function rowsToView($rows){
		$i = 0;
		$str = null;
		if($rows){
			foreach($rows as $val){
				$i ++;
				$str .=<<<EOT
				<tr>
					<td>$i</td>
					<td>
						{$val['grade']}
					</td>
					<td class='formatMoney'>
						{$val['deduction']}
					</td>
				</tr>
EOT;
			}
		}else{
			$str= '<tr align="center" id="emptyDeduct"><td colspan="20">û�пۿ���Ϣ</td></tr>';
		}
		return array($str,$i);
	}
}