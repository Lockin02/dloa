<?php
/*
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once( WEB_TOR . 'model/finance/payablesapply/ipayablesapply.php');

class model_finance_payablesapply_strategy_none extends model_base implements ipayablesapply{

	//���Զ�Ӧ��
	private $thisClass = '';

	//��Ӧ����
	private $thisCode = 'YFRK-04';

	//Դ����Ϣ��ȡ
	function getObjInfo_d($obj){
		return null;
	}

	/**
	 * ��Ⱦ����ҳ��ӱ�
	 */
	function initAdd_d($object,$mainObj){
		$str = null;
		$i = 0;
		if(is_array($object['detail'])){
//			print_r($object['detail']);
			foreach($object['detail'] as $key => $val){
				//��ȡ������������
				$canApply = bcsub($val['orderMoney'],$mainObj->getApplyMoneyByPur_d($val['id'],$this->thisCode),2);

				if($canApply == 0) return false;

				$i++;
				$str .=<<<EOT
					<tr>
						<td>
							<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����">
						</td>
						<td>
							$i
						</td>
						<td>
							<input type="text" id="contractCode$i" value="$object[sourceTypeCN]" class="readOnlyTxtMiddle" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][objCode]" id="objCode$i" value="$val[orderCode]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][objId]" id="objId$i" value="$val[id]"/>
							<input type="hidden" name="payablesapply[detail][$i][objType]" id="objType$i" value="$this->thisCode"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][money]" id="money$i" onblur="checkMax($i);countAll();" value="$canApply" class="txtmiddle formatMoney"/>
							<input type="hidden" id="oldMoney$i" value="$canApply"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][purchaseMoney]" id="purchaseMoney$i" value="$val[orderMoney]" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][payDesc]" id="payDesc$i" class="txtlong"/>
						</td>
					</tr>
EOT;
			}
		}
		return array($str,$i);
	}

	/**
	 * ��Ⱦ�鿴ҳ��ӱ�
	 */
	function initView_d($object){
		$str = null;
		$i = 0;
		if(is_array($object)){
			$datadictDao = new model_system_datadict_datadict();
			$sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			print_r($object['detail']);
			foreach($object as $key => $val){
				$i++;
				$str .=<<<EOT
					<tr>
						<td>
							$i
						</td>
						<td>
							$sourceTypeCN
						</td>
						<td>
							<a href="#" onclick="openContract($val[objId],'$sourceTypeCN')">$val[objCode]</a>
						</td>
						<td class="formatMoney">$val[money]</td>
						<td class="formatMoney">$val[purchaseMoney]</td>
						<td>
							$val[payDesc]
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * ��Ⱦ�鿴ҳ��ӱ�
	 */
	function initAudit_d($object){
		$str = null;
		$i = 0;
		if(is_array($object)){
			$datadictDao = new model_system_datadict_datadict();
			$sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			print_r($object['detail']);
			foreach($object as $key => $val){
				$i++;
				$str .=<<<EOT
					<tr>
						<td>
							$i
						</td>
						<td>
							$sourceTypeCN
						</td>
						<td>
							<a href="#" onclick="openContract($val[objId],'$sourceTypeCN')">$val[objCode]</a>
						</td>
						<td class="formatMoney">$val[money]</td>
						<td class="formatMoney">$val[purchaseMoney]</td>
						<td>
							$val[payDesc]
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * ��Ⱦ�༭ҳ��ӱ�
	 */
	function initEdit_d($object,$mainObj){
		$str = null;
		$i = 0;
		if(is_array($object)){
			$datadictDao = new model_system_datadict_datadict();
			$sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			print_r($object['detail']);
			foreach($object as $key => $val){
				//��ȡ������������
				$canApply = bcsub($val['purchaseMoney'],$mainObj->getApplyMoneyByPur_d($val['objId'],$val['objType']),2);
				//�����ݿ������� = ʣ��������� + �����ݽ��
				$canApply = bcadd($canApply,$val['money'],2);

				$i++;
				$str .=<<<EOT
					<tr>
						<td>
							<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����">
						</td>
						<td>
							$i
						</td>
						<td>
							<input type="text" id="contractCode$i" value="$sourceTypeCN" class="readOnlyTxtMiddle" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][objCode]" id="objCode$i" value="$val[objCode]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][objId]" id="objId$i" value="$val[objId]"/>
							<input type="hidden" name="payablesapply[detail][$i][objType]" id="objType$i" value="$this->thisCode"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][money]" id="money$i" onblur="checkMax($i);countAll();" value="$val[money]" class="txtmiddle formatMoney"/>
							<input type="hidden" id="oldMoney$i" value="$canApply"/>
							<input type="hidden" id="orgMoney$i" value="$val[money]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][purchaseMoney]" id="purchaseMoney$i" value="$val[purchaseMoney]" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][payDesc]" id="payDesc$i" value="$val[payDesc]" class="txtlong"/>
						</td>
					</tr>
EOT;
			}
		}
		return array($str,$i);
	}

	/**
	 * ��Ⱦ��ӡҳ��ӱ�
	 */
	function initPrint_d($object){
		$str = null;
		$i = 0;
		if(is_array($object)){
			$datadictDao = new model_system_datadict_datadict();
			$sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			print_r($object['detail']);
			foreach($object as $key => $val){
				$i++;
				$str .=<<<EOT
					<tr>
						<td>
							$val[payDesc]
						</td>
						<td class="formatMoney">$val[money]</td>
						<td>
							$sourceTypeCN
						</td>
						<td>
							$val[objCode]
						</td>
						<td class="formatMoney">$val[purchaseMoney]</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * ���Ӹ�����Ϣ
	 */
	function initAddInfo_d($object){
		return "";
	}



	/******************************** �����������Ƹ���� *******************************/
	/**
	 * �����������Ƹ����
	 */
	function initPayablesAdd_i($object){
		return array (
			null,
			null
		);
	}

	/**
	 * ��ʼ��������ϸ
	 */
	function initPayContent_i($object){
		$str = null;
		return $str;
	}

	/**
	 * ����ȷ�ϸ���������֯��չ�ֶ�
	 */
	function rebuildExpandArr_i($object){
		return array();
	}
}
?>
