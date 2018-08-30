<?php
/**
 * @description: �ɹ��豸
 * @date 2011-1-3 ����10:26:09
 * @author qian
 */
class model_purchase_equipment_purchaseequipment extends model_base {
	/*
	 * @desription ���캯��
	 * @param tags
	 * @author qian
	 * @date 2011-1-3 ����10:28:31
	 */
	function __construct () {
		$this->tbl_name = "oa_purch_apply_equ";
		$this->sql_map = "purchase/equipment/purchaseequipmentSql.php";
		parent :: __construct();
	}

	/**********************************************ģ�����滻����**************************************************/

	/*
	 * @desription �豸�б�
	 * @param tags
	 * @author qian
	 * @date 2011-1-3 ����01:31:05
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
					<td>���۲ɹ�</td>
					<td>$val[amountIssued]</td>
					<td>$val[dateHope]</td>
					<td>$val[applyPrice]</td>
					<td>$val[remark]</td>
				</tr>
EOT;
			}

		}else{
			$str = "<tr><td colspan='9'>������زɹ��豸</td></tr>";
		}
		return $str;
	}


	/**********************************************�ⲿ�ӿ��෽��**************************************************/

	/*
	 * @desription ��ȡ�豸������
	 * @param tags
	 * @author qian
	 * @date 2011-1-3 ����07:42:08
	 */
	function getEquipment_d () {
		$service = $this->service;
		$rows = $service->listBySqlId();
		return $rows;
	}

}
?>
