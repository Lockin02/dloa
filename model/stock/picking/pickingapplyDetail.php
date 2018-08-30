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
	 * ------------------------------以下为页面模板显示调用方法------------------------------
	 *************************************************************************************************/

	function showlist($rows, $showpage) {
//		$str = ""; //返回的模板字符串
//		if ($rows) {
//			$i = 0; //列表记录序号
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
//								<a href="?model=finance_income_income&action=init&perm=view&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="修改到款分配" class="thickbox">修改</a>
//
//							</td>
//						</tr>
//EOT;
//			}
//		} else {
//			$str = "<tr align='center'><td colspan='50'>没有到款分配信息</td></tr>";
//		}
//		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show(6) . '</td></tr>';
	}


	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/*
	 * 根据费用发票ID获取费用条目
	 */
	function getDetailByPickingId($pickingId) {
		$this->searchArr = array (
			'pickingId' => $pickingId
		);
		$this->asc = false;
		return $this->list_d();
	}

	/*
	 * 根据费用发票ID删除费用条目
	 */
	function deleteDetailByPickingId($pickingId) {
		$condition = array (
			"pickingId" => $pickingId
		);
		$this->delete($condition);
	}

}
?>
