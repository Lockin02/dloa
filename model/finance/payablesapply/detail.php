<?php
/**
 * @author Show
 * @Date 2011��5��8�� ������ 13:55:28
 * @version 1.0
 * @description:����������ϸ(��) Model��
 */
class model_finance_payablesapply_detail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_payablesapply_detail";
		$this->sql_map = "finance/payablesapply/detailSql.php";
		parent::__construct ();
	}

	/**
	 * �鿴
	 */
	function initView($rows,$object) {
		$str = $payapplyStr = $invpurchase = ""; //���ص�ģ���ַ���
		if ($rows) {
			$i = 0; //�б��¼���
			$datadictDao = new model_system_datadict_datadict();
			//��ȡ�Ѹ�����
			$payablesDao = new model_finance_payables_payables();
			//��ȡ���ܷ�Ʊ���
			$invpurchasaeDao = new model_finance_invpurchase_invpurchase();
			foreach ($rows as $key => $val) {
				$i++;
				$objType = $datadictDao->getDataNameByCode($val['objType']);
				$purchaseMoney = $payedMoney = $handInvoiceMoney = '';
				if(!empty($val['objId'])){
					$imgStr = '<img src="images/icon/view.gif" title="�鿴����ҵ�񵥾�" onclick="showModalWin(\'?model=purchase_contract_purchasecontract&action=toTabRead&id='.$val['objId'].'&skey='.$val['skey_'].'\',1)"/>';
					$imgStr .= ' <img src="images/icon/search.gif" title="�鿴����ҵ�񸶿���ʷ" onclick="showOpenWin(\'?model=finance_payablesapply_payablesapply&action=toHistory&obj[objId]='.$val['objId'].'&obj[objCode]='.$val['objCode'].'&obj[objType]='.$val['objType'].'&obj[supplierId]='.$object['supplierId'].'&obj[supplierName]='.$object['supplierName'].'&obj[thisId]='.$object['id'].$val['objId'].'&skey='.$val['skey_'].'\',1)"/>';
					$payapplyStr = 'showOpenWin(\'?model=finance_payables_payables&action=toHistory&obj[objId]='.$val['objId'].'&obj[objCode]='.$val['objCode'].'&obj[objType]='.$val['objType'].'&obj[supplierId]='.$object['supplierId'].'&obj[supplierName]='.$object['supplierName'].'&obj[thisId]='.$object['id'].$val['objId'].'&skey='.$val['skey_'].'\',1)';
					$invpurchase = 'showOpenWin(\'?model=finance_invpurchase_invpurchase&action=toHistory&obj[objId]='.$val['objId'].'&obj[objCode]='.$val['objCode'].'&obj[objType]='.$val['objType'].'&obj[supplierId]='.$object['supplierId'].'&obj[supplierName]='.$object['supplierName'].'&obj[thisId]='.$object['id'].$val['objId'].'&skey='.$val['skey_'].'\',1)';

					$purchaseMoney = $val['purchaseMoney'];
					$payedMoney = $payablesDao->getPayedMoneyByPur_d($val['objId'],'YFRK-01');
					$handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($val['objId']);
				}else{
					$imgStr = null;
				}
				$str .=<<<EOT
						<tr><td>$i</td>
							<td>
								$objType
							</td>
							<td>
								$val[objCode]
								$imgStr
							</td>
							<td class='formatMoney'>
								$val[money]
							</td>
							<td class='formatMoney'>$purchaseMoney</td>
							<td>
								<a class='formatMoney' href="#" onclick="$payapplyStr">$payedMoney</a>
							</td>
							<td>
								<a class='formatMoney' href="#" onclick="$invpurchase">$handInvoiceMoney</a>
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
			$payablesapplyDao = new model_finance_payablesapply_payablesapply();
			foreach ($rows as $key => $val) {
				$i++;
				$objTypeArr = $this->getDatadictsStr ( $datadictArr ['YFRK'], $val ['objType'] );
				$objTypeArr = '<option value="">��ѡ������</option>'.$objTypeArr;
				if(!empty($val ['objId'])){
					$purcontInfo = $payablesapplyDao->getContractinfoById_d($val ['objId']);
					$canApply = bcsub($purcontInfo['allMoney'],$purcontInfo['payablesapplyMoney'],2);
					$canApply = bcadd($canApply,$val['money'],2);
				}else{
					$canApply = 0;
				}
				$str .=<<<EOT
						<tr><td>$i</td>
							<td>
								<select class="selectmiddel" id="objType$i" onchange="initGrid(this.value,$i)" value="$val[objType]" name="payablesapply[detail][$i][objType]">
									$objTypeArr
								</select>
							</td>
							<td>
								<input type="text" class="txt" id="objCode$i" value="$val[objCode]" name="payablesapply[detail][$i][objCode]"/>
								<input type="hidden" id="objId$i" value="$val[objId]" name="payablesapply[detail][$i][objId]"/>
							</td>
							<td>
								<input type="text" class="txtmiddle formatMoney" id="money$i" value="$val[money]" name="payablesapply[detail][$i][money]" onblur="checkMax($i);countAll()"/>
								<input type="hidden" id="oldMoney$i" value="$canApply"/>
								<input type="hidden" id="orgMoney$i" value="$val[money]"/>
							</td>
							<td>
								<input type="text" class="readOnlyTxt formatMoney" readonly='readonly' id="purchaseMoney$i" value="$val[purchaseMoney]" name="payablesapply[detail][$i][purchaseMoney]"/>
							</td>
							<td>
								<input type="text" class="readOnlyTxt formatMoney" readonly='readonly' id="payed$i" value="$val[payedMoney]" name="payablesapply[detail][$i][payedMoney]"/>
							</td>
							<td>
								<input type="text" class="readOnlyTxt formatMoney" readonly='readonly' id="handInvoiceMoney$i" value="$val[handInvoiceMoney]" name="payablesapply[detail][$i][handInvoiceMoney]"/>
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
	 * �鿴
	 */
	function initAuditing($rows,$object) {
		$str = $payapplyStr = $invpurchase = ""; //���ص�ģ���ַ���
		if ($rows) {
			$i = 0; //�б��¼���
			$datadictDao = new model_system_datadict_datadict();
			//��ȡ�Ѹ�����
			$payablesDao = new model_finance_payables_payables();
			//��ȡ���ܷ�Ʊ���
			$invpurchasaeDao = new model_finance_invpurchase_invpurchase();
			//��ȡ�ɹ�������Ϣ
			$purchasecontractDao = new model_purchase_contract_purchasecontract();
			foreach ($rows as $key => $val) {
				$addStr = null;
				$i++;
				$objType = $datadictDao->getDataNameByCode($val['objType']);
				$purchaseMoney = $payedMoney = $handInvoiceMoney = '';
				if(!empty($val['objId'])){
					$imgStr = '<img src="images/icon/view.gif" title="�鿴����ҵ�񵥾�" onclick="showModalWin(\'?model=purchase_contract_purchasecontract&action=toTabRead&id='.$val['objId'].'&skey='.$val['skey_'].'\',1)"/>';
					$imgStr .= ' <img src="images/icon/search.gif" title="�鿴����ҵ�񸶿���ʷ" onclick="showOpenWin(\'?model=finance_payablesapply_payablesapply&action=toHistory&obj[objId]='.$val['objId'].'&obj[objCode]='.$val['objCode'].'&obj[objType]='.$val['objType'].'&obj[supplierId]='.$object['supplierId'].'&obj[supplierName]='.$object['supplierName'].'&obj[thisId]='.$object['id'].$val['objId'].'&skey='.$val['skey_'].'\',1)"/>';
					$payapplyStr = 'showOpenWin(\'?model=finance_payables_payables&action=toHistory&obj[objId]='.$val['objId'].'&obj[objCode]='.$val['objCode'].'&obj[objType]='.$val['objType'].'&obj[supplierId]='.$object['supplierId'].'&obj[supplierName]='.$object['supplierName'].'&obj[thisId]='.$object['id'].$val['objId'].'&skey='.$val['skey_'].'\',1)';
					$invpurchase = 'showOpenWin(\'?model=finance_invpurchase_invpurchase&action=toHistory&obj[objId]='.$val['objId'].'&obj[objCode]='.$val['objCode'].'&obj[objType]='.$val['objType'].'&obj[supplierId]='.$object['supplierId'].'&obj[supplierName]='.$object['supplierName'].'&obj[thisId]='.$object['id'].$val['objId'].'&skey='.$val['skey_'].'\',1)';

					$purchaseMoney = $val['purchaseMoney'];
					$payedMoney = $payablesDao->getPayedMoneyByPur_d($val['objId'],'YFRK-01');
					$handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($val['objId']);

					$addStr = $purchasecontractDao->showPayModeList($val['objId']);
				}else{
					$imgStr = null;
				}
				$str .=<<<EOT
						<tr class="tr_count"><td>$i</td>
							<td>
								$objType
							</td>
							<td>
								$val[objCode]
								$imgStr
							</td>
							<td>
								<b class='formatMoney'>$val[money]</b>
							</td>
							<td><b class='formatMoney'>$purchaseMoney</b></td>
							<td>
								<a class='formatMoney' href="#" onclick="$payapplyStr" title="����鿴��ϸ">$payedMoney</a>
							</td>
							<td>
								<a class='formatMoney' href="#" onclick="$invpurchase" title="����鿴��ϸ">$handInvoiceMoney</a>
							</td>
						</tr>
EOT;
				if(!empty($addStr)){
					$str .= "<tr><td></td><td colspan='6' class='innerTd'>".$addStr ."</td></tr>";
				}
				$str .= "<tr class='tr_even'><td colspan='7'></td></tr>";
			}
		} else {
			$str = "<tr align='center'><td colspan='6'>û����ϸ��Ϣ</td></tr>";
		}
		return $str;
	}

	/************************�ⲿ�ӿڵ��÷���*************************/

	/*
	 * ���ݵ����ȡ�������
	 */
	function getDetail($payapplyId) {
		$this->searchArr = array (
			'payapplyId' => $payapplyId
		);
		$this->asc = false;
		return $this->list_d();
	}

	/*
	 * ���ݵ���ɾ���������
	 */
	function deleteDetail($payapplyId) {
		$condition = array (
			'payapplyId' => $payapplyId
		);
		$this->delete($condition);
	}

	/**
	 * ��ȡ�ɹ�������������ϸ��Ϣ
	 */
	function getPayInfo_d($object){
		if(is_array($object)){

			//��ȡ�Ѹ�����
			$payablesDao = new model_finance_payables_payables();
			//��ȡ���ܷ�Ʊ���
			$invpurchasaeDao = new model_finance_invpurchase_invpurchase();

			foreach( $object as $key => $val){
				if(empty($val['objId'])){
					$object[$key]['purchaseMoney'] = null;
					$object[$key]['payedMoney'] = null;
					$object[$key]['handInvoiceMoney'] = null;
				}else{
					$object[$key]['payedMoney'] = $payablesDao->getPayedMoneyByPur_d($val['objId'],$val['objType']);
					$object[$key]['handInvoiceMoney'] = $invpurchasaeDao->getInvMoneyByPur_d($val['objId']);
				}
			}
		}
		return $object;
	}

	/**
	 * ��ȡ���������Ѹ���� - ��Բɹ���������
	 */
	function getPayedDetail_d($objId){
		$this->searchArr = array('objId'=>$objId,'objType'=>'YFRK-01' ,'pStatus'=>'FKSQD-03');
		$this->groupBy = 'c.objId,c.expand1';
		$this->sort = 'c.expand1';
		$rs = $this->list_d('select_sum');
		if(is_array($rs)){
			$rtArr = array();
			foreach($rs as $key => $val){
				$rtArr[$val['expand1']] = $val['money'];
			}
			return $rtArr;
		}else{
			false;
		}
	}

	/**
	 * ��ȡ���������Ѹ���� - ��Բɹ��������� - �����˿�
	 */
	function getPayedDetailAll_d($objId){
		$this->searchArr = array('objId'=>$objId,'objType'=>'YFRK-01' ,'pStatus'=>'FKSQD-03');
		$this->groupBy = 'c.objId,c.expand1';
		$this->sort = 'c.expand1';
		$rs = $this->list_d('select_sumAll');
		if(is_array($rs)){
			$rtArr = array();
			foreach($rs as $key => $val){
				$rtArr[$val['expand1']] = $val['money'];
			}
			return $rtArr;
		}else{
			false;
		}
	}
}
?>