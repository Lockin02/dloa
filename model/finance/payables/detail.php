<?php
/**
 * @author Show
 * @Date 2011��5��6�� ������ 16:22:12
 * @version 1.0
 * @description:��ϸ�� Model��
 */
 class model_finance_payables_detail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_payables_detail";
		$this->sql_map = "finance/payables/detailSql.php";
		parent::__construct ();
	}

	/*************************ģ�����ɲ���************************/

	/**
	 * �鿴
	 */
	function initView($rows) {
		$str = ""; //���ص�ģ���ַ���
		if ($rows) {
			$i = 0; //�б��¼���
			$datadictDao = new model_system_datadict_datadict();
			foreach ($rows as $key => $val) {
				$i++;
				$objType = $datadictDao->getDataNameByCode($val['objType']);
				$str .=<<<EOT
						<tr><td>$i</td>
							<td>
								$objType
							</td>
							<td>
								$val[objCode]
							</td>
							<td class='formatMoney'>
								$val[money]
							</td>
							<td>
								$val[payContent]
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='6'>û����ϸ��Ϣ</td></tr>";
		}
		return $str;
	}

	/**
	 * �༭ҳ����Ⱦ������ϸģ�������
	 */
	function initEdit($rows) {
		$str = ""; //���ص�ģ���ַ���
		$i = 0; //�б��¼���
		if ($rows) {
			$datadictArr = $this->getDatadicts ( 'YFRK' );
			foreach ($rows as $key => $val) {
				$i++;
				$objTypeArr = $this->getDatadictsStr ( $datadictArr ['YFRK'], $val ['objType'] );
				if(empty($val ['objType'])){
					$objTypeArr = '<option value="">��ѡ�񵥾�</option>'.$objTypeArr;
				}
				$str .=<<<EOT
						<tr><td>$i</td>
							<td>
								<select class="selectmiddel" id="objType$i" onchange="initGrid($i)" value="$val[objType]" name="payables[detail][$i][objType]">
									$objTypeArr
								</select>
							</td>
							<td>
								<input type="text" class="txt" id="objCode$i" value="$val[objCode]" name="payables[detail][$i][objCode]"/>
								<input type="hidden" id="objId$i" value="$val[objId]" name="payables[detail][$i][objId]"/>
							</td>
							<td>
								<input type="text" class="txtmiddle formatMoney" id="money$i" value="$val[money]" name="payables[detail][$i][money]" onblur="countAll()"/>
							</td>
							<td>
								<input type="text" class="txtlong" value="$val[payContent]" name="payables[detail][$i][payContent]"/>
								<input type="hidden" value="$val[expand1]" name="payables[detail][$i][expand1]"/>
								<input type="hidden" value="$val[expand2]" name="payables[detail][$i][expand2]"/>
							</td>
						 	<td width="5%">
						 		<img src="images/closeDiv.gif" onclick="mydel(this,'mytbody')" title="ɾ����"/>
						 	</td>
						</tr>
EOT;
			}
		}
		return array (
			$str,
			$i
		);
	}

	/**
	 * ��Ⱦ�����˿�ӱ�
	 */
	function initRefund($rows) {
		$str = ""; //���ص�ģ���ַ���
		$i = 0; //�б��¼���
		if ($rows) {
			$datadictDao = new model_system_datadict_datadict();
			foreach ($rows as $key => $val) {
				$i++;
				$objType = $datadictDao->getDataNameByCode($val['objType']);
				$str .=<<<EOT
						<tr><td>$i</td>
							<td>
								$objType
							</td>
							<td>
								$val[objCode]
								<input type="hidden" id="objId$i" value="$val[objId]" name="payables[detail][$i][objId]"/>
								<input type="hidden" id="objCode$i" value="$val[objCode]" name="payables[detail][$i][objCode]"/>
								<input type="hidden" id="objType$i" value="$val[objType]" name="payables[detail][$i][objType]"/>
							</td>
							<td>
								<input type="text" class="readOnlyTxtMiddle formatMoney" id="money$i" value="$val[money]" name="payables[detail][$i][money]" readonly="readonly"/>
								<input type="hidden" id="orgMoney$i" value="$val[money]"/>
							</td>
							<td>
								<input type="text" class="readOnlyTxtLong" readonly='readonly' value="$val[payContent]" name="payables[detail][$i][payContent]"/>
								<input type="hidden" value="$val[expand1]" name="payables[detail][$i][expand1]"/>
								<input type="hidden" value="$val[expand2]" name="payables[detail][$i][expand2]"/>
								<input type="hidden" value="$val[expand3]" name="payables[detail][$i][expand3]"/>
							</td>
						</tr>
EOT;
			}
		}
		return array (
			$str,
			$i
		);
	}

	/************************�ⲿ�ӿڵ��÷���*************************/

	/*
	 * ���ݵ����ȡ�������
	 */
	function getDetail($advancesId) {
		$this->searchArr = array (
			'advancesId' => $advancesId
		);
		$this->asc = false;
		return $this->list_d();
	}

	/*
	 * ��ȡ���˿�Ľ��
	 */
	function getCanRefundDetail($advancesId) {
		$this->searchArr = array (
			'relatedId' => $advancesId
		);
		$this->groupBy = 'c.objId,c.objType having money <> 0';
		$this->asc = false;
		return $this->list_d('select_count');
	}

	/**
	 * ��ȡ���˿��� - ������ɹ��˿�
	 */
	function getCanRefundDetailGE($advancesId) {
		$this->searchArr = array (
			'relatedId' => $advancesId
		);
		$this->groupBy = 'c.objId,c.objType,c.expand1 having money <> 0';
		$this->asc = false;
		return $this->list_d('select_count');
	}

	/*
	 * ���ݵ���ɾ���������
	 */
	function deleteDetail($advancesId) {
		$condition = array (
			'advancesId' => $advancesId
		);
		$this->delete($condition);
	}

	/**
	 * ���������嵥id��ȡ�Ѹ����
	 */
	function getPayedMoney_d($expand1,$objType = 'YFRK-01'){
		$this->searchArr = array (
			'expand1' => $expand1,
			'objType' => $objType
		);
		$this->groupBy = 'c.objId,c.objType,c.expand1 having money <> 0';
		$this->asc = false;
		$rs = $this->list_d('select_count');
		if($rs){
			return $rs[0]['money'];
		}else{
			return 0;
		}
	}
}
?>