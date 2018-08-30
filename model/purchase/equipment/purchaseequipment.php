<?php
/**
 * @description: 采购设备
 * @date 2011-1-3 上午10:26:09
 * @author qian
 */
class model_purchase_equipment_purchaseequipment extends model_base {
	/*
	 * @desription 构造函数
	 * @param tags
	 * @author qian
	 * @date 2011-1-3 上午10:28:31
	 */
	function __construct () {
		$this->tbl_name = "oa_purch_apply_equ";
		$this->sql_map = "purchase/equipment/purchaseequipmentSql.php";
		parent :: __construct();
	}

	/**********************************************模板类替换方法**************************************************/

	/*
	 * @desription 设备列表
	 * @param tags
	 * @author qian
	 * @date 2011-1-3 下午01:31:05
	 */
	function showEquipmentList ($rows) {
		$str = "";
		$i = 0;
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				$i++;
				$cssClass = (($i%2) == 0)?"tr_even":"tr_odd";
				$str .=<<<EOT;
				<tr class="$cssClass">
					<td>$i</td>
					<td>
						$val[objCode]<br>
						$val[productName]
					</td>
					<td>$val[amountAll]</td>
					<td>$val[applyNumb]</td>
					<td>销售采购</td>
					<td>$val[amountIssued]</td>
					<td>$val[dateHope]</td>
					<td>$val[applyPrice]</td>
					<td>$val[remark]</td>
				</tr>
EOT;
			}

		}else{
			$str = "<tr><td colspan='9'>暂无相关采购设备</td></tr>";
		}
		return $str;
	}


	/**********************************************外部接口类方法**************************************************/

	/*
	 * @desription 获取设备的数据
	 * @param tags
	 * @author qian
	 * @date 2011-1-3 下午07:42:08
	 */
	function getEquipment_d () {
		$service = $this->service;
		$rows = $service->listBySqlId();
		return $rows;
	}

}
?>
