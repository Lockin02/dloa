<?php


/*
 * Created on 2010-12-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_stock_picking_pickingapplyDetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_pickingapply_detail";
		$this->sql_map = "stock/picking/pickingapplyDetailSql.php";
		parent :: __construct();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
	 *************************************************************************************************/

	function showlist($rows, $showpage) {
//		$str = ""; //���ص�ģ���ַ���
//		if ($rows) {
//			$i = 0; //�б��¼���
//			$datadictDao = new model_system_datadict_datadict();
//			foreach ($rows as $key => $val) {
//				$i++;
//				$incomeType = $datadictDao->getDataNameByCode($val[incomeType]);
//				$str .=<<<EOT
//						<tr id="tr_$val[id]">
//							<td><input type="checkbox" name="datacb"  value="$val[id]" onClick="checkOne();"></td>
//							<td align="center">$i</td>
//							<td align="center">$val[incomeNo]</td>
//							<td align="center">$val[incomeUnitName]</td>
//							<td align="center">$val[incomeDate]</td>
//							<td align="center">$incomeType</td>
//							<td align="center">$val[incomeMoney]</td>
//							<td align="center">$val[createName]</td>
//							<td align="center">$val[createTime]</td>
//							<td align="center" id="m_$val[id] ">
//								<a href="?model=finance_income_income&action=init&perm=view&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�޸ĵ������" class="thickbox">�޸�</a>
//
//							</td>
//						</tr>
//EOT;
//			}
//		} else {
//			$str = "<tr align='center'><td colspan='50'>û�е��������Ϣ</td></tr>";
//		}
//		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show(6) . '</td></tr>';
	}


	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/*
	 * ���ݷ��÷�ƱID��ȡ������Ŀ
	 */
	function getDetailByPickingId($pickingId) {
		$this->searchArr = array (
			'pickingId' => $pickingId
		);
		$this->asc = false;
		return $this->list_d();
	}

	/*
	 * ���ݷ��÷�ƱIDɾ��������Ŀ
	 */
	function deleteDetailByPickingId($pickingId) {
		$condition = array (
			"pickingId" => $pickingId
		);
		$this->delete($condition);
	}

}
?>
