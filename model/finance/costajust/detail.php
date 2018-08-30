<?php

/**
 * @author Show
 * @Date 2011��5��31�� ���ڶ� 10:30:26
 * @version 1.0
 * @description:�ɱ���������ϸ Model��
 */
class model_finance_costajust_detail extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_finance_costajust_detail";
		$this->sql_map = "finance/costajust/detailSql.php";
		parent::__construct();
	}

	/**
	 * �鿴
	 * @param $rows
	 * @return string
	 */
	function initView($rows) {
		$str = ""; //���ص�ģ���ַ���
		if ($rows) {
			$i = 0; //�б��¼���
			foreach ($rows as $key => $val) {
				$i++;
				$str .= <<<EOT
					<tr>
						<td>$i</td>
						<td>
							$val[productNo]
						</td>
						<td>
							$val[productName]
						</td>
						<td>
							$val[productModel]
						</td>
						<td class='formatMoney'>
							$val[money]
						</td>
						<td>
							$val[remark]
						</td>
					</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='6'>û�������ϸ</td></tr>";
		}
		return $str;
	}

	/**
	 * �༭ҳ����Ⱦ������ϸģ�������
	 * @param $rows
	 * @return array
	 */
	function initEdit($rows) {
		$str = ""; //���ص�ģ���ַ���
		$i = 0; //�б��¼���
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$str .= <<<EOT
					<tr>
						<td>$i</td>
						<td>
							<input type="text" class="txtmiddle" id="productNo$i" value="$val[productNo]" name="costajust[detail][$i][productNo]"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtNormal" readonly="readonly" id="productName$i" value="$val[productName]" name="costajust[detail][$i][productName]"/>
							<input type="hidden" id="productId$i" value="$val[productId]" name="costajust[detail][$i][productId]"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" readonly="readonly" id="productModel$i" value="$val[productModel]" name="costajust[detail][$i][productModel]"/>
						</td>
						<td>
							<input type="text" class="txtmiddle" value="$val[money]" id="money$i" name="costajust[detail][$i][money]"/>
						</td>
						<td>
							<input type="text" class="txtmiddle" value="$val[remark]" name="costajust[detail][$i][remark]"/>
						</td>
						<td width="5%">
							<img src="images/closeDiv.gif" onclick="mydel(this,'mytbody')" title="ɾ����"/>
						</td>
					</tr>
EOT;
			}
		}
		return array(
			$str,
			$i
		);
	}

	/************************�ⲿ�ӿڵ��÷���*************************/

	/**
	 * ���ݵ����ȡ�������
	 * @param $advancesId
	 * @return mixed
	 */
	function getDetail($advancesId) {
		$this->searchArr = array(
			'costajustId' => $advancesId
		);
		$this->asc = false;
		return $this->list_d();
	}

	/**
	 * ���ݵ���ɾ���������
	 * @param $advancesId
	 */
	function deleteDetail($advancesId) {
		$condition = array(
			'costajustId' => $advancesId
		);
		$this->delete($condition);
	}
}