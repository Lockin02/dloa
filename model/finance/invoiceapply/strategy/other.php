<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include( WEB_TOR . 'model/finance/invoiceapply/iinvoiceapply.php');
/**
 * 销售开票申请策略
 */
class model_finance_invoiceapply_strategy_other extends model_base implements iinvoiceapply{

	/************************页面渲染**********************/
	private $thisClass = 'model_contract_other_other';

	/**
	 * 新增开票申请时自动渲染从表
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
						<img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行">
					</td>
				</tr>
EOT;
			}
		}
		return array($str,$i);
	}

	/**
	 * 显示开票计划 － 查看
	 */
	function initPlanView($object){
	}

	/*
	 * 显示开票计划列表模板，如果是查看页面，则只显示关联的发票计划
	 */
	function initPlanEdit($invoicePlans) {
		$str = null; //返回的模板字符串
		if(!empty($invoicePlans)){
			$i = 0; //列表记录序号
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
			return '<tr height="28px"><td colspan="10">暂无</td></tr>';
		}
	}
	/****************************业务接口************************/

	/**
	 * 获取数据信息
	 */
	function getObjInfo_d($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['objId']);

		//设置客户类型 - 没有客户类型
//		$customerDao = new model_customer_customer_customer();
//		$customerArr = $customerDao->get_d($innerObj['signCompanyId']);
		$innerObj['customerName'] = $innerObj['signCompanyName'];

		//设置开票类型
		$innerObj['invoiceType'] = null;

		//开票申请金额
		$innerObj['money']  = $innerObj['orderMoney'];
		unset($innerObj['orderMoney']);

		//剩余可开金额（合同本身可开）
		$innerObj['conCanApply'] = bcsub($innerObj['money'],$innerObj['uninvoiceMoney'],2);

		//业务编号
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
	 * 新增开票申请时渲染开票详细信息
	 */
	function initAdd_d($obj){
		$obj['invoicePlans'] = $this->initPlanEdit($obj['invoice']);
		unset($obj['invoice']);

		return $obj;
	}

	/**
	 * 获取订单信息
	 */
	public function getObjInfoInit_d($obj){
		$invoiceDao = new model_projectmanagent_order_invoice();
		return $invoiceDao->getDetail_d($obj['objId']);
	}

	/**
	 * 编辑开票申请时渲染开票详细信息
	 */
	function initEdit_d($rows){
		return $this->initPlanEdit($rows);
	}

	/**
	 * 查看开票申请时渲染开票详细信息
	 */
	function initView_d($rows){
		return $this->initPlanView($rows);
	}

	/**
	 * 获取开票详细内容
	 */
	function getDetail($obj){

	}

	/**
	 *  操作业务处理
	 */
	function businessDeal_i($obj,$mainObj){
	}
}