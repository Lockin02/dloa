<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include( WEB_TOR . 'model/finance/invoiceapply/iinvoiceapply.php');
/**
 * ���ۿ�Ʊ�������
 */
class model_finance_invoiceapply_strategy_other extends model_base implements iinvoiceapply{

	/************************ҳ����Ⱦ**********************/
	private $thisClass = 'model_contract_other_other';

	/**
	 * ������Ʊ����ʱ�Զ���Ⱦ�ӱ�
	 */
	function initEquipInAdd($object){
		$i = 0;
		$str = "";
		if($object){
			$datadictArr = $this->getDatadicts ( 'CPFWLX' );
			foreach($object as $val){
				$i ++;
				$productTypeStr = $this->getDatadictsStr ( $datadictArr ['CPFWLX']);
				$str .=<<<EOT
				<tr>
					<td>$i</td>
					<td>
						<input type="text" class="txtmiddle" name="invoiceapply[invoiceDetail][$i][productName]" id="invoiceEquName$i" value="$val[productName]"/>
						<input type="hidden" name="invoiceapply[invoiceDetail][$i][productId]" id="invoiceEquId$i" value="$val[productId]"/>
					</td>
					<td>
						<input type="text" class="txtshort" name="invoiceapply[invoiceDetail][$i][amount]" id="amount$i" value="$val[amount]"  onblur="countDetail(this)" size="5"/>
					</td>
					<td><input type="text" class="txtshort" name="invoiceapply[invoiceDetail][$i][softMoney]" id="softMoney$i" value="0" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort" name="invoiceapply[invoiceDetail][$i][hardMoney]" id="hardMoney$i" value="0" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort" name="invoiceapply[invoiceDetail][$i][repairMoney]" id="repairMoney$i" value="0" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort" name="invoiceapply[invoiceDetail][$i][serviceMoney]" id="serviceMoney$i" value="0" onblur="countDetail(this)"/></td>
					<td>
						<select name="invoiceapply[invoiceDetail][$i][psTyle]" class="txtshort">
							$productTypeStr
						</select>
					</td>
					<td><input type="text" class="txt" name="invoiceapply[invoiceDetail][$i][remark]"/></td>
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
	 * ��ʾ��Ʊ�ƻ� �� �鿴
	 */
	function initPlanView($object){
	}

	/*
	 * ��ʾ��Ʊ�ƻ��б�ģ�壬����ǲ鿴ҳ�棬��ֻ��ʾ�����ķ�Ʊ�ƻ�
	 */
	function initPlanEdit($invoicePlans) {
		$str = null; //���ص�ģ���ַ���
		if(!empty($invoicePlans)){
			$i = 0; //�б��¼���
			$dataDictDao = new model_system_datadict_datadict();
			foreach ( $invoicePlans as $key => $val ) {
				$checked = null;
				if (!empty($val['checked'] )){
					$checked = 'checked="true"';
				}
				$iType = $dataDictDao->getDataNameByCode($val['iType']);
				$i ++;
				$str .= <<<EOT
					<tr height="28px">
						<td><input type="checkbox" id="thisCheck_$val[id]" value="$val[id]" onclick="checkPlan();" $checked /></td>
						<td class="formatMoney">{$val['money']}</td>
						<td class="formatMoney">{$val['softM']}</td>
						<td>$iType</td>
						<td>{$val['invDT']}</td>
						<td>{$val['remark']}</td>
					</tr>
EOT;
			}
		}
		if(!empty($str)){
			return $str;
		}else{
			return '<tr height="28px"><td colspan="10">����</td></tr>';
		}
	}
	/****************************ҵ��ӿ�************************/

	/**
	 * ��ȡ������Ϣ
	 */
	function getObjInfo_d($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['objId']);

		//���ÿͻ����� - û�пͻ�����
//		$customerDao = new model_customer_customer_customer();
//		$customerArr = $customerDao->get_d($innerObj['signCompanyId']);
		$innerObj['customerName'] = $innerObj['signCompanyName'];

		//���ÿ�Ʊ����
		$innerObj['invoiceType'] = null;

		//��Ʊ������
		$innerObj['money']  = $innerObj['orderMoney'];
		unset($innerObj['orderMoney']);

		//ʣ��ɿ�����ͬ����ɿ���
		$innerObj['conCanApply'] = bcsub($innerObj['money'],$innerObj['uninvoiceMoney'],2);

		//ҵ����
		$innerObj['rObjCode']  = $innerObj['objCode'];
		unset($innerObj['objCode']);

        $innerObj['rObjCode']  = '';
        $innerObj['customerProvince']  = '';
        $innerObj['managerId']  = '';
        $innerObj['managerName']  = '';
        $innerObj['areaCode']  = $innerObj['areaId'];
        $innerObj['linkPhone']  = $innerObj['telephone'];
        $innerObj['linkMan']  = $innerObj['contactUserName'];

		return $innerObj;
	}


	/**
	 * ������Ʊ����ʱ��Ⱦ��Ʊ��ϸ��Ϣ
	 */
	function initAdd_d($obj){
		$obj['invoicePlans'] = $this->initPlanEdit($obj['invoice']);
		unset($obj['invoice']);

		return $obj;
	}

	/**
	 * ��ȡ������Ϣ
	 */
	public function getObjInfoInit_d($obj){
		$invoiceDao = new model_projectmanagent_order_invoice();
		return $invoiceDao->getDetail_d($obj['objId']);
	}

	/**
	 * �༭��Ʊ����ʱ��Ⱦ��Ʊ��ϸ��Ϣ
	 */
	function initEdit_d($rows){
		return $this->initPlanEdit($rows);
	}

	/**
	 * �鿴��Ʊ����ʱ��Ⱦ��Ʊ��ϸ��Ϣ
	 */
	function initView_d($rows){
		return $this->initPlanView($rows);
	}

	/**
	 * ��ȡ��Ʊ��ϸ����
	 */
	function getDetail($obj){

	}

	/**
	 *  ����ҵ����
	 */
	function businessDeal_i($obj,$mainObj){
	}
}