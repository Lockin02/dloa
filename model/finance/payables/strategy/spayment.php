<?php
/*
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include( WEB_TOR . 'model/finance/payables/ipayables.php');

class model_finance_payables_strategy_spayment extends model_base implements ipayables{

	/**
	 * ��ȡ���������¼
	 */
	function getInfoAndDetail_d($objId){
		$payablesapply = new model_finance_payablesapply_payablesapply();

		$obj = $payablesapply->find(array('id' => $objId),null,'sourceType');

		if($obj['sourceType']){
			$rs = $payablesapply->getForPushForDetail_d ( $objId );
			$rs['amount'] = $rs['payMoney'] ;
			$rs['amountCur'] = $rs['payMoneyCur'] ;
		}else{
			$rs = $payablesapply->getForPush_d ( $objId);
			$rs['amount'] = $rs['payMoney'] ;
			$detail = $this->initAddForApply($rs['detail']);
			$rs['detail'] = $detail[0];
			$rs['coutNumb'] = $detail[1];
		}
		return $rs;
	}

	/**
	 *  ��Ⱦ����ӱ�
	 */
	public function initAddForApply($object){
		$str = ""; //���ص�ģ���ַ���
		$i = 0; //�б��¼���
		$firstOption = null;
		if ($object) {
			$datadictArr = $this->getDatadicts ( 'YFRK' );
			foreach ($object as $key => $val) {
				$i++;
				if(empty($val['objCode'])){
					$firstOption = "<option value=''></option>";
				}
				$objTypeArr = $this->getDatadictsStr ( $datadictArr ['YFRK'], $val ['objType'] );
				$str .=<<<EOT
						<tr><td>$i</td>
							<td>
								<select class="selectmiddel" id="objTypeList$i"  disabled='true' value="$val[objType]" name="payables[detail][$i][objType]">
									$firstOption
									$objTypeArr
								</select>
							</td>
							<td>
								<input type="text" class="readOnlyTxtNormal" id="objCode$i" readonly='readonly' value="$val[objCode]" name="payables[detail][$i][objCode]"/>
								<input type="hidden" id="objType$i" value="$val[objType]" name="payables[detail][$i][objType]"/>
								<input type="hidden" id="objId$i" value="$val[objId]" name="payables[detail][$i][objId]"/>
							</td>
							<td>
								<input type="text" class="readOnlyTxt formatMoney" readonly='readonly' id="money$i" value="$val[money]" name="payables[detail][$i][money]"/>
							</td>
							<td>
								<input type="text" class="txtlong" name="payables[detail][$i][payContent]"/>
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
	 * ����ʱ����ҵ����
	 */
	public function businessDealAdd_d($object){
		$payablesapply = new model_finance_payablesapply_payablesapply();
		return $payablesapply->updateStatusByPayedMoney_d($object['payApplyId']);
	}
}
?>
